@extends('layouts.app')

@section('title', $category['name'] . ' - Free Online ' . $category['name'] . ' | ' . config('site.name'))
@section('description', $category['blurb'] . ' ' . $tools->count() . ' free ' . strtolower($category['name']) . ', most running entirely in your browser with no upload and no account.')

@section('content')

<section class="page-head">
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ url('/') }}">Home</a>
        <span aria-hidden="true">/</span>
        <a href="{{ url('/tools') }}">All Tools</a>
        <span aria-hidden="true">/</span>
        <span aria-current="page">{{ $category['name'] }}</span>
    </nav>

    <h1><i class="{{ $category['icon'] }}" aria-hidden="true"></i> {{ $category['name'] }}</h1>
    <p class="page-lead">{{ $category['blurb'] }}</p>
</section>

<div class="card-grid">
    @foreach ($tools as $tool)
        <a class="tool-card" href="{{ url($tool['path']) }}">
            <i class="{{ $tool['icon'] }}" aria-hidden="true"></i>
            <h3>{{ $tool['name'] }}</h3>
            <p>{{ $tool['summary'] }}</p>
            <span class="tool-card-cta">Open tool &rarr;</span>
        </a>
    @endforeach
</div>

@if (!empty($category['description']))
    <section class="tool-doc">
        <div class="tool-doc-block">
            <h2>About {{ $category['name'] }}</h2>
            <p>{{ $category['description'] }}</p>
        </div>
    </section>
@endif

@include('partials.ads.slot', ['position' => 'bottom'])

@if (!empty($category['faqs']))
    <section class="home-section" aria-labelledby="cat-faq">
        <h2 id="cat-faq" class="section-heading">Frequently asked questions</h2>
        <div class="faq-list">
            @foreach ($category['faqs'] as $faq)
                <details class="faq-item">
                    <summary>{{ $faq['q'] }}</summary>
                    <p>{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </section>
@endif

<section class="home-section">
    <h2 class="section-heading">Other categories</h2>
    <div class="category-grid">
        @foreach (\App\Support\ToolRegistry::categoriesWithTools() as $other)
            @continue($other['slug'] === $category['slug'])
            <a class="category-card" href="{{ url('/tools/' . $other['slug']) }}">
                <i class="{{ $other['icon'] }}" aria-hidden="true"></i>
                <h3>{{ $other['name'] }}</h3>
                <p>{{ $other['blurb'] }}</p>
                <span class="category-count">{{ $other['count'] }} {{ Str::plural('tool', $other['count']) }}</span>
            </a>
        @endforeach
    </div>
</section>

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => rtrim(config('site.url'), '/') . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'All Tools', 'item' => rtrim(config('site.url'), '/') . '/tools'],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $category['name'], 'item' => rtrim(config('site.url'), '/') . '/tools/' . $category['slug']],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@if (!empty($category['faqs']))
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($category['faqs'])->map(fn ($faq) => [
        '@type' => 'Question',
        'name' => $faq['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
    ])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endpush

@endsection
