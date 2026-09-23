<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Tests\TestCase;

/**
 * Covers the one server-side step of the PDF toolkit: POST
 * /pdf-toolkit/word-to-pdf, which turns an uploaded .docx into a PDF.
 *
 * The tests assert on real output (a %PDF- header, a non-trivial body) and on
 * the rejection paths, including that no server path or stack trace reaches
 * the response.
 */
class WordToPdfTest extends TestCase
{
    private string $workDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workDir = sys_get_temp_dir() . '/word2pdf-tests-' . getmypid();

        if (! is_dir($this->workDir)) {
            mkdir($this->workDir, 0700, true);
        }
    }

    protected function tearDown(): void
    {
        foreach (glob($this->workDir . '/*') ?: [] as $file) {
            @unlink($file);
        }

        @rmdir($this->workDir);

        parent::tearDown();
    }

    /** Build a .docx on disk and wrap it as an UploadedFile. */
    private function docx(string $name, callable $build): UploadedFile
    {
        $word = new PhpWord();
        $build($word);

        $path = $this->workDir . '/' . $name;
        IOFactory::createWriter($word, 'Word2007')->save($path);

        return new UploadedFile($path, $name, null, null, true);
    }

    private function plainFile(string $name, string $contents): UploadedFile
    {
        $path = $this->workDir . '/' . $name;
        file_put_contents($path, $contents);

        return new UploadedFile($path, $name, null, null, true);
    }

    private function convert(UploadedFile $file)
    {
        return $this->post('/pdf-toolkit/word-to-pdf', ['document' => $file]);
    }

    public function test_simple_docx_converts_to_a_real_pdf(): void
    {
        $file = $this->docx('simple.docx', function (PhpWord $word) {
            $word->addSection()->addText('Hello from a simple document.');
        });

        $response = $this->convert($file);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');

        $body = $response->getContent();

        $this->assertSame('%PDF-', substr($body, 0, 5), 'Response is not a PDF document.');
        $this->assertGreaterThan(500, strlen($body), 'PDF body is suspiciously small.');
    }

    public function test_formatted_docx_keeps_its_content(): void
    {
        $file = $this->docx('formatted.docx', function (PhpWord $word) {
            $section = $word->addSection();
            $section->addTitle('Annual Report', 1);
            $section->addText('This is bold.', ['bold' => true]);
            $section->addListItem('First bullet');

            $table = $section->addTable();
            $table->addRow();
            $table->addCell(3000)->addText('Region');
            $table->addCell(3000)->addText('Revenue');
        });

        $body = $this->convert($file)->assertOk()->getContent();

        $this->assertSame('%PDF-', substr($body, 0, 5));
        // Dompdf compresses content streams, so assert on structure not glyphs.
        $this->assertStringContainsString('/Type /Page', $body);
    }

    public function test_multi_page_docx_produces_multiple_pages(): void
    {
        $file = $this->docx('multipage.docx', function (PhpWord $word) {
            $section = $word->addSection();
            $section->addText('Page one.');
            $section->addPageBreak();
            $section->addText('Page two.');
            $section->addPageBreak();
            $section->addText('Page three.');
        });

        $body = $this->convert($file)->assertOk()->getContent();

        $pageCount = preg_match_all('#/Type\s*/Page[^s]#', $body);

        $this->assertGreaterThanOrEqual(3, $pageCount, 'Expected at least 3 PDF pages.');
    }

    public function test_legacy_doc_is_rejected_with_a_clear_message(): void
    {
        $file = $this->plainFile('legacy.doc', 'anything');

        $this->convert($file)
            ->assertStatus(422)
            ->assertJsonFragment(['message' => 'Legacy .doc files are not supported. Please save the document as .docx and try again.']);
    }

    public function test_non_docx_extension_is_rejected(): void
    {
        $file = $this->plainFile('notes.txt', 'hello');

        $this->convert($file)->assertStatus(422);
    }

    public function test_file_that_is_not_really_a_docx_is_rejected(): void
    {
        // Correct extension, but the bytes are not an Open XML package.
        $file = $this->plainFile('fake.docx', 'this is definitely not a docx');

        $response = $this->convert($file)->assertStatus(422);

        // Depending on what the MIME sniffer makes of the bytes this is
        // caught either by the type check or by the Open XML structure check.
        // Both are correct; what matters is that it is refused and explained.
        $message = $response->json('message');

        $this->assertMatchesRegularExpression(
            '/not a readable Word document|does not look like a Word document/',
            $message
        );
    }

    public function test_empty_file_is_rejected(): void
    {
        $file = $this->plainFile('empty.docx', '');

        $this->convert($file)->assertStatus(422);
    }

    public function test_missing_upload_is_rejected(): void
    {
        $this->post('/pdf-toolkit/word-to-pdf')
            ->assertStatus(422)
            ->assertJsonFragment(['message' => 'Please choose a Word document to convert.']);
    }

    public function test_an_empty_document_still_produces_a_valid_pdf(): void
    {
        $file = $this->docx('blank.docx', function (PhpWord $word) {
            $word->addSection();
        });

        $body = $this->convert($file)->assertOk()->getContent();

        $this->assertSame('%PDF-', substr($body, 0, 5));
    }

    public function test_failures_never_leak_server_paths_or_stack_traces(): void
    {
        $file = $this->plainFile('fake.docx', 'not a docx at all');

        $body = $this->convert($file)->assertStatus(422)->getContent();

        $this->assertStringNotContainsString('/var/www', $body);
        $this->assertStringNotContainsString('Stack trace', $body);
        $this->assertStringNotContainsString('vendor/', $body);
        $this->assertStringNotContainsString(base_path(), $body);
    }

    public function test_temporary_upload_directory_is_cleaned_up(): void
    {
        $file = $this->docx('cleanup.docx', function (PhpWord $word) {
            $word->addSection()->addText('Cleanup check.');
        });

        $this->convert($file)->assertOk();

        $leftovers = glob(storage_path('app/doc-convert/*')) ?: [];

        $this->assertSame([], $leftovers, 'Temporary conversion files were left behind.');
    }

    public function test_download_filename_is_derived_safely(): void
    {
        // Written under a safe name, but presented with a hostile client name.
        $onDisk = $this->docx('safe-name.docx', function (PhpWord $word) {
            $word->addSection()->addText('Name handling.');
        });

        $file = new UploadedFile(
            $onDisk->getPathname(),
            '../../etc/pa ss wd.docx',
            null,
            null,
            true
        );

        $disposition = $this->convert($file)->assertOk()->headers->get('Content-Disposition');

        $this->assertStringNotContainsString('..', $disposition);
        $this->assertStringNotContainsString('/', str_replace('attachment; filename="', '', $disposition));
        $this->assertStringEndsWith('.pdf"', $disposition);
    }
}
