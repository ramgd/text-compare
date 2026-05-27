@extends('layouts.app')

@section('content')

<div class="beautifier-container">

    <!-- INPUT -->
    <div class="editor-box">
        <div class="editor-header">
            Input Code
            <select id="language">
                <option value="javascript">JavaScript</option>
                <option value="html">HTML</option>
                <option value="css">CSS</option>
                <option value="json">JSON</option>
                <option value="sql">SQL</option>
            </select>
        </div>
        <div id="inputCode" class="editor"></div>
    </div>

    <!-- ACTIONS -->
    <div class="beautifier-actions">
        <button onclick="beautifyCode()">Beautify</button>
        <button onclick="minifyCode()">Minify</button>
        <button onclick="copyCode()">Copy</button>
        <button onclick="clearCode()">Clear</button>
    </div>

    <!-- OUTPUT -->
    <div class="editor-box">
        <div class="editor-header">Output</div>
        <div id="outputCode" class="editor"></div>
    </div>

</div>

@endsection