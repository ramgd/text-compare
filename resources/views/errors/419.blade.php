@extends('layouts.app')

@section('title', 'Your session expired - ' . config('site.name'))
@section('description', 'The page was open too long and its security token expired. Reload and try again.')
@section('robots', 'noindex, follow')

@section('content')
<section class="error-page">
    <p class="error-code">419</p>
    <h1>Your session expired</h1>
    <p class="page-lead">
        The page had been open long enough for its security token to expire, so the
        submission was rejected. Reload the page and try once more — nothing was lost.
    </p>

    <div class="hero-actions">
        <a class="btn-hero btn-hero-primary" href="{{ url()->previous() }}">Reload and try again</a>
        <a class="btn-hero btn-hero-ghost" href="{{ url('/') }}">Go to the homepage</a>
    </div>
</section>
@endsection
