{{--
    A single ad slot.

    Usage:  @include('partials.ads.slot', ['position' => 'in-content'])

    Renders nothing unless a real publisher ID is configured, so there are no
    empty grey boxes on an unconfigured install. Slots are deliberately placed
    between content blocks - never inside a tool's controls - so an ad can
    never be mistaken for a button or sit where someone is about to tap.
--}}
@php
    $adsenseClient = config('site.adsense.client_id');
    $adsenseReady  = is_string($adsenseClient) && preg_match('/^ca-pub-\d{10,20}$/', $adsenseClient) === 1;
    $position      = $position ?? 'in-content';
    $slotId        = $slotId ?? null;
@endphp

@if ($adsenseReady)
    <aside class="ad-slot ad-slot--{{ $position }}" aria-label="Advertisement">
        <span class="ad-slot-label">Advertisement</span>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="{{ $adsenseClient }}"
             @if ($slotId) data-ad-slot="{{ $slotId }}" @endif
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
    </aside>
@endif
