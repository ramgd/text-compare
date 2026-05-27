<!DOCTYPE html>
<html>

<head>

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

</body>

</html>