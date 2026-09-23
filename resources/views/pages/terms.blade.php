@extends('layouts.app')

@section('title', 'Terms of Service - ' . config('site.name'))
@section('description', 'The terms under which you may use ' . config('site.name') . ', including acceptable use, intellectual property and limitation of liability.')

@section('content')
<article class="legal">

    <h1>Terms of Service</h1>
    <p class="legal-updated">Last updated: {{ date('j F Y') }}</p>

    <p>
        By using {{ config('site.name') }} you agree to these terms. If you do not agree
        with them, please do not use the site.
    </p>

    <h2>The service</h2>
    <p>
        {{ config('site.name') }} provides a collection of free online utilities. Most run
        entirely in your browser; one, Word to PDF, processes an uploaded file on our
        server. There are no user accounts and no payment.
    </p>

    <h2>Acceptable use</h2>
    <p>You agree not to:</p>
    <ul>
        <li>use the site for any unlawful purpose, or to process material you have no right to process;</li>
        <li>upload material containing malware, or deliberately malformed files intended to attack the service;</li>
        <li>attempt to gain unauthorised access to the site, its server or any connected system;</li>
        <li>use automated means to place excessive load on the service, or to scrape it at a volume that degrades it for others;</li>
        <li>use the API Tester to attack, probe or overload a third-party service.</li>
    </ul>
    <p>
        We may restrict or block access where use appears to breach these terms or
        threatens the stability of the service.
    </p>

    <h2>Your content</h2>
    <p>
        You keep all rights in the files and text you process. We claim no ownership of
        them. For browser-based tools we never receive your content at all. For Word to
        PDF, you grant us only the limited permission needed to convert the file and
        return the result, after which the upload is deleted.
    </p>
    <p>
        You are responsible for having the right to process any file you use here,
        including any confidentiality obligations that attach to it.
    </p>

    <h2>No warranty</h2>
    <p>
        The service is provided "as is" and "as available", without warranties of any
        kind, whether express or implied. In particular we do not warrant that the
        service will be uninterrupted or error free, or that any conversion, calculation
        or other output will be accurate, complete or fit for a particular purpose.
    </p>
    <p>
        Document conversion is approximate by nature. Please read the
        <a href="{{ url('/disclaimer') }}">Disclaimer</a>, which explains the specific
        limitations of the conversion tools.
    </p>

    <h2>Limitation of liability</h2>
    <p>
        To the fullest extent permitted by law, we are not liable for any indirect,
        incidental, special or consequential loss, or for any loss of data, profit or
        business, arising from your use of or inability to use the service. Nothing in
        these terms excludes liability that cannot lawfully be excluded.
    </p>
    <p>
        You are responsible for keeping your own copies of any file you value. Do not
        rely on this site as the only place a document exists.
    </p>

    <h2>Third-party services and links</h2>
    <p>
        The site loads libraries from public content delivery networks and may link to
        third-party websites. We do not control those services and are not responsible
        for their content, availability or practices.
    </p>

    <h2>Advertising</h2>
    <p>
        The site may display advertising supplied by a third-party network. Advertisements
        are clearly separated from the tool controls. We do not endorse advertised
        products or services, and any dealing with an advertiser is between you and them.
    </p>

    <h2>Intellectual property</h2>
    <p>
        The site's design, text and original code belong to their respective owners. The
        open-source libraries used by the tools remain subject to their own licences.
        You may use the tools and their output freely; you may not copy the site itself
        and present it as your own.
    </p>

    <h2>Availability and changes</h2>
    <p>
        The service is free and provided without any guarantee of continued availability.
        Tools may be added, changed or withdrawn, and these terms may be updated. The
        current version is always the one on this page, with its date at the top.
    </p>

    <h2>Governing approach</h2>
    <p>
        These terms are intended to be read reasonably. Where a provision is found to be
        unenforceable, the rest continues to apply.
    </p>

    <h2>Contact</h2>
    <p>
        Questions about these terms can be sent through our
        <a href="{{ url('/contact') }}">contact page</a>.
    </p>

</article>
@endsection
