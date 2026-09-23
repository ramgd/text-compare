@extends('layouts.app')

@section('content')


@include('partials.tool-header')
<div class="pg-container">

    <h2>🔐 Password Generator</h2>

    <!-- OUTPUT -->
    <div class="output-box">
        <input type="text" id="password" readonly>
        <button onclick="copyPassword()">Copy</button>
    </div>

    <!-- OPTIONS -->
    <div class="options">

        <label>Password Length: <span id="lenVal">12</span></label>
        <input type="range" min="4" max="32" value="12" id="length">

        <label><input type="checkbox" id="uppercase" checked> Uppercase (A-Z)</label>
        <label><input type="checkbox" id="lowercase" checked> Lowercase (a-z)</label>
        <label><input type="checkbox" id="numbers" checked> Numbers (0-9)</label>
        <label><input type="checkbox" id="symbols"> Symbols (!@#$)</label>

    </div>

    <!-- BUTTON -->
    <button class="generate" onclick="generatePassword()">Generate Password</button>

</div>

@include('partials.tool-content')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/password.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/password.js') }}"></script>
@endpush
