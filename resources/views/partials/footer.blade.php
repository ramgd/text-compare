@php
    use App\Support\ToolRegistry;

    $footerCategories = ToolRegistry::categoriesWithTools();
    $contactEmail     = config('site.contact_email');
@endphp

<footer class="footer site-footer">
    <div class="footer-inner">

        <div class="footer-brand">
            <span class="footer-logo">{{ config('site.name') }}</span>
            <p class="footer-blurb">{{ config('site.description') }}</p>
        </div>

        <nav class="footer-cols" aria-label="Footer">

            <div class="footer-col">
                <h2 class="footer-heading">Tools</h2>
                <ul>
                    @foreach ($footerCategories as $category)
                        <li><a href="{{ url('/tools/' . $category['slug']) }}">{{ $category['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="footer-col">
                <h2 class="footer-heading">Resources</h2>
                <ul>
                    <li><a href="{{ url('/tools') }}">All Tools</a></li>
                    <li><a href="{{ url('/#how-it-works') }}">How It Works</a></li>
                    <li><a href="{{ url('/#faq') }}">FAQ</a></li>
                    <li><a href="{{ url('/sitemap.xml') }}">Sitemap</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h2 class="footer-heading">Company</h2>
                <ul>
                    <li><a href="{{ url('/about') }}">About</a></li>
                    <li><a href="{{ url('/contact') }}">Contact</a></li>
                    <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ url('/terms-of-service') }}">Terms of Service</a></li>
                </ul>
            </div>

        </nav>
    </div>

    <div class="footer-bottom">
        <p class="footer-copy">&copy; {{ date('Y') }} {{ config('site.name') }}. All rights reserved.</p>
        <ul class="footer-legal">
            <li><a href="{{ url('/privacy-policy') }}">Privacy Policy</a></li>
            <li><a href="{{ url('/cookie-policy') }}">Cookie Policy</a></li>
            <li><a href="{{ url('/terms-of-service') }}">Terms</a></li>
            <li><a href="{{ url('/disclaimer') }}">Disclaimer</a></li>
            @if ($contactEmail)
                <li><a href="mailto:{{ $contactEmail }}">Contact</a></li>
            @endif
        </ul>
    </div>
</footer>
