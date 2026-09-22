<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Text Compare Tool</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!--  JSON CSS ADD HERE -->
    <link rel="stylesheet" href="{{ asset('css/json.css') }}">
    <!--  Password CSS ADD HERE -->
    <link rel="stylesheet" href="{{ asset('css/password.css') }}">
    <!--  SQL CSS ADD HERE -->
    <!-- <link rel="stylesheet" href="{{ asset('css/sql.css') }}"> -->
    <!--  API Tester LIKE POSTMAN JS ADD HERE -->
    <link rel="stylesheet" href="{{ asset('css/api.css') }}">
    <!--  QR-CODE generator ADD HERE -->
    <link rel="stylesheet" href="{{ asset('css/qr.css') }}">
    <!--  Image To Text -->
    <link rel="stylesheet" href="{{ asset('css/image-to-text.css') }}">
    <link rel="stylesheet" href="{{ asset('css/text-to-image.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base64.css') }}">
    <link rel="stylesheet" href="{{ asset('css/age-calculator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hash-generator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/color-palette.css') }}">

    <link rel="stylesheet" href="{{ asset('css/unit-converter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/markdown-editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ip-tools.css') }}">
    <link rel="stylesheet" href="{{ asset('css/date-calculator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/email-validator.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-converter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/url-encoder.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('css/mind-map.css') }}"> -->
<link rel="stylesheet" href="{{ asset('css/pdf-toolkit.css') }}">
<link rel="stylesheet" href="{{ asset('css/tractor-game.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('css/memory-game.css') }}"> -->
<link rel="stylesheet" href="{{ asset('css/zipzap-game.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('css/speed-test.css') }}">
<link rel="stylesheet" href="{{ asset('css/speed-checker.css') }}"> -->



    <!--  GLOBAL RESPONSIVE LAYER - must stay LAST so it wins the cascade -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/diff_match_patch/20121119/diff_match_patch.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/jsoneditor@9.10.0/dist/jsoneditor.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsoneditor@9.10.0/dist/jsoneditor.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- For BEAUTIFIER -->
    <!-- BEAUTIFIER LIBS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-html.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-css.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sql-formatter@11.3.0/dist/sql-formatter.min.js"></script>
    <!-- For qr code generate -->
    <script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.9.2/lib/qr-code-styling.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/2.0.0/pdf-lib.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script> -->

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
    <!-- <script src="{{ asset('js/app.js') }}"></script> -->
    <!--  Compare JS ADD HERE -->
    <script src="{{ asset('js/compare.js') }}"></script>
    <!--  JSON JS ADD HERE -->
    <script src="{{ asset('js/json.js') }}"></script>
    <!--  Password JS ADD HERE -->
    <script src="{{ asset('js/password.js') }}"></script>
    <!--  SQL JS ADD HERE -->
    <script src="{{ asset('js/sql.js') }}"></script>
    <!--  BEAUTIFIER JS ADD HERE -->
    <script src="{{ asset('js/beautifier.js') }}"></script>
    <!--  API Tester LIKE POSTMAN JS ADD HERE -->
    <script src="{{ asset('js/api.js') }}"></script>
    <!--  QR-CODE generator ADD HERE -->
    <script src="{{ asset('js/qr.js') }}"></script>
    <!--  OCR JS ADD HERE - Image To Text -->
    <script src="https://unpkg.com/tesseract.js@5/dist/tesseract.min.js"></script>
    <script src="{{ asset('js/image-to-text.js') }}"></script>
    <!--  Text To Image JS ADD HERE -->
    <script src="{{ asset('js/text-to-image.js') }}"></script>
    <script src="{{ asset('js/base64.js') }}"></script>
    <script src="{{ asset('js/age-calculator.js') }}"></script>
    <!-- <script src="{{ asset('js/hash-generator.js') }}"></script> -->
    <script src="{{ asset('js/hash-generator.js') }}"></script>
    <script src="{{ asset('js/color-palette.js') }}"></script>
    <script src="{{ asset('js/unit-converter.js') }}"></script>
    <script src="{{ asset('js/markdown-editor.js') }}"></script>
    <script src="{{ asset('js/ip-tools.js') }}"></script>
    <script src="{{ asset('js/date-calculator.js') }}"></script>
    <script src="{{ asset('js/email-validator.js') }}"></script>
    <script src="{{ asset('js/file-converter.js') }}"></script>
    <script src="{{ asset('js/url-encoder.js') }}"></script>
    <!-- <script src="{{ asset('js/mind-map.js') }}"></script> -->
<script src="{{ asset('js/pdf-toolkit.js') }}"></script>
<script src="{{ asset('js/tractor-game.js') }}"></script>
<!-- <script src="{{ asset('js/memory-game.js') }}"></script> -->
<script src="{{ asset('js/zipzap-game.js') }}"></script>
<!-- <script src="{{ asset('js/speed-test.js') }}"></script> -->
<!-- <script src="{{ asset('js/speed-checker.js') }}"></script> -->


</body>

</html>