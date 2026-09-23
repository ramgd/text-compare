@extends('layouts.app')

@section('title', config('site.name') . ' - Free Online Tools for PDFs, Text and Developers')
@section('description', 'Free browser-based tools for merging and converting PDFs, comparing text, generating hashes, formatting code and everyday calculations. Most run entirely in your browser, so your files never leave your device.')

@section('content')

<section class="hero">
    <h1 class="hero-title">Free online tools that respect your files</h1>
    <p class="hero-sub">
        {{ $toolCount }} browser-based tools for working with PDFs, text, code and everyday tasks.
        Most of them run entirely on your own device, so nothing you open is uploaded to us.
        No account, no installation, no cost.
    </p>

    <div class="hero-actions">
        <a class="btn-hero btn-hero-primary" href="{{ url('/tools') }}">Browse all tools</a>
        <a class="btn-hero btn-hero-ghost" href="#categories">See categories</a>
    </div>

    <form class="hero-search" role="search" onsubmit="return tdFilterSubmit(event)">
        <label class="sr-only" for="homeSearch">Search tools</label>
        <input type="search" id="homeSearch" class="tool-search-input"
               placeholder="Search tools, e.g. merge PDF or hash"
               autocomplete="off" oninput="tdFilter(this.value)">
    </form>

    <div id="homeSearchResults" class="search-results" hidden aria-live="polite"></div>
</section>

<section class="home-section" aria-labelledby="featured-heading">
    <h2 id="featured-heading" class="section-heading">Popular tools</h2>
    <p class="section-sub">The tools people reach for most often.</p>

    <div class="card-grid">
        @foreach ($featured as $tool)
            <a class="tool-card" href="{{ url($tool['path']) }}">
                <i class="{{ $tool['icon'] }}" aria-hidden="true"></i>
                <h3>{{ $tool['name'] }}</h3>
                <p>{{ $tool['summary'] }}</p>
                <span class="tool-card-cat">{{ \App\Support\ToolRegistry::categoryName($tool) }}</span>
                <span class="tool-card-cta">Open tool &rarr;</span>
            </a>
        @endforeach
    </div>
</section>

@include('partials.ads.slot', ['position' => 'in-content'])

<section class="home-section" id="categories" aria-labelledby="categories-heading">
    <h2 id="categories-heading" class="section-heading">Tool categories</h2>
    <p class="section-sub">Every tool on the site, grouped by what it does.</p>

    <div class="category-grid">
        @foreach ($categories as $category)
            <a class="category-card" href="{{ url('/tools/' . $category['slug']) }}">
                <i class="{{ $category['icon'] }}" aria-hidden="true"></i>
                <h3>{{ $category['name'] }}</h3>
                <p>{{ $category['blurb'] }}</p>
                <span class="category-count">{{ $category['count'] }} {{ Str::plural('tool', $category['count']) }}</span>
            </a>
        @endforeach
    </div>
</section>

<section class="home-section" aria-labelledby="why-heading">
    <h2 id="why-heading" class="section-heading">Why use these tools</h2>

    <div class="why-grid">
        <div class="why-item">
            <h3>Your files usually stay with you</h3>
            <p>
                Text comparison, hashing, PDF merging, PDF to Word, OCR and most other
                tools run in your browser using JavaScript. The file is read on your own
                device and never sent to us. The one exception is Word to PDF, which
                needs a server to read the document format - that upload is deleted
                immediately after the PDF is produced.
            </p>
        </div>
        <div class="why-item">
            <h3>No account required</h3>
            <p>
                There is no sign-up, no login and no profile. We do not have user accounts,
                so there is nothing to create and nothing for us to hold on to.
            </p>
        </div>
        <div class="why-item">
            <h3>Built for phones as well as desktops</h3>
            <p>
                Every page is laid out for small screens first, with touch-sized controls,
                and tested from 320&nbsp;pixels wide upwards including landscape.
            </p>
        </div>
        <div class="why-item">
            <h3>Honest about limits</h3>
            <p>
                Document conversion is never perfect. Each tool page explains what it
                reproduces well and what it does not, so you know what to check before
                relying on a result.
            </p>
        </div>
    </div>
</section>

<section class="home-section" id="how-it-works" aria-labelledby="how-heading">
    <h2 id="how-heading" class="section-heading">How it works</h2>

    <ol class="how-steps">
        <li>
            <span class="how-num">1</span>
            <h3>Choose a tool</h3>
            <p>Pick one from the categories above, or search by name.</p>
        </li>
        <li>
            <span class="how-num">2</span>
            <h3>Add your data</h3>
            <p>Paste your text, or select the file you want to work with.</p>
        </li>
        <li>
            <span class="how-num">3</span>
            <h3>Process and download</h3>
            <p>Run the tool and copy or download the result straight away.</p>
        </li>
    </ol>
</section>

<section class="home-section" id="faq" aria-labelledby="faq-heading">
    <h2 id="faq-heading" class="section-heading">Frequently asked questions</h2>

    <div class="faq-list">
        @php
            $homeFaqs = [
                ['q' => 'Are these tools free?', 'a' => 'Yes. Every tool on the site is free to use, with no account, no trial and no usage limit beyond the file size limits noted on each tool page.'],
                ['q' => 'Do I need to create an account?', 'a' => 'No. There are no user accounts on this site at all, so there is nothing to sign up for.'],
                ['q' => 'Are my files uploaded to your server?', 'a' => 'For almost every tool, no - the work happens in your browser and the file never leaves your device. The single exception is Word to PDF, which must send the document to our server to be read. That file is processed in a private temporary folder and deleted as soon as the PDF has been generated.'],
                ['q' => 'Do the tools work on a phone?', 'a' => 'Yes. Every page is designed for small screens first and tested from 320 pixels wide upwards, including landscape orientation.'],
                ['q' => 'Is there a file size limit?', 'a' => 'PDF merging and PDF to Word accept files up to 100 MB each. Word to PDF accepts .docx files up to 20 MB. Browser-based tools are also limited by your own device memory.'],
                ['q' => 'Why did my document conversion not look perfect?', 'a' => 'Converting between document formats is approximate by nature. Each tool page sets out what it reproduces reliably and what it does not, so you can check the parts that matter before using the result.'],
                ['q' => 'Do you use cookies?', 'a' => 'The site itself uses browser storage to remember small preferences such as dark mode. Advertising and any analytics may set their own cookies - our Cookie Policy explains what is in use.'],
            ];
        @endphp

        @foreach ($homeFaqs as $faq)
            <details class="faq-item">
                <summary>{{ $faq['q'] }}</summary>
                <p>{{ $faq['a'] }}</p>
            </details>
        @endforeach
    </div>
</section>

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => config('site.name'),
    'url' => rtrim(config('site.url'), '/') . '/',
    'description' => config('site.description'),
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => rtrim(config('site.url'), '/') . '/tools?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($homeFaqs ?? [])->map(fn ($faq) => [
        '@type' => 'Question',
        'name' => $faq['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@endsection

@push('scripts')
<script src="{{ asset('js/tool-directory.js') }}"></script>
@endpush
