@extends('layouts.app')

@section('content')
<div class="hash-wrapper">
    <div class="hash-container">
        <div class="hash-header">
            <h1>🔐 Hash Generator & Decoder</h1>
            <p class="subtitle">Generate MD5, SHA-1, SHA-256, SHA-512 hashes and decode them back to original values</p>
        </div>

        <div class="hash-tabs">
            <button class="tab-btn active" data-tab="text" onclick="switchTab('text')">
                <i class="fas fa-font"></i> Generate
            </button>
            <button class="tab-btn" data-tab="decode" onclick="switchTab('decode')">
                <i class="fas fa-unlock"></i> Decode
            </button>
            <button class="tab-btn" data-tab="file" onclick="switchTab('file')">
                <i class="fas fa-file"></i> File
            </button>
            <button class="tab-btn" data-tab="compare" onclick="switchTab('compare')">
                <i class="fas fa-balance-scale"></i> Compare
            </button>
        </div>

        <!-- Generate Tab -->
        <div class="tab-content active" id="text-tab">
            <div class="hash-grid">
                <div class="input-section">
                    <label>Input Text</label>
                    <textarea id="hashTextInput" placeholder="Enter text to hash..."></textarea>
                    
                    <div class="hash-options">
                        <label>Hash Algorithms:</label>
                        <div class="checkbox-group">
                            <label><input type="checkbox" class="hash-algo" value="md5" checked> MD5</label>
                            <label><input type="checkbox" class="hash-algo" value="sha1" checked> SHA-1</label>
                            <label><input type="checkbox" class="hash-algo" value="sha256" checked> SHA-256</label>
                            <label><input type="checkbox" class="hash-algo" value="sha512" checked> SHA-512</label>
                        </div>
                    </div>

                    <div class="button-group">
                        <button onclick="generateHash()" class="btn-generate">🔒 Generate Hash</button>
                        <button onclick="clearTextHash()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>

                <div class="output-section">
                    <label>Hash Results</label>
                    <div id="hashResults">
                        <div class="hash-result-item">
                            <span class="hash-algo-label">MD5</span>
                            <input type="text" id="md5Result" readonly placeholder="MD5 hash will appear here...">
                            <button onclick="copyHash('md5Result')" class="btn-copy-small">📋</button>
                            <button onclick="decodeHash('md5Result')" class="btn-decode-small">🔓</button>
                        </div>
                        <div class="hash-result-item">
                            <span class="hash-algo-label">SHA-1</span>
                            <input type="text" id="sha1Result" readonly placeholder="SHA-1 hash will appear here...">
                            <button onclick="copyHash('sha1Result')" class="btn-copy-small">📋</button>
                            <button onclick="decodeHash('sha1Result')" class="btn-decode-small">🔓</button>
                        </div>
                        <div class="hash-result-item">
                            <span class="hash-algo-label">SHA-256</span>
                            <input type="text" id="sha256Result" readonly placeholder="SHA-256 hash will appear here...">
                            <button onclick="copyHash('sha256Result')" class="btn-copy-small">📋</button>
                            <button onclick="decodeHash('sha256Result')" class="btn-decode-small">🔓</button>
                        </div>
                        <div class="hash-result-item">
                            <span class="hash-algo-label">SHA-512</span>
                            <input type="text" id="sha512Result" readonly placeholder="SHA-512 hash will appear here...">
                            <button onclick="copyHash('sha512Result')" class="btn-copy-small">📋</button>
                            <button onclick="decodeHash('sha512Result')" class="btn-decode-small">🔓</button>
                        </div>
                    </div>
                    <button onclick="downloadAllHashes()" class="btn-download-hashes">💾 Download All Hashes</button>
                </div>
            </div>
        </div>

        <!-- Decode Tab -->
        <div class="tab-content" id="decode-tab">
            <div class="hash-grid">
                <div class="input-section">
                    <label>Enter Hash to Decode</label>
                    <textarea id="decodeInput" placeholder="Enter hash to decode (MD5, SHA-1, SHA-256, SHA-512)..."></textarea>
                    
                    <div class="hash-options">
                        <label>Hash Type (if known):</label>
                        <div class="checkbox-group">
                            <label><input type="radio" name="decodeType" value="auto" checked> Auto Detect</label>
                            <label><input type="radio" name="decodeType" value="md5"> MD5</label>
                            <label><input type="radio" name="decodeType" value="sha1"> SHA-1</label>
                            <label><input type="radio" name="decodeType" value="sha256"> SHA-256</label>
                            <label><input type="radio" name="decodeType" value="sha512"> SHA-512</label>
                        </div>
                    </div>

                    <div class="button-group">
                        <button onclick="decodeHashInput()" class="btn-decode">🔓 Decode Hash</button>
                        <button onclick="clearDecode()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>

                <div class="output-section">
                    <label>Decoded Results</label>
                    <div id="decodeResults">
                        <div class="decode-result-item">
                            <span class="decode-label">Status:</span>
                            <span class="decode-value" id="decodeStatus">Waiting for input...</span>
                        </div>
                        <div class="decode-result-item">
                            <span class="decode-label">Original Value:</span>
                            <span class="decode-value" id="decodeOriginal">-</span>
                        </div>
                        <div class="decode-result-item">
                            <span class="decode-label">Hash Type:</span>
                            <span class="decode-value" id="decodeTypeResult">-</span>
                        </div>
                        <div class="decode-result-item">
                            <span class="decode-label">Hash Length:</span>
                            <span class="decode-value" id="decodeLength">-</span>
                        </div>
                        <div class="decode-result-item">
                            <span class="decode-label">Confidence:</span>
                            <span class="decode-value" id="decodeConfidence">-</span>
                        </div>
                    </div>
                    <div id="decodeOriginalValue" style="display:none; margin-top: 15px; padding: 15px; background: #d4edda; border-radius: 8px; border: 1px solid #c3e6cb;">
                        <strong>✅ Original Value:</strong>
                        <div style="font-size: 18px; margin-top: 10px; word-break: break-all;" id="decodeOriginalText"></div>
                        <button onclick="copyDecodedValue()" class="btn-copy" style="margin-top: 10px;">📋 Copy Value</button>
                    </div>
                    <div id="decodeNotFound" style="display:none; margin-top: 15px; padding: 15px; background: #f8d7da; border-radius: 8px; border: 1px solid #f5c6cb;">
                        <strong>❌ Not Found:</strong>
                        <div style="margin-top: 10px;" id="decodeNotFoundText">Could not find original value for this hash. Try a different hash or check if it's correct.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File Tab -->
        <div class="tab-content" id="file-tab">
            <div class="hash-grid">
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
                        <button onclick="generateFileHash()" class="btn-generate">🔒 Generate File Hash</button>
                        <button onclick="clearFileHash()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>

                <div class="output-section">
                    <label>File Hash Results</label>
                    <div id="fileHashResults">
                        <div class="hash-result-item">
                            <span class="hash-algo-label">MD5</span>
                            <input type="text" id="fileMd5Result" readonly placeholder="MD5 hash will appear here...">
                            <button onclick="copyHash('fileMd5Result')" class="btn-copy-small">📋</button>
                        </div>
                        <div class="hash-result-item">
                            <span class="hash-algo-label">SHA-1</span>
                            <input type="text" id="fileSha1Result" readonly placeholder="SHA-1 hash will appear here...">
                            <button onclick="copyHash('fileSha1Result')" class="btn-copy-small">📋</button>
                        </div>
                        <div class="hash-result-item">
                            <span class="hash-algo-label">SHA-256</span>
                            <input type="text" id="fileSha256Result" readonly placeholder="SHA-256 hash will appear here...">
                            <button onclick="copyHash('fileSha256Result')" class="btn-copy-small">📋</button>
                        </div>
                        <div class="hash-result-item">
                            <span class="hash-algo-label">SHA-512</span>
                            <input type="text" id="fileSha512Result" readonly placeholder="SHA-512 hash will appear here...">
                            <button onclick="copyHash('fileSha512Result')" class="btn-copy-small">📋</button>
                        </div>
                    </div>
                    <button onclick="downloadFileHashes()" class="btn-download-hashes">💾 Download File Hashes</button>
                </div>
            </div>
        </div>

        <!-- Compare Tab -->
        <div class="tab-content" id="compare-tab">
            <div class="hash-grid">
                <div class="input-section">
                    <label>Hash 1</label>
                    <textarea id="hash1Input" placeholder="Enter first hash to compare..."></textarea>
                    
                    <label style="margin-top: 15px;">Hash 2</label>
                    <textarea id="hash2Input" placeholder="Enter second hash to compare..."></textarea>
                    
                    <div class="button-group">
                        <button onclick="compareHashes()" class="btn-compare">⚖️ Compare Hashes</button>
                        <button onclick="clearCompare()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>

                <div class="output-section">
                    <label>Comparison Result</label>
                    <div id="compareResult" class="compare-result">
                        <div class="compare-placeholder">
                            <i class="fas fa-balance-scale"></i>
                            <p>Enter two hashes to compare</p>
                        </div>
                    </div>
                    <div id="compareDetails" style="display:none;">
                        <div class="compare-detail-item">
                            <span class="compare-label">Hash 1:</span>
                            <span class="compare-value" id="compareHash1"></span>
                        </div>
                        <div class="compare-detail-item">
                            <span class="compare-label">Hash 2:</span>
                            <span class="compare-value" id="compareHash2"></span>
                        </div>
                        <div class="compare-detail-item">
                            <span class="compare-label">Status:</span>
                            <span class="compare-status" id="compareStatus"></span>
                        </div>
                        <div class="compare-detail-item">
                            <span class="compare-label">Length Match:</span>
                            <span class="compare-value" id="compareLength"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

<!-- @push('styles')
<link rel="stylesheet" href="{{ asset('css/hash-generator.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/hash-generator.js') }}"></script>
@endpush -->