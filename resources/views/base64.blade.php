@extends('layouts.app')

@section('content')
<div class="base64-wrapper">
    <div class="base64-container">
        <div class="base64-header">
            <h1>🔐 Base64 Encoder / Decoder</h1>
            <p class="subtitle">Encode or decode text, files, and images to Base64 format</p>
        </div>

        <div class="base64-tabs">
            <button class="tab-btn active" data-tab="text" onclick="switchTab('text')">
                <i class="fas fa-font"></i> Text
            </button>
            <button class="tab-btn" data-tab="file" onclick="switchTab('file')">
                <i class="fas fa-file"></i> File
            </button>
            <button class="tab-btn" data-tab="image" onclick="switchTab('image')">
                <i class="fas fa-image"></i> Image
            </button>
        </div>

        <!-- Text Tab -->
        <div class="tab-content active" id="text-tab">
            <div class="base64-grid">
                <div class="input-section">
                    <label>Input Text</label>
                    <textarea id="textInput" placeholder="Enter text to encode or decode..."></textarea>
                    <div class="button-group">
                        <button onclick="encodeText()" class="btn-encode">🔒 Encode</button>
                        <button onclick="decodeText()" class="btn-decode">🔓 Decode</button>
                        <button onclick="clearText()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>Output</label>
                    <textarea id="textOutput" readonly placeholder="Result will appear here..."></textarea>
                    <div class="button-group">
                        <button onclick="copyOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadText()" class="btn-download">💾 Download</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Tab -->
        <div class="tab-content" id="file-tab">
            <div class="base64-grid">
                <div class="input-section">
                    <label>Upload File</label>
                    <div class="file-drop-zone" id="fileDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop file here or click to browse</p>
                        <input type="file" id="fileInput" onchange="handleFileSelect(event)">
                    </div>
                    <div id="fileInfo" style="display:none;">
                        <p><strong>File:</strong> <span id="fileName"></span></p>
                        <p><strong>Size:</strong> <span id="fileSize"></span></p>
                        <p><strong>Type:</strong> <span id="fileType"></span></p>
                    </div>
                    <div class="button-group">
                        <button onclick="encodeFile()" class="btn-encode">🔒 Encode to Base64</button>
                        <button onclick="clearFile()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>Base64 Output</label>
                    <textarea id="fileOutput" readonly placeholder="Base64 encoded result..."></textarea>
                    <div class="button-group">
                        <button onclick="copyFileOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadFileOutput()" class="btn-download">💾 Download</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Tab -->
        <div class="tab-content" id="image-tab">
            <div class="base64-grid">
                <div class="input-section">
                    <label>Upload Image</label>
                    <div class="file-drop-zone" id="imageDropZone">
                        <i class="fas fa-image"></i>
                        <p>Drag & drop image here or click to browse</p>
                        <input type="file" id="imageInput" accept="image/*" onchange="handleImageSelect(event)">
                    </div>
                    <div id="imagePreview" style="display:none;">
                        <img id="previewImage" alt="Preview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                    </div>
                    <div class="button-group">
                        <button onclick="encodeImage()" class="btn-encode">🔒 Encode to Base64</button>
                        <button onclick="clearImage()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>Base64 Output</label>
                    <textarea id="imageOutput" readonly placeholder="Base64 encoded image..."></textarea>
                    <div class="button-group">
                        <button onclick="copyImageOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadImageOutput()" class="btn-download">💾 Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

