@extends('layouts.app')

@section('title', 'Cookie Policy - ' . config('site.name'))
@section('description', 'Which cookies and browser storage ' . config('site.name') . ' uses, what advertising may set, and how to control both.')

@section('content')
<article class="legal">

    <h1>Cookie Policy</h1>
    <p class="legal-updated">Last updated: {{ date('j F Y') }}</p>

    <p>
        This page explains what {{ config('site.name') }} stores in your browser and why.
        We have tried to describe only what is actually in use rather than listing
        categories that do not apply to this site.
    </p>

    <h2>Strictly necessary cookies</h2>
    <p>
        These are required for the site to function and cannot be switched off.
    </p>
    <table class="legal-table">
        <thead>
            <tr><th>Name</th><th>Set by</th><th>Purpose</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>Session cookie</td>
                <td>This site</td>
                <td>Maintains your session so a form submission can be matched to the page it came from.</td>
            </tr>
            <tr>
                <td>CSRF token</td>
                <td>This site</td>
                <td>Protects form submissions, such as the Word to PDF upload, against cross-site request forgery.</td>
            </tr>
        </tbody>
    </table>

    <h2>Browser storage for your preferences</h2>
    <p>
        These are not cookies and are never transmitted to us. They live in your browser's
        local storage and can be cleared at any time through your browser settings.
    </p>
    <table class="legal-table">
        <thead>
            <tr><th>Stored value</th><th>Purpose</th></tr>
        </thead>
        <tbody>
            <tr><td>Dark mode preference</td><td>Remembers whether you chose the dark theme.</td></tr>
            <tr><td>Recent colour palettes</td><td>Lets the colour palette tool show what you generated last.</td></tr>
            <tr><td>Recent conversions and scores</td><td>Lets the unit converter and the games show your recent history.</td></tr>
        </tbody>
    </table>

    <h2>Analytics</h2>
    <p>
        This site does not currently run a third-party analytics package. If one is added,
        this page will be updated to name it and describe what it collects before it goes
        live.
    </p>

    <h2>Advertising cookies</h2>
    @if (config('site.adsense.client_id'))
        <p>
            This site displays advertising through Google AdSense. Google and its partners
            may set cookies to serve ads, limit how often you see the same ad, and measure
            whether an ad worked. Where permitted, these may be used to show advertising
            based on your prior visits to this or other sites.
        </p>
        <p>
            If you are in the European Economic Area, the United Kingdom or Switzerland, a
            consent message from Google's certified consent management platform appears
            before personalised advertising cookies are used, and records your choices. You
            can reopen it at any time using the privacy options link it provides.
        </p>
        <p>
            You can also manage advertising personalisation directly at
            <a href="https://adssettings.google.com" rel="noopener noreferrer" target="_blank">Google Ads Settings</a>.
        </p>
    @else
        <p>
            This site does not currently display advertising and therefore sets no
            advertising cookies. If advertising is introduced, this page will be updated
            before it goes live, and visitors in the European Economic Area, the United
            Kingdom and Switzerland will be asked for their consent choices through a
            certified consent management platform.
        </p>
    @endif

    <h2>Third-party content delivery networks</h2>
    <p>
        Some tool pages load libraries from cdnjs and jsDelivr. These do not set cookies
        for us, but as with any web request the provider can see your IP address and which
        file was requested.
    </p>

    <h2>Controlling cookies</h2>
    <p>
        Every major browser lets you view, block and delete cookies and clear local
        storage. Blocking the strictly necessary cookies above will stop forms on this
        site from working; blocking the rest only means your preferences are not
        remembered.
    </p>

    <h2>Contact</h2>
    <p>
        Questions about this policy can be sent through our
        <a href="{{ url('/contact') }}">contact page</a>.
    </p>

</article>
@endsection
