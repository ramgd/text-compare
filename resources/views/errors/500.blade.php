@extends('layouts.app')

@section('title', 'Something went wrong - ' . config('site.name'))
@section('description', 'An unexpected error occurred. Please try again, or pick another tool.')
@section('robots', 'noindex, follow')

@section('content')
<section class="error-page">
    <p class="error-code">500</p>
    <h1>Something went wrong at our end</h1>
    <p class="page-lead">
        This one is our fault, not yours. The error has been recorded so we can look at it.
        Trying again often works; if it does not, please let us know what you were doing.
    </p>

    <div class="hero-actions">
        <a class="btn-hero btn-hero-primary" href="{{ url('/tools') }}">Browse all tools</a>
        <a class="btn-hero btn-hero-ghost" href="{{ url('/contact') }}">Report the problem</a>
    </div>
</section>
@endsection
