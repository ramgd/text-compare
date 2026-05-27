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

    </div>

</div>

@endsection