@extends('layouts.app')

@section('content')

<div class="json-container">

    <!-- LEFT EDITOR -->
    <div class="editor-box">
        <div class="editor-header">Input JSON</div>
        <div id="inputEditor" class="editor"></div>
    </div>

    <!-- BUTTONS -->
    <div class="json-actions">
        <button onclick="formatJSON()">Format</button>
        <button onclick="minifyJSON()">Minify</button>
        <button onclick="validateJSON()">Validate</button>
        <button onclick="copyJSON()">Copy</button>
        <button onclick="downloadJSON()">Download</button>
    </div>

    <!-- RIGHT EDITOR -->
    <div class="editor-box">
        <div class="editor-header">Output</div>
        <div id="outputEditor" class="editor"></div>
    </div>

</div>

@endsection