@php
    use App\Support\ToolRegistry;

    $siteName = config('site.name');
    $siteUrl  = config('site.url');

    /* A tool view does not need to repeat its own metadata: if it defines no
       title, the registry entry for the current path supplies a unique one.
       Pages outside the registry fall back to the site defaults. */
    $registryTool = ToolRegistry::findByPath(request()->path());

    $defaultTitle = $registryTool
        ? $registryTool['title'] . ' | ' . $siteName
        : $siteName;

    $defaultDescription = $registryTool
        ? $registryTool['summary']
        : config('site.description');

    $pageTitle       = trim($__env->yieldContent('title', $defaultTitle));
    $metaDescription = trim($__env->yieldContent('description', $defaultDescription));

    // Canonical: the current path on the configured production host, with no
    // query string, so parameterised variants do not fragment indexing.
    $path      = trim(request()->path(), '/');
    $canonical = $path === '' ? $siteUrl : $siteUrl . '/' . $path;

    $robots  = trim($__env->yieldContent('robots', 'index, follow'));
    $ogImage = $siteUrl . '/favicon.ico';
@endphp
<!DOCTYPE html>
<html lang="{{ config('site.locale') }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <link rel="canonical" href="{{ $canonical }}">

    <meta name="theme-color" content="{{ config('site.theme_color') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    @stack('meta')

    <!-- ===================================================================
         GLOBAL ASSETS ONLY.

         Tool-specific CSS/JS is pushed by each view via @@push('styles') and
         @@push('scripts') - see any tool blade for the pattern. Nothing that
         belongs to a single tool should be added here: loading every tool's
         script on every page is what produced the null-reference and
         ace.edit errors this layout used to throw on all 30 routes.
         =================================================================== -->

    <!-- Core layout, header/nav, footer, compare tool, dashboard, toaster -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Public site shell: hero, cards, tool docs, legal pages, footer -->
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">

    <!-- Icon font: used by the dashboard and most tool pages -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Per-page stylesheets -->
    @stack('styles')

    <!--  GLOBAL RESPONSIVE LAYER - must stay LAST so it wins the cascade -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @stack('structured-data')

    @include('partials.ads.head')

</head>

<body>

    <a class="skip-link" href="#main-content">Skip to content</a>

    @include('partials.header')

    @include('partials.back-link')

    <main id="main-content" class="container">
        @yield('content')
    </main>

    <!-- GLOBAL TOASTER -->
    <div id="toaster" class="toaster" role="status" aria-live="polite"></div>

    @include('partials.footer')

    <!--  GLOBAL common.JS SCRIPTS -->
    <script src="{{ asset('js/common.js') }}"></script>

    <!-- Per-page scripts (tool libraries + tool JS) -->
    @stack('scripts')

</body>

</html>
