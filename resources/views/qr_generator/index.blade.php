@extends('layouts.app')

@section('content')


@include('partials.tool-header')
<div class="qr-wrapper">
    <div class="qr-container">
        <h2>Advanced QR Code Generator</h2>

        <div class="form-group">
            <label>Enter Text / URL</label>
            <textarea id="qr_text" rows="6" placeholder="Enter any text, URL, phone, email, or anything..."></textarea>
            <div class="error" id="textError"></div>
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label>QR Size</label>
                <select id="qr_size">
                    <option value="150">150 x 150</option>
                    <option value="200">200 x 200</option>
                    <option value="250" selected>250 x 250</option>
                    <option value="300">300 x 300</option>
                    <option value="400">400 x 400</option>
                    <option value="500">500 x 500</option>
                </select>
            </div>

            <div class="form-group half">
                <label>Margin</label>
                <select id="qr_margin">
                    <option value="0">0</option>
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group half">
                <label>QR Color</label>
                <input type="color" id="qr_color" value="#000000">
            </div>

            <div class="form-group half">
                <label>Background Color</label>
                <input type="color" id="bg_color" value="#ffffff">
            </div>
        </div>

        <div class="form-group">
            <label>Upload Logo (optional)</label>
            <input type="file" id="qr_logo" accept="image/*">
            <small class="help-text">PNG/JPG logo will be show in qr code center.</small>
        </div>

        <div class="btn-group">
            <button type="button" class="generate-btn" id="generateQrBtn">Generate QR</button>
        </div>

        <div class="result-box hidden" id="qrResultBox">
            <h2>Generated QR Code</h2>

            <div class="qr-preview" id="qrPreviewWrap">
                <div id="qrPreview"></div>
            </div>
        <div class="btn-group">
            <button type="button" class="copy-btn" id="copyQrTextBtn">Copy Text</button>
            <button type="button" class="download-btn" id="downloadSvgBtn">Download SVG</button>
            <button type="button" class="png-btn" id="downloadPngBtn">Download PNG</button>
            <button type="button" class="clear-btn" id="clearQrForm">Clear</button>
        </div>
            <div class="preview-text">
                <strong>Encoded Text:</strong>
                <p id="encodedText"></p>
            </div>

            <div id="copyMessage" class="copy-message"></div>
        </div>
    </div>
</div>

@include('partials.tool-content')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/qr.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.9.2/lib/qr-code-styling.js"></script>
<script src="{{ asset('js/qr.js') }}"></script>
@endpush
