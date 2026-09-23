@extends('layouts.app')

@section('content')


@include('partials.tool-header')
<div class="ocr-container">

    <div class="ocr-card">

        <h2>📷 Image To Text Extractor</h2>

        <div id="dropZone" class="drop-zone">

            <div class="drop-content">
                <i class="fas fa-cloud-upload-alt"></i>
                <h3>Drag & Drop Image Here</h3>
                <p>or Click to Browse</p>
            </div>

            <input
                type="file"
                id="imageFile"
                accept="image/*"
                hidden>

        </div>

        <div class="preview-box">

            <img
                id="preview"
                alt=""
                src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                style="display:none;">

        </div>

        <div class="ocr-buttons">

            <button onclick="extractText()">
                Extract Text
            </button>

            <button onclick="copyText()">
                Copy
            </button>

            <button onclick="downloadText()">
                Download
            </button>

        </div>

        <textarea
            id="outputText"
            placeholder="Extracted text will appear here..."
        ></textarea>

    </div>

</div>

@include('partials.tool-content')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/image-to-text.css') }}">
@endpush

@push('scripts')
<script src="https://unpkg.com/tesseract.js@5/dist/tesseract.min.js"></script>
<script src="{{ asset('js/image-to-text.js') }}"></script>
@endpush
