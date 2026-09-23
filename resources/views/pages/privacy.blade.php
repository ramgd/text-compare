@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('site.name'))
@section('description', 'How ' . config('site.name') . ' handles your data: which tools run entirely in your browser, what the one server-side tool does with an uploaded file, and what cookies and advertising may set.')

@section('content')
<article class="legal">

    <h1>Privacy Policy</h1>
    <p class="legal-updated">Last updated: {{ date('j F Y') }}</p>

    <p>
        This policy describes what happens to your information when you use
        {{ config('site.name') }}. It is written to match what the site actually does.
        Where a tool processes data on your own device rather than on our server, it
        says so, because that distinction is the most important thing to understand
        about this site.
    </p>

    <h2>The short version</h2>
    <ul>
        <li>There are no user accounts. We do not ask you to register and we do not hold a profile about you.</li>
        <li>Almost every tool runs entirely in your browser. Those files and that text are never transmitted to us.</li>
        <li>One tool — Word to PDF — must upload your document to convert it. That file is deleted immediately after conversion.</li>
        <li>We do not sell your data.</li>
    </ul>

    <h2>Tools that run in your browser</h2>
    <p>
        The following tools do their work using JavaScript running on your own device.
        The file or text you provide is read locally and is <strong>never sent to our
        server</strong>: text comparison, hash generation, PDF merging, PDF to Word,
        Base64 encoding and decoding, URL encoding, image to text (OCR), text to image,
        the Markdown editor, the JSON formatter, the code beautifier, the SQL minifier,
        the password generator, the colour palette generator, the unit, date and age
        calculators, the email validator, the file converter and the mind map generator.
    </p>
    <p>
        Because the processing is local, we have no copy of that data, cannot recover it
        for you, and cannot disclose it to anyone. Closing the tab discards it.
    </p>

    <h2>The one tool that uploads a file: Word to PDF</h2>
    <p>
        Converting a Word document to PDF requires reading the Open XML document format,
        which needs to happen on a server. When you use Word to PDF:
    </p>
    <ul>
        <li>Your <code>.docx</code> file is sent to our server over an encrypted connection.</li>
        <li>It is checked for size and type, and verified to be a genuine Word package before anything reads it.</li>
        <li>It is written to a private, randomly named temporary directory that is not reachable from the web.</li>
        <li>The PDF is generated and returned to you directly in the response.</li>
        <li>The uploaded file and the temporary directory are deleted immediately afterwards, including when the conversion fails.</li>
    </ul>
    <p>
        We do not keep a library of converted documents, and we do not read the contents
        of your file for any purpose other than producing the PDF you asked for. If a
        conversion fails, a technical error message is written to our server log to help
        us fix the problem; that log entry records the file name and size, not the
        contents of the document.
    </p>

    <h2>The API Tester</h2>
    <p>
        The API Tester sends requests from your browser directly to whatever address you
        enter. Those requests do not pass through our server, so any credentials or API
        keys you use in it are not visible to us. The service you are calling will of
        course see the request.
    </p>

    <h2>Information collected automatically</h2>
    <p>
        Like almost all websites, our hosting infrastructure records standard technical
        information when a page is requested: the IP address, the time, the page
        requested, the referring page and the browser's user-agent string. This is used
        to operate the site, investigate errors and detect abuse.
    </p>

    <h2>Cookies and local storage</h2>
    <p>
        The site itself uses your browser's local storage to remember small preferences,
        such as whether you chose dark mode and your recently used colour palettes or
        unit conversions. That information stays in your browser and is never sent to us.
    </p>
    <p>
        Laravel, the framework this site runs on, sets a session cookie and a CSRF token
        cookie. These are strictly necessary to keep the site working and to protect form
        submissions against cross-site request forgery.
    </p>
    <p>
        Our <a href="{{ url('/cookie-policy') }}">Cookie Policy</a> sets out the detail,
        including cookies that advertising may set.
    </p>

    <h2>Advertising</h2>
    @if (config('site.adsense.client_id'))
        <p>
            This site displays advertising supplied by Google AdSense. Google and its
            partners may use cookies or similar technologies to serve and measure ads,
            including ads based on your prior visits to this or other websites.
        </p>
        <p>
            You can review and adjust the advertising Google shows you at
            <a href="https://adssettings.google.com" rel="noopener noreferrer" target="_blank">Google Ads Settings</a>,
            and opt out of personalised advertising from participating vendors at
            <a href="https://www.aboutads.info/choices/" rel="noopener noreferrer" target="_blank">aboutads.info/choices</a>.
        </p>
        <p>
            If you are in the European Economic Area, the United Kingdom or Switzerland,
            you will be asked for your consent choices before personalised advertising
            cookies are used, through Google's certified consent management platform.
            You can change those choices at any time using the privacy options link that
            the consent tool provides.
        </p>
    @else
        <p>
            This site does not currently display advertising. If advertising is introduced,
            this policy and our <a href="{{ url('/cookie-policy') }}">Cookie Policy</a> will
            be updated before it goes live to describe what the advertising provider
            collects and how to control it.
        </p>
    @endif

    <h2>Third-party services</h2>
    <p>
        Some pages load libraries from public content delivery networks (cdnjs and
        jsDelivr) so that tools such as the PDF engine, the OCR engine and the code
        editors work. Requesting a file from a CDN means that provider can see your IP
        address and the file requested, in the same way as any other web request. The
        IP Tools page additionally calls a third-party lookup service in order to report
        address details.
    </p>

    <h2>Children</h2>
    <p>
        This site is not directed at children and we do not knowingly collect personal
        information from them. Because there are no accounts, we do not ask for age or
        identity information at all.
    </p>

    <h2>Data retention</h2>
    <p>
        Files processed in your browser are never received by us, so there is nothing to
        retain. Files uploaded for Word to PDF are deleted immediately after conversion.
        Server logs are kept only as long as is useful for operating and securing the
        site.
    </p>

    <h2>Security</h2>
    <p>
        Uploads are limited in size, checked for type, and verified to be a genuine
        document package before being parsed. They are stored outside the web root while
        being processed and removed afterwards. The site is intended to be served over
        HTTPS in production so that traffic between your browser and the server is
        encrypted. No method of transmission or storage is completely secure, and we
        cannot guarantee absolute security.
    </p>

    <h2>Your rights</h2>
    <p>
        Depending on where you live, you may have rights to access, correct, delete or
        restrict the use of personal information about you, and to object to certain
        processing. Because we operate no accounts and retain no uploaded documents,
        in most cases we simply do not hold personal information about you to act on.
        If you believe we do, please contact us and we will respond.
    </p>
    <p>
        If you are in the EEA or the UK and you are not satisfied with our response, you
        have the right to complain to your local data protection authority.
    </p>

    <h2>International users</h2>
    <p>
        This site is available worldwide and is provided in English. Requests may be
        processed on servers located in a country other than your own. By using the site
        you understand that technical information described above may be processed in
        those locations.
    </p>

    <h2>Changes to this policy</h2>
    <p>
        If this policy changes, the revised version will be published on this page with a
        new date at the top. Significant changes affecting how data is handled will be
        described here rather than made quietly.
    </p>

    <h2>Contact</h2>
    @if (config('site.contact_email'))
        <p>
            Questions about this policy can be sent to
            <a href="mailto:{{ config('site.contact_email') }}">{{ config('site.contact_email') }}</a>.
        </p>
    @else
        <p>
            Questions about this policy can be sent through our
            <a href="{{ url('/contact') }}">contact page</a>.
        </p>
    @endif

</article>
@endsection
