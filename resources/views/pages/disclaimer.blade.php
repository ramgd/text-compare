@extends('layouts.app')

@section('title', 'Disclaimer - ' . config('site.name'))
@section('description', 'What the tools on ' . config('site.name') . ' can and cannot do, with the specific limitations of each conversion tool stated plainly.')

@section('content')
<article class="legal">

    <h1>Disclaimer</h1>
    <p class="legal-updated">Last updated: {{ date('j F Y') }}</p>

    <p>
        The tools here are provided free and in good faith, but they are tools, not
        professional advice, and their output should be checked before you rely on it.
        This page sets out the specific limits of the ones where that matters most.
    </p>

    <h2>General</h2>
    <p>
        Results are produced automatically. We make no guarantee that any output is
        accurate, complete or suitable for your purpose. Always keep your original file,
        and review a result before sending it somewhere that matters.
    </p>

    <h2>PDF to Word</h2>
    <p>
        This extracts the text layer of a PDF into an editable Word document. It
        reproduces text, paragraph grouping, heading-sized lines and page order.
        It does <strong>not</strong> reproduce the original page layout, columns,
        images, vector graphics, backgrounds or embedded fonts. The result is an
        editable text document, not a visual copy of the original.
    </p>
    <p>
        A scanned PDF — effectively a photograph of a page with no text layer — cannot
        be converted this way. The tool detects that and tells you, rather than returning
        an empty document.
    </p>

    <h2>Word to PDF</h2>
    <p>
        Paragraphs, headings, bold and italic, alignment, lists, tables and page breaks
        are carried across. Because the document is rendered through HTML, precise
        positioning, embedded fonts, floating images and complex multi-column layouts
        are approximated rather than reproduced exactly. Only <code>.docx</code> is
        supported; the older binary <code>.doc</code> format is refused.
    </p>

    <h2>PDF merging</h2>
    <p>
        Pages are combined in the order the files are listed, and each page keeps its own
        size and rotation. Encrypted (password-protected) PDFs cannot be read and are
        reported rather than skipped silently. Form fields, annotations and digital
        signatures are not guaranteed to survive a merge.
    </p>

    <h2>PDF tools still in development</h2>
    <p>
        The Split, Compress, Protect, Convert and Edit tabs of the PDF Toolkit are not
        yet finished and do not currently perform real processing. Do not rely on their
        output. The Merge, PDF to Word and Word to PDF tools are complete and tested.
    </p>

    <h2>Optical character recognition</h2>
    <p>
        OCR accuracy depends heavily on the source image. Low resolution, poor lighting,
        skew, unusual fonts and handwriting all reduce it significantly. Always proofread
        extracted text.
    </p>

    <h2>Hashing</h2>
    <p>
        The hash tool implements standard algorithms and its output matches the published
        test vectors. MD5 and SHA-1 are included because they remain in wide use for
        checksums, but both are cryptographically broken and must not be used to protect
        anything. Plain hashes of any kind are not a safe way to store passwords.
    </p>

    <h2>Calculators and validators</h2>
    <p>
        The age, date and unit calculators are for general use and round their results for
        display. The email validator checks the format of an address only; it cannot tell
        you whether a mailbox exists. Do not use these where a legally or financially
        binding figure is required without independent verification.
    </p>

    <h2>Network tools</h2>
    <p>
        Speed measurements taken in a browser are indicative rather than definitive.
        IP-based location is approximate and is often wrong on mobile networks or behind
        a VPN.
    </p>

    <h2>External links and advertising</h2>
    <p>
        We are not responsible for the content of third-party sites we link to, or for
        products and services shown in advertising. Advertisements are supplied by an
        external network and their presence is not an endorsement.
    </p>

    <h2>Contact</h2>
    <p>
        If you find an inaccuracy, please tell us through our
        <a href="{{ url('/contact') }}">contact page</a> so we can correct it.
    </p>

</article>
@endsection
