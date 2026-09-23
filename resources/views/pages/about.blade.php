@extends('layouts.app')

@section('title', 'About - ' . config('site.name'))
@section('description', 'What ' . config('site.name') . ' is, why it exists, how the tools process your files, and what we do and do not claim about them.')

@section('content')
<article class="legal">

    <h1>About {{ config('site.name') }}</h1>

    <p class="page-lead">
        {{ config('site.name') }} is a free collection of {{ $toolCount }} online utilities
        for working with documents, text, code and everyday calculations. There is no
        account to create, nothing to install, and no charge.
    </p>

    <h2>Why it exists</h2>
    <p>
        Most small digital jobs — merging two PDFs, checking what changed between two
        drafts, generating a checksum, reformatting a block of JSON — do not need a
        desktop application or a subscription. They need one page that does the job
        properly and then gets out of the way. That is what this site tries to be.
    </p>
    <p>
        A second reason is privacy. A lot of online converters upload whatever you give
        them to a server you know nothing about. Modern browsers are perfectly capable of
        doing most of this work locally, so wherever that is technically possible here,
        that is how it is built.
    </p>

    <h2>How your files are handled</h2>
    <p>
        Almost every tool on this site runs entirely in your browser. When you merge PDFs,
        compare text, generate a hash, extract text from an image or convert a PDF to Word,
        the file is read on your own device by JavaScript and never transmitted to us. We
        could not see it if we wanted to.
    </p>
    <p>
        There is one exception, and it is worth being clear about it. Word to PDF has to
        read the Open XML document format, which is not practical in a browser, so that
        file is uploaded. It is size-limited, checked to be a genuine Word package before
        anything parses it, processed in a private temporary directory, and deleted as
        soon as the PDF is returned. Our
        <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> describes this in detail.
    </p>

    <h2>What you will find here</h2>
    <div class="about-cats">
        @foreach ($categories as $category)
            <div class="about-cat">
                <h3><i class="{{ $category['icon'] }}" aria-hidden="true"></i>
                    <a href="{{ url('/tools/' . $category['slug']) }}">{{ $category['name'] }}</a></h3>
                <p>{{ $category['blurb'] }}</p>
                <p class="about-cat-list">
                    @foreach ($category['tools'] as $tool)
                        <a href="{{ url($tool['path']) }}">{{ $tool['name'] }}</a>@if(!$loop->last), @endif
                    @endforeach
                </p>
            </div>
        @endforeach
    </div>

    <h2>What we do not claim</h2>
    <p>
        Converting between document formats is approximate. Anyone promising a perfect,
        pixel-identical conversion is overselling it. Each tool page here states what that
        tool reproduces reliably and what it does not, and the
        <a href="{{ url('/disclaimer') }}">Disclaimer</a> collects those limits in one
        place. Where part of a tool is unfinished — several tabs of the PDF Toolkit are —
        we say so rather than letting it look complete.
    </p>
    <p>
        We also do not publish user counts, testimonials, ratings or awards, because we
        would be making them up.
    </p>

    <h2>How it is funded</h2>
    @if (config('site.adsense.client_id'))
        <p>
            The site is supported by advertising, which is what keeps the tools free. Ads
            are kept away from the tool controls so they cannot be mistaken for buttons or
            cause an accidental tap.
        </p>
    @else
        <p>
            The site is free to use. If advertising is introduced to cover running costs,
            it will be kept away from the tool controls so it cannot be mistaken for a
            button, and the <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> and
            <a href="{{ url('/cookie-policy') }}">Cookie Policy</a> will be updated before
            it goes live.
        </p>
    @endif

    <h2>Get in touch</h2>
    <p>
        Bug reports, corrections and suggestions for new tools are all welcome — see the
        <a href="{{ url('/contact') }}">contact page</a>. If a tool gives you a wrong
        result, we would genuinely like to know.
    </p>

</article>
@endsection
