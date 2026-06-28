@extends('layouts.app')

@section('content')
<div class="url-wrapper">
    <div class="url-container">
        <div class="url-header">
            <h1>🌐 URL Encoder / Decoder</h1>
            <p class="subtitle">Encode or decode URLs, HTML entities, and Base64 strings</p>
        </div>

        <div class="url-tabs">
            <button class="tab-btn active" data-tab="url" onclick="switchTab('url')">
                <i class="fas fa-link"></i> URL
            </button>
            <button class="tab-btn" data-tab="html" onclick="switchTab('html')">
                <i class="fas fa-code"></i> HTML Entities
            </button>
            <button class="tab-btn" data-tab="base64" onclick="switchTab('base64')">
                <i class="fas fa-lock"></i> Base64
            </button>
        </div>

        <!-- URL Tab -->
        <div class="tab-content active" id="url-tab">
            <div class="url-grid">
                <div class="input-section">
                    <label>URL to Encode / Decode</label>
                    <textarea id="urlInput" placeholder="Enter URL to encode or decode..."></textarea>
                    <div class="button-group">
                        <button onclick="urlEncode()" class="btn-encode">🔒 Encode</button>
                        <button onclick="urlDecode()" class="btn-decode">🔓 Decode</button>
                        <button onclick="clearUrl()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>Result</label>
                    <textarea id="urlOutput" readonly placeholder="Result will appear here..."></textarea>
                    <div class="button-group">
                        <button onclick="copyUrlOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadUrlOutput()" class="btn-download">💾 Download</button>
                    </div>
                </div>
            </div>
            <div class="url-presets">
                <label>Quick Tests:</label>
                <button onclick="setUrlExample('encode')">Encode Example</button>
                <button onclick="setUrlExample('decode')">Decode Example</button>
                <button onclick="setUrlExample('special')">Special Chars</button>
            </div>
        </div>

        <!-- HTML Entities Tab -->
        <div class="tab-content" id="html-tab">
            <div class="url-grid">
                <div class="input-section">
                    <label>HTML to Encode / Decode</label>
                    <textarea id="htmlInput" placeholder="Enter HTML text to encode or decode..."></textarea>
                    <div class="button-group">
                        <button onclick="htmlEncode()" class="btn-encode">🔒 Encode</button>
                        <button onclick="htmlDecode()" class="btn-decode">🔓 Decode</button>
                        <button onclick="clearHtml()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>Result</label>
                    <textarea id="htmlOutput" readonly placeholder="Result will appear here..."></textarea>
                    <div class="button-group">
                        <button onclick="copyHtmlOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadHtmlOutput()" class="btn-download">💾 Download</button>
                    </div>
                </div>
            </div>
            <div class="url-presets">
                <label>Quick Tests:</label>
                <button onclick="setHtmlExample('encode')">Encode Example</button>
                <button onclick="setHtmlExample('decode')">Decode Example</button>
            </div>
        </div>

        <!-- Base64 Tab -->
        <div class="tab-content" id="base64-tab">
            <div class="url-grid">
                <div class="input-section">
                    <label>Text to Encode / Decode</label>
                    <textarea id="base64Input" placeholder="Enter text to encode or decode..."></textarea>
                    <div class="button-group">
                        <button onclick="base64Encode()" class="btn-encode">🔒 Encode</button>
                        <button onclick="base64Decode()" class="btn-decode">🔓 Decode</button>
                        <button onclick="clearBase64()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>Result</label>
                    <textarea id="base64Output" readonly placeholder="Result will appear here..."></textarea>
                    <div class="button-group">
                        <button onclick="copyBase64Output()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadBase64Output()" class="btn-download">💾 Download</button>
                    </div>
                </div>
            </div>
            <div class="url-presets">
                <label>Quick Tests:</label>
                <button onclick="setBase64Example('encode')">Encode Example</button>
                <button onclick="setBase64Example('decode')">Decode Example</button>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/url-encoder.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/url-encoder.js') }}"></script>
@endpush