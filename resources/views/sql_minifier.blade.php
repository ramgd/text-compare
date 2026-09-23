@extends('layouts.app')

@section('content')


@include('partials.tool-header')
<div class="sql-container">

    <!-- INPUT -->
    <div class="editor-box">
        <div class="editor-header">Input SQL</div>
        <textarea id="sqlInput" placeholder="Paste your SQL query here..."></textarea>
    </div>

    <!-- BUTTONS -->
    <div class="sql-actions">
        <button onclick="minifySQL()">Minify</button>
        <button onclick="formatSQL()">Format</button>
        <button onclick="copySQL()">Copy</button>
        <button onclick="clearSQL()">Clear</button>
    </div>

    <!-- OUTPUT -->
    <div class="editor-box">
        <div class="editor-header">Output</div>
        <textarea id="sqlOutput" readonly></textarea>
    </div>

</div>

@include('partials.tool-content')

@endsection

@push('scripts')
<script src="{{ asset('js/sql.js') }}"></script>
@endpush
