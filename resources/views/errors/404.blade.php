@extends('layouts.app')

@section('title', 'Page not found - ' . config('site.name'))
@section('description', 'That page does not exist. Browse the full list of free online tools instead.')
@section('robots', 'noindex, follow')

@section('content')
<section class="error-page">
    <p class="error-code">404</p>
    <h1>We could not find that page</h1>
    <p class="page-lead">
        The link may be out of date, or the address may have a typo in it.
        Everything on the site is reachable from the tool directory.
    </p>

    <div class="hero-actions">
        <a class="btn-hero btn-hero-primary" href="{{ url('/tools') }}">Browse all tools</a>
        <a class="btn-hero btn-hero-ghost" href="{{ url('/') }}">Go to the homepage</a>
    </div>

    <div class="error-suggestions">
        <h2>Popular tools</h2>
        <div class="card-grid">
            @foreach (\App\Support\ToolRegistry::featured()->take(4) as $tool)
                <a class="tool-card" href="{{ url($tool['path']) }}">
                    <i class="{{ $tool['icon'] }}" aria-hidden="true"></i>
                    <h3>{{ $tool['name'] }}</h3>
                    <p>{{ $tool['summary'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
