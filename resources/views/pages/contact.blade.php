@extends('layouts.app')

@section('title', 'Contact - ' . config('site.name'))
@section('description', 'How to get in touch with ' . config('site.name') . ' about a bug, a correction, a privacy question or a suggestion for a new tool.')

@section('content')
<article class="legal">

    <h1>Contact</h1>

    <p class="page-lead">
        We read everything that comes in. Please include enough detail for us to
        reproduce a problem — which tool, what you did, and what happened.
    </p>

    @if (config('site.contact_email'))
        <div class="contact-card">
            <h2>Email</h2>
            <p class="contact-email">
                <a href="mailto:{{ config('site.contact_email') }}">{{ config('site.contact_email') }}</a>
            </p>
        </div>
    @else
        <div class="contact-card contact-card--pending">
            <h2>Email</h2>
            <p>
                A public contact address has not been configured for this deployment yet.
                Set <code>CONTACT_EMAIL</code> in the environment configuration and it
                will appear here and in the footer.
            </p>
        </div>
    @endif

    <h2>What to get in touch about</h2>
    <dl class="contact-reasons">
        <dt>A tool gave a wrong result</dt>
        <dd>Tell us the tool, the input you used and the output you expected. This is the most useful kind of message we get.</dd>

        <dt>Something is broken</dt>
        <dd>Let us know the page, your browser and whether it happens every time.</dd>

        <dt>Privacy questions</dt>
        <dd>Our <a href="{{ url('/privacy-policy') }}">Privacy Policy</a> covers how each kind of tool handles data. If it does not answer your question, ask.</dd>

        <dt>Suggest a tool</dt>
        <dd>Tell us the job you are trying to do, not just the feature — it usually leads to a better tool.</dd>

        <dt>Advertising and business enquiries</dt>
        <dd>Use the same address and say what it is about in the subject line.</dd>
    </dl>

    <h2>Before you write</h2>
    <p>
        A few things are already answered elsewhere: what each tool can and cannot do is
        on its own page and in the <a href="{{ url('/disclaimer') }}">Disclaimer</a>,
        and how files are handled is in the
        <a href="{{ url('/privacy-policy') }}">Privacy Policy</a>.
    </p>

    <h2>Privacy notice for messages</h2>
    <p>
        When you email us we receive your address and whatever you put in the message. We
        use it only to reply and to fix what you reported. Please do not send confidential
        documents or credentials — if a file is needed to reproduce a bug, we will ask for
        the smallest example that shows the problem.
    </p>

</article>
@endsection
