@extends('layouts.app')

@section('title', 'All Tools - ' . config('site.name'))
@section('description', 'Browse all ' . $toolCount . ' free online tools: PDF merging and conversion, text comparison, developer utilities, image tools, calculators and more. Search or filter by category.')

@section('content')

<section class="page-head">
    <h1>All tools</h1>
    <p class="page-lead">
        Every tool on the site, grouped by category. {{ $toolCount }} in total, all free and
        most running entirely in your browser. Use the search box to jump straight to one.
    </p>
</section>

<div class="tool-search">
    <label class="sr-only" for="toolSearch">Search tools</label>
    <input type="search" id="toolSearch" class="tool-search-input"
           placeholder="Search by name or what it does…"
           autocomplete="off" oninput="tdFilter(this.value)">
    <p id="toolSearchStatus" class="tool-search-status" aria-live="polite"></p>
</div>

@foreach ($categories as $category)
    <section class="home-section tool-category-block" data-category-block
             aria-labelledby="cat-{{ $category['slug'] }}">
        <h2 id="cat-{{ $category['slug'] }}" class="section-heading">
            <i class="{{ $category['icon'] }}" aria-hidden="true"></i>
            <a href="{{ url('/tools/' . $category['slug']) }}">{{ $category['name'] }}</a>
        </h2>
        <p class="section-sub">{{ $category['blurb'] }}</p>

        <div class="card-grid">
            @foreach ($category['tools'] as $tool)
                <a class="tool-card" href="{{ url($tool['path']) }}"
                   data-tool-card
                   data-search="{{ strtolower($tool['name'] . ' ' . $tool['summary'] . ' ' . implode(' ', $tool['keywords'] ?? [])) }}">
                    <i class="{{ $tool['icon'] }}" aria-hidden="true"></i>
                    <h3>{{ $tool['name'] }}</h3>
                    <p>{{ $tool['summary'] }}</p>
                    <span class="tool-card-cta">Open tool &rarr;</span>
                </a>
            @endforeach
        </div>
    </section>
@endforeach

<p id="toolSearchEmpty" class="tool-search-empty" hidden>
    No tools match that search. Try a different word, or
    <a href="{{ url('/tools') }}">browse everything</a>.
</p>

@endsection

@push('scripts')
<script src="{{ asset('js/tool-directory.js') }}"></script>
@endpush
