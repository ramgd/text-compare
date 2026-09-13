@extends('layouts.app')

@section('content')

<div class="dashboard">

    <h2 class="dashboard-title">🚀 All Tools Dashboard</h2>

    <div class="card-grid">

        <!-- COMPARE -->
        <div class="tool-card" onclick="go('/')">
            <i class="fas fa-code"></i>
            <h3>Text Compare</h3>
            <p>Compare text & code with highlights</p>
        </div>

        <!-- JSON -->
        <div class="tool-card" onclick="go('/json_formatter')">
            <i class="fas fa-file-code"></i>
            <h3>JSON Formatter</h3>
            <p>Format, Validate & Beautify JSON</p>
        </div>

        <!-- PASSWORD -->
        <div class="tool-card" onclick="go('/password-generator')">
            <i class="fas fa-key"></i>
            <h3>Password Generator</h3>
            <p>Generate secure passwords</p>
        </div>

        <!-- SQL -->
        <div class="tool-card" onclick="go('/sql_minifier')">
            <i class="fas fa-database"></i>
            <h3>SQL Minifier</h3>
            <p>Minify & Format SQL queries</p>
        </div>

        <!-- CODE BEAUTIFIER -->
        <div class="tool-card" onclick="go('/code-beautifier')">
            <i class="fas fa-code"></i>
            <h3>Code Beautifier</h3>
            <p>Format and beautify code</p>
        </div>

        <!-- API TESTER LIKE POSTMAN -->
        <div class="tool-card" onclick="go('/api_tester')">
            <i class="fas fa-code"></i>
            <h3>API Tester</h3>
            <p>Test and debug your APIs</p>
        </div>

        <!-- QR-CODE GENERATOR -->
        <div class="tool-card" onclick="go('/qr-generator')">
            <i class="fas fa-qrcode"></i>
            <h3>QR Code Generator</h3>
            <p>Generate QR codes for any text or URL</p>
        </div>

        <!-- IMAGE TO TEXT -->
        <div class="tool-card" onclick="go('/image-to-text')">
            <i class="fas fa-image"></i>
            <h3>Image to Text</h3>
            <p>Extract text from images</p>
        </div>

        <!-- TEXT TO IMAGE -->
        <div class="tool-card" onclick="go('/text-to-image')">
            <i class="fas fa-text-height"></i>
            <h3>Text to Image</h3>
            <p>Convert text to images</p>
        </div>

        <!-- BASE64 ENCODER/DECODER -->
        <div class="tool-card" onclick="go('/base64')">
            <i class="fas fa-code"></i>
            <h3>Base64 Encoder/Decoder</h3>
            <p>Encode and decode Base64 strings</p>
        </div>

        <!-- AGE CALCULATOR -->
        <div class="tool-card" onclick="go('/age-calculator')">
            <i class="fas fa-birthday-cake"></i>
            <h3>Age Calculator</h3>
            <p>Calculate age based on birth date</p>
        </div>

        <!-- HASH GENERATOR -->
        <div class="tool-card" onclick="go('/hash-generator')">
            <i class="fas fa-lock"></i>
            <h3>Hash Generator</h3>
            <p>Generate MD5, SHA-1, SHA-256, SHA-512 hashes</p>
        </div>

        <!-- COLOR PALETTE -->
        <div class="tool-card" onclick="go('/color-palette')">
            <i class="fas fa-palette"></i>
            <h3>Color Palette</h3>
            <p>Generate and manage color palettes</p>
        </div>

        <!-- UNIT CONVERTER -->
        <div class="tool-card" onclick="go('/unit-converter')">
            <i class="fas fa-ruler"></i>
            <h3>Unit Converter</h3>
            <p>Convert between different units easily</p>
        </div>

        <!-- MARKDOWN EDITOR -->
        <div class="tool-card" onclick="go('/markdown-editor')">
            <i class="fas fa-markdown"></i>
            <h3>Markdown Editor</h3>
            <p>Write and preview markdown in real-time</p>
        </div>

        <!-- IP TOOLS -->
        <div class="tool-card" onclick="go('/ip-tools')">
            <i class="fas fa-network-wired"></i>
            <h3>IP Tools</h3>
            <p>IP Lookup, Geolocation, and more</p>
        </div>

        <!-- DATE CALCULATOR -->
        <div class="tool-card" onclick="go('/date-calculator')">
            <i class="fas fa-calendar-alt"></i>
            <h3>Date Calculator</h3>
            <p>Calculate date differences and more</p>
        </div>

        <!-- EMAIL VALIDATOR -->
        <div class="tool-card" onclick="go('/email-validator')">
            <i class="fas fa-envelope"></i>
            <h3>Email Validator</h3>
            <p>Validate email addresses and check domains</p>
        </div>

        <!-- FILE CONVERTER -->
        <div class="tool-card" onclick="go('/file-converter')">
            <i class="fas fa-file-converter"></i>
            <h3>File Converter</h3>
            <p>Convert between CSV, JSON, XML, and YAML formats</p>
        </div>

        <!-- URL ENCODER/DECODER -->
        <div class="tool-card" onclick="go('/url-encoder')">
            <i class="fas fa-link"></i>
            <h3>URL Encoder/Decoder</h3>
            <p>Encode and decode URLs easily</p>
        </div>

        <!-- MIND MAP GENERATOR -->
        <div class="tool-card" onclick="go('/mind-map')">
            <i class="fas fa-brain"></i>
            <h3>Mind Map Generator</h3>
            <p>Create visual mind maps from your ideas</p>
        </div>

        <!-- PDF TOOLKIT -->
        <div class="tool-card" onclick="go('/pdf-toolkit')">
            <i class="fas fa-file-pdf"></i>
            <h3>PDF Toolkit</h3>
            <p>Merge, split, compress, and convert PDF files</p>
        </div>

        <!-- Memory Game -->
        <div class="tool-card" onclick="go('/memory-game')">
            <i class="fas fa-brain"></i>
            <h3>Memory Game</h3>
            <p>Test your memory with this fun card matching game</p>
        </div>

        <!-- Tractor Game -->
        <div class="tool-card" onclick="go('/tractor-game')">
            <i class="fas fa-tractor"></i>
            <h3>Tractor Farming</h3>
            <p>Drive your tractor and plow the fields! 🌾</p>
        </div>
        <!-- Zip Zap Game -->
        <div class="tool-card" onclick="go('/zipzap-game')">
            <i class="fas fa-bolt"></i>
            <h3>Zip Zap</h3>
            <p>Complete the pattern! Think fast, tap smart ⚡</p>
        </div>

        <!-- Highway Racer -->
        <div class="tool-card" onclick="go('/highway-racer')">
            <i class="fas fa-car"></i>
            <h3>Highway Racer</h3>
            <p>Dodge traffic and survive! 🏎️</p>
        </div>
        <!-- Speed Test -->
        <!-- <div class="tool-card" onclick="go('/speed-test')">
            <i class="fas fa-wifi"></i>
            <h3>Speed Test</h3>
            <p>Check your internet speed in real-time 🌐</p>
        </div> -->

        <!-- Speed Checker -->
        <!-- <div class="tool-card" onclick="go('/speed-checker')">
            <i class="fas fa-wifi"></i>
            <h3>Speed Checker</h3>
            <p>Professional internet speed test 🌐</p>
        </div> -->

        <!-- Bike Racer -->
        <div class="tool-card" onclick="go('/bike-racer')">
            <i class="fas fa-bicycle"></i>
            <h3>Bike Racer</h3>
            <p>Race against the clock on your bike! 🚴</p>
        </div>

        <!-- Arrow Maze -->
<div class="tool-card" onclick="go('/arrow-maze')">
    <i class="fas fa-arrow-right"></i>
    <h3>Arrow Maze</h3>
    <p>Follow the arrows to solve the puzzle 🎯</p>
</div>

    </div>

</div>

@endsection