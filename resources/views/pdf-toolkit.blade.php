@extends('layouts.app')

@section('content')
<div class="pdf-wrapper">
    <div class="pdf-container">
        <div class="pdf-header">
            <h1>📄 PDF Toolkit</h1>
            <p class="subtitle">Merge, split, compress, convert, protect, and edit PDF files</p>
        </div>

        <div class="pdf-tabs">
            <button class="tab-btn active" data-tab="merge" onclick="switchTab('merge')">
                <i class="fas fa-object-ungroup"></i> Merge
            </button>
            <button class="tab-btn" data-tab="split" onclick="switchTab('split')">
                <i class="fas fa-cut"></i> Split
            </button>
            <button class="tab-btn" data-tab="compress" onclick="switchTab('compress')">
                <i class="fas fa-compress-alt"></i> Compress
            </button>
            <button class="tab-btn" data-tab="convert" onclick="switchTab('convert')">
                <i class="fas fa-exchange-alt"></i> Convert
            </button>
            <button class="tab-btn" data-tab="pdf2word" onclick="switchTab('pdf2word')">
                <i class="fas fa-file-word"></i> PDF &rarr; Word
            </button>
            <button class="tab-btn" data-tab="word2pdf" onclick="switchTab('word2pdf')">
                <i class="fas fa-file-pdf"></i> Word &rarr; PDF
            </button>
            <button class="tab-btn" data-tab="protect" onclick="switchTab('protect')">
                <i class="fas fa-lock"></i> Protect
            </button>
            <button class="tab-btn" data-tab="edit" onclick="switchTab('edit')">
                <i class="fas fa-edit"></i> Edit
            </button>
        </div>

        <!-- Merge Tab -->
        <div class="tab-content active" id="merge-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label>📂 Upload PDF Files to Merge</label>
                    <div class="file-drop-zone" id="mergeDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop PDF files here or click to browse</p>
                        <input type="file" id="mergeInput" accept=".pdf" multiple onchange="handleMergeFiles(event)">
                    </div>
                    <div id="mergeFileList" class="file-list"></div>
                    <div class="button-group">
                        <button onclick="mergePDF()" class="btn-primary">🔗 Merge PDFs</button>
                        <button onclick="clearMerge()" class="btn-clear">🗑️ Clear All</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 Merged PDF</label>
                    <div id="mergePreview" class="preview-placeholder">
                        <i class="fas fa-file-pdf"></i>
                        <p>Upload PDFs to merge</p>
                    </div>
                    <div class="button-group">
                        <button onclick="downloadMergedPDF()" class="btn-download">💾 Download Merged PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Split Tab -->
        <div class="tab-content" id="split-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label>📂 Upload PDF to Split</label>
                    <div class="file-drop-zone" id="splitDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop a PDF file here or click to browse</p>
                        <input type="file" id="splitInput" accept=".pdf" onchange="handleSplitFile(event)">
                    </div>
                    <div class="split-options">
                        <label>Split by:</label>
                        <div class="split-radio">
                            <label><input type="radio" name="splitMode" value="pages" checked onchange="toggleSplitMode()"> All Pages</label>
                            <label><input type="radio" name="splitMode" value="ranges" onchange="toggleSplitMode()"> Page Ranges</label>
                        </div>
                        <div id="pageRangeInput" style="display:none; margin-top:10px;">
                            <input type="text" id="pageRanges" placeholder="e.g., 1-3, 5, 7-9">
                            <small>Enter page ranges separated by commas</small>
                        </div>
                    </div>
                    <div class="button-group">
                        <button onclick="splitPDF()" class="btn-primary">✂️ Split PDF</button>
                        <button onclick="clearSplit()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 Split PDFs</label>
                    <div id="splitPreview" class="preview-placeholder">
                        <i class="fas fa-file-pdf"></i>
                        <p>Upload a PDF to split</p>
                    </div>
                    <div id="splitResults" style="display:none;">
                        <div id="splitFileList" class="file-list"></div>
                        <button onclick="downloadSplitPDFs()" class="btn-download">💾 Download All</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Compress Tab -->
        <div class="tab-content" id="compress-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label>📂 Upload PDF to Compress</label>
                    <div class="file-drop-zone" id="compressDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop a PDF file here or click to browse</p>
                        <input type="file" id="compressInput" accept=".pdf" onchange="handleCompressFile(event)">
                    </div>
                    <div class="compress-options">
                        <label>Compression Level:</label>
                        <select id="compressLevel">
                            <option value="low">Low (Fast)</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High (Smaller Size)</option>
                        </select>
                    </div>
                    <div class="button-group">
                        <button onclick="compressPDF()" class="btn-primary">📦 Compress PDF</button>
                        <button onclick="clearCompress()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 Compressed PDF</label>
                    <div id="compressPreview" class="preview-placeholder">
                        <i class="fas fa-file-pdf"></i>
                        <p>Upload a PDF to compress</p>
                    </div>
                    <div id="compressInfo" style="display:none;">
                        <div class="compress-stats">
                            <span>Original: <strong id="originalSize">0 KB</strong></span>
                            <span>Compressed: <strong id="compressedSize">0 KB</strong></span>
                            <span>Saved: <strong id="savedSize">0%</strong></span>
                        </div>
                        <button onclick="downloadCompressedPDF()" class="btn-download">💾 Download Compressed PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Convert Tab -->
        <div class="tab-content" id="convert-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label>📂 Upload PDF to Convert</label>
                    <div class="file-drop-zone" id="convertDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop a PDF file here or click to browse</p>
                        <input type="file" id="convertInput" accept=".pdf" onchange="handleConvertFile(event)">
                    </div>
                    <div class="convert-options">
                        <label>Convert to:</label>
                        <select id="convertFormat">
                            <option value="jpg">JPG Images</option>
                            <option value="png">PNG Images</option>
                            <option value="text">Text (Extract)</option>
                        </select>
                    </div>
                    <div class="button-group">
                        <button onclick="convertPDF()" class="btn-primary">🔄 Convert</button>
                        <button onclick="clearConvert()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 Converted Output</label>
                    <div id="convertPreview" class="preview-placeholder">
                        <i class="fas fa-file-pdf"></i>
                        <p>Upload a PDF to convert</p>
                    </div>
                    <div id="convertResults" style="display:none;">
                        <div id="convertFileList" class="file-list"></div>
                        <button onclick="downloadConvertedFiles()" class="btn-download">💾 Download All</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF to Word Tab -->
        <div class="tab-content" id="pdf2word-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label for="pdf2wordInput">📂 Upload a PDF to convert to Word</label>
                    <div class="file-drop-zone" id="pdf2wordDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag &amp; drop a PDF here or click to browse</p>
                        <input type="file" id="pdf2wordInput" accept="application/pdf,.pdf" onchange="handlePdf2WordFile(event)">
                    </div>
                    <div id="pdf2wordFileList" class="file-list"></div>
                    <div class="button-group">
                        <button type="button" onclick="convertPdfToWord()" class="btn-primary">🔄 Convert to Word</button>
                        <button type="button" onclick="clearPdf2Word()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 Word document</label>
                    <div id="pdf2wordPreview" class="preview-placeholder" role="status" aria-live="polite">
                        <i class="fas fa-file-word"></i>
                        <p>Upload a PDF to convert</p>
                    </div>
                    <div class="button-group">
                        <button type="button" onclick="downloadWordFile()" class="btn-download">💾 Download .docx</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Word to PDF Tab -->
        <div class="tab-content" id="word2pdf-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label for="word2pdfInput">📂 Upload a Word document (.docx)</label>
                    <div class="file-drop-zone" id="word2pdfDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag &amp; drop a .docx here or click to browse</p>
                        <input type="file" id="word2pdfInput"
                               accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                               onchange="handleWord2PdfFile(event)">
                    </div>
                    <div id="word2pdfFileList" class="file-list"></div>
                    <div class="button-group">
                        <button type="button" onclick="convertWordToPdf()" class="btn-primary">🔄 Convert to PDF</button>
                        <button type="button" onclick="clearWord2Pdf()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 PDF document</label>
                    <div id="word2pdfPreview" class="preview-placeholder" role="status" aria-live="polite">
                        <i class="fas fa-file-pdf"></i>
                        <p>Upload a .docx to convert</p>
                    </div>
                    <div class="button-group">
                        <button type="button" onclick="downloadConvertedPdf()" class="btn-download">💾 Download PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Protect Tab -->
        <div class="tab-content" id="protect-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label>📂 Upload PDF to Protect</label>
                    <div class="file-drop-zone" id="protectDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop a PDF file here or click to browse</p>
                        <input type="file" id="protectInput" accept=".pdf" onchange="handleProtectFile(event)">
                    </div>
                    <div class="protect-options">
                        <div class="password-group">
                            <label>Set Password</label>
                            <input type="password" id="pdfPassword" placeholder="Enter password (min 4 chars)">
                        </div>
                        <div class="permissions">
                            <label>Permissions:</label>
                            <label><input type="checkbox" id="permitPrint" checked> Printing</label>
                            <label><input type="checkbox" id="permitCopy" checked> Copying</label>
                            <label><input type="checkbox" id="permitModify" checked> Modifying</label>
                        </div>
                    </div>
                    <div class="button-group">
                        <button onclick="protectPDF()" class="btn-primary">🔒 Protect PDF</button>
                        <button onclick="clearProtect()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 Protected PDF</label>
                    <div id="protectPreview" class="preview-placeholder">
                        <i class="fas fa-file-pdf"></i>
                        <p>Upload a PDF to protect</p>
                    </div>
                    <div class="button-group">
                        <button onclick="downloadProtectedPDF()" class="btn-download">💾 Download Protected PDF</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Tab -->
        <div class="tab-content" id="edit-tab">
            <div class="pdf-grid">
                <div class="input-section">
                    <label>📂 Upload PDF to Edit</label>
                    <div class="file-drop-zone" id="editDropZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop a PDF file here or click to browse</p>
                        <input type="file" id="editInput" accept=".pdf" onchange="handleEditFile(event)">
                    </div>
                    <div id="editFileInfo" style="display:none;">
                        <div class="file-item">
                            <span class="file-name"><i class="fas fa-file-pdf"></i> <span id="editFileName"></span></span>
                            <span class="file-size" id="editFileSize"></span>
                        </div>
                    </div>
                    <div class="edit-options">
                        <label>Edit Options:</label>
                        <div class="edit-checkboxes">
                            <label><input type="checkbox" id="editRotate" checked> Rotate Pages</label>
                            <label><input type="checkbox" id="editRemovePages"> Remove Pages</label>
                            <label><input type="checkbox" id="editAddWatermark"> Add Watermark</label>
                            <label><input type="checkbox" id="editCompress"> Compress</label>
                        </div>
                        <div id="editPageRange" style="margin-top:10px;">
                            <input type="text" id="editPages" placeholder="Page numbers to keep (e.g., 1,3,5 or 1-5)">
                            <small>Leave empty for all pages</small>
                        </div>
                        <div id="editWatermarkText" style="display:none; margin-top:10px;">
                            <input type="text" id="watermarkText" placeholder="Enter watermark text">
                        </div>
                    </div>
                    <div class="button-group">
                        <button onclick="editPDF()" class="btn-primary">✏️ Edit PDF</button>
                        <button onclick="clearEdit()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>📥 Edited PDF</label>
                    <div id="editPreview" class="preview-placeholder">
                        <i class="fas fa-file-pdf"></i>
                        <p>Upload a PDF to edit</p>
                    </div>
                    <div class="button-group">
                        <button onclick="downloadEditedPDF()" class="btn-download">💾 Download Edited PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pdf-toolkit.css') }}">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
<!-- PDF text extraction (PDF -> Word) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<!-- DOCX generation (PDF -> Word) -->
<script src="https://cdn.jsdelivr.net/npm/docx@8.5.0/build/index.umd.min.js"></script>
<script src="{{ asset('js/pdf-toolkit.js') }}"></script>
@endpush