{{--
    Google AdSense loader.

    Renders nothing at all unless ADSENSE_CLIENT_ID is set to a real publisher
    ID (ca-pub-XXXXXXXXXXXXXXXX). No placeholder ID is ever emitted, so an
    unconfigured install serves a clean page with no broken ad code.

    Consent for the EEA, the UK and Switzerland is handled by Google's own
    Privacy & Messaging CMP, configured in the AdSense account rather than in
    this codebase - see docs/ADSENSE-SETUP.md. No home-grown consent banner is
    used, because a banner that does not actually gate TCF signals would be
    worse than none.
--}}
@php
    $adsenseClient = config('site.adsense.client_id');
    $adsenseReady  = is_string($adsenseClient) && preg_match('/^ca-pub-\d{10,20}$/', $adsenseClient) === 1;
@endphp

@if ($adsenseReady)
    <script async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ $adsenseClient }}"
            crossorigin="anonymous"></script>
@endif
