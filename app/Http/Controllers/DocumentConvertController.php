<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings as PhpWordSettings;
use Throwable;
use ZipArchive;

/**
 * Word -> PDF conversion.
 *
 * The rest of the PDF toolkit runs in the browser, but a faithful .docx
 * render needs a real Open XML reader, so this one step happens server-side
 * with PhpWord + Dompdf.
 *
 * Uploads are untrusted: the request is size-limited, the extension and MIME
 * type are checked, and the file is then verified to actually be an Open XML
 * word package before any parser touches it. The upload lives in a private
 * temp directory that is always removed, and the PDF is streamed back rather
 * than being written anywhere public.
 */
class DocumentConvertController extends Controller
{
    /** Largest .docx we will accept, in kilobytes (20 MB). */
    private const MAX_KILOBYTES = 20480;

    private const DOCX_MIMES = [
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/zip',       // some browsers report a bare zip
        'application/octet-stream',
    ];

    public function wordToPdf(Request $request)
    {
        $file = $request->file('document');

        if ($file === null) {
            return $this->fail('Please choose a Word document to convert.', 422);
        }

        if (! $file->isValid()) {
            return $this->fail(
                'The upload did not complete. The file may be larger than the server allows.',
                422
            );
        }

        // --- extension -------------------------------------------------
        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === 'doc') {
            return $this->fail(
                'Legacy .doc files are not supported. Please save the document as .docx and try again.',
                422
            );
        }

        if ($extension !== 'docx') {
            return $this->fail('Only .docx Word documents are supported.', 422);
        }

        // --- size ------------------------------------------------------
        if ($file->getSize() === 0) {
            return $this->fail('That file is empty.', 422);
        }

        if ($file->getSize() > self::MAX_KILOBYTES * 1024) {
            return $this->fail(
                'That document is larger than ' . round(self::MAX_KILOBYTES / 1024) . ' MB.',
                422
            );
        }

        // --- reported MIME (a hint only, never the sole check) ----------
        $mime = $file->getMimeType();

        if ($mime !== null && ! in_array($mime, self::DOCX_MIMES, true)) {
            return $this->fail('That file does not look like a Word document.', 422);
        }

        // --- real structure: a .docx is a zip holding word/document.xml --
        if (! $this->looksLikeDocx($file->getRealPath())) {
            return $this->fail(
                'That file is not a readable Word document. It may be corrupted or saved in another format.',
                422
            );
        }

        // Private per-request working directory, always cleaned up below.
        $workDir = storage_path('app/doc-convert/' . bin2hex(random_bytes(16)));

        if (! @mkdir($workDir, 0700, true) && ! is_dir($workDir)) {
            Log::error('word->pdf: could not create work directory', ['dir' => $workDir]);

            return $this->fail('The server could not process this file right now. Please try again.', 500);
        }

        $sourcePath = $workDir . '/source.docx';

        try {
            // move() keeps the bytes off any web-served path.
            $file->move($workDir, 'source.docx');

            $html = $this->docxToHtml($sourcePath);
            $pdf  = $this->htmlToPdf($html);

            $downloadName = $this->safeDownloadName($file->getClientOriginalName());

            return response($pdf, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
                'Content-Length'      => (string) strlen($pdf),
                'X-Content-Type-Options' => 'nosniff',
            ]);
        } catch (Throwable $e) {
            // Full detail to the log, a plain sentence to the user.
            Log::error('word->pdf conversion failed', [
                'file'      => $file->getClientOriginalName(),
                'size'      => $file->getSize(),
                'exception' => $e->getMessage(),
            ]);

            return $this->fail(
                'Unable to convert this document. It may be corrupted, password-protected, or use unsupported features.',
                422
            );
        } finally {
            $this->removeDirectory($workDir);
        }
    }

    /**
     * A .docx is an Open XML package: a zip whose payload includes
     * word/document.xml. Checking that rejects renamed files and most
     * corrupt uploads before a parser sees them.
     */
    private function looksLikeDocx(string $path): bool
    {
        if (! is_readable($path)) {
            return false;
        }

        $zip = new ZipArchive();

        if ($zip->open($path, ZipArchive::CHECKCONS) !== true) {
            // Retry without consistency checks: some writers produce archives
            // that are readable but fail the strict check.
            if ($zip->open($path) !== true) {
                return false;
            }
        }

        $hasDocument = $zip->locateName('word/document.xml') !== false;
        $zip->close();

        return $hasDocument;
    }

    /**
     * PhpWord reads the .docx and writes HTML, which Dompdf can lay out.
     * Paragraphs, headings, bold/italic, alignment, lists and tables survive
     * this route; precise positioning and embedded fonts do not.
     */
    private function docxToHtml(string $path): string
    {
        PhpWordSettings::setOutputEscapingEnabled(true);

        $document = IOFactory::load($path, 'Word2007');

        $writer = IOFactory::createWriter($document, 'HTML');

        ob_start();

        try {
            $writer->save('php://output');

            return (string) ob_get_clean();
        } catch (Throwable $e) {
            ob_end_clean();

            throw $e;
        }
    }

    private function htmlToPdf(string $html): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', false);      // never fetch remote URLs from a document
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', false);         // never evaluate PHP from a document
        $options->set('defaultFont', 'DejaVu Sans');  // ships with Dompdf, covers accents
        $options->set('chroot', storage_path('app'));

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        if (! is_string($output) || strncmp($output, '%PDF-', 5) !== 0) {
            throw new \RuntimeException('Dompdf did not return a PDF document.');
        }

        return $output;
    }

    /** Strip any path information the client sent and force a .pdf name. */
    private function safeDownloadName(string $originalName): string
    {
        $base = pathinfo($originalName, PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9 ._-]/', '', $base ?? '');
        $base = trim((string) $base);

        if ($base === '') {
            $base = 'document';
        }

        return mb_substr($base, 0, 80) . '.pdf';
    }

    private function removeDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $path = $dir . '/' . $entry;

            is_dir($path) ? $this->removeDirectory($path) : @unlink($path);
        }

        @rmdir($dir);
    }

    private function fail(string $message, int $status): JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}
