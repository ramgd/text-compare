<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Text Compare Tool')</title>

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

    <!-- Icon font: used by the dashboard and most tool pages -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Per-page stylesheets -->
    @stack('styles')

    <!--  GLOBAL RESPONSIVE LAYER - must stay LAST so it wins the cascade -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

</head>

<body>

    @include('partials.header')

    <div class="container">
        @yield('content')
    </div>
    <!-- GLOBAL TOASTER -->
    <div id="toaster" class="toaster"></div>
    @include('partials.footer')

    <!--  GLOBAL common.JS SCRIPTS -->
    <script src="{{ asset('js/common.js') }}"></script>

    <!-- Per-page scripts (tool libraries + tool JS) -->
    @stack('scripts')

</body>

</html>
