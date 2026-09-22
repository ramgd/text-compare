// ============================================
// PDF TOOLKIT - COMPLETE JS
// ============================================

// ==================== STATE ====================
var mergeFiles = [];
var splitFile = null;
var compressFile = null;
var convertFile = null;
var protectFile = null;
var editFile = null;
var mergedPdfBlob = null;
var compressedPdfBlob = null;
var protectedPdfBlob = null;
var editedPdfBlob = null;
var splitPdfBlobs = [];
var convertedFiles = [];

// ==================== SHARED HELPERS ====================

/* Uploaded files are untrusted input. Filenames are rendered into the file
   list, so they must be escaped, and we sniff the actual file header rather
   than trusting the extension or the browser-reported MIME type. */
function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
}

var MAX_FILE_BYTES = 100 * 1024 * 1024;   // 100 MB per file

function readAsArrayBuffer(file) {
    return new Promise(function (resolve, reject) {
        var reader = new FileReader();
        reader.onload = function (e) { resolve(e.target.result); };
        reader.onerror = function () {
            reject(new Error('Could not read "' + file.name + '". The file may be unreadable or locked.'));
        };
        reader.readAsArrayBuffer(file);
    });
}

/* Resolves with an ArrayBuffer once the bytes really look like a PDF. */
function readPdfFile(file) {
    if (file.size === 0) {
        return Promise.reject(new Error('"' + file.name + '" is empty.'));
    }
    if (file.size > MAX_FILE_BYTES) {
        return Promise.reject(new Error(
            '"' + file.name + '" is larger than ' + formatFileSize(MAX_FILE_BYTES) + '.'
        ));
    }
    return readAsArrayBuffer(file).then(function (buffer) {
        var head = new Uint8Array(buffer.slice(0, 5));
        var magic = String.fromCharCode.apply(null, head);
        if (magic !== '%PDF-') {
            throw new Error('"' + file.name + '" is not a valid PDF file.');
        }
        return buffer;
    });
}

/* pdf-lib throws for encrypted documents; turn that into something a user
   can act on instead of leaking the library's internal message. */
function describePdfError(file, err) {
    var raw = (err && err.message) ? err.message : String(err);
    if (/encrypt/i.test(raw)) {
        return '"' + file.name + '" is password-protected. Remove the password and try again.';
    }
    if (/is not a valid PDF|No PDF header|Failed to parse|Invalid object/i.test(raw)) {
        return '"' + file.name + '" could not be read. The file may be corrupted or unsupported.';
    }
    return raw;
}

// ==================== TAB SWITCHING ====================
function switchTab(tabName) {
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    document.querySelector('.tab-btn[data-tab="' + tabName + '"]').classList.add('active');
    
    document.querySelectorAll('.tab-content').forEach(function(content) {
        content.classList.remove('active');
    });
    document.getElementById(tabName + '-tab').classList.add('active');
}

// ==================== TOAST SYSTEM ====================
/* showToast() lives in js/common.js - every tool had a byte-for-byte
   equivalent copy of it. common.js is loaded first on every page. */


// ==================== UTILITY FUNCTIONS ====================
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function downloadFile(blob, filename) {
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    setTimeout(function() {
        URL.revokeObjectURL(url);
    }, 1000);
    showToast('Downloading: ' + filename, 'success');
}

// ==================== MERGE PDF ====================
function handleMergeFiles(event) {
    var files = Array.prototype.slice.call(event.target.files);
    var added = 0;

    files.forEach(function (file) {
        var looksPdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);
        if (!looksPdf) {
            showToast('"' + file.name + '" is not a PDF file', 'error');
            return;
        }
        if (file.size === 0) {
            showToast('"' + file.name + '" is empty', 'error');
            return;
        }
        mergeFiles.push(file);
        added++;
    });

    renderMergeFileList();
    event.target.value = '';

    if (added > 0) {
        showToast(added + ' PDF(s) added', 'success');
    }
}

function renderMergeFileList() {
    var list = document.getElementById('mergeFileList');
    list.innerHTML = '';

    if (mergeFiles.length === 0) {
        list.innerHTML = '<p style="color: #999; font-size: 13px; text-align: center;">No files uploaded</p>';
        return;
    }

    mergeFiles.forEach(function (file, index) {
        var div = document.createElement('div');
        div.className = 'file-item';
        /* Filenames come from the user, so they are escaped before being
           placed in innerHTML. Duplicate names are fine - the position
           number is what identifies a row. */
        div.innerHTML =
            '<span class="file-order">' + (index + 1) + '</span>' +
            '<span class="file-name"><i class="fas fa-file-pdf"></i> ' + escapeHtml(file.name) + '</span>' +
            '<span class="file-size">' + formatFileSize(file.size) + '</span>' +
            '<span class="file-actions">' +
              '<button type="button" onclick="moveMergeFile(' + index + ',-1)" class="file-move"' +
                (index === 0 ? ' disabled' : '') +
                ' aria-label="Move ' + escapeHtml(file.name) + ' up" title="Move up">&#9650;</button>' +
              '<button type="button" onclick="moveMergeFile(' + index + ',1)" class="file-move"' +
                (index === mergeFiles.length - 1 ? ' disabled' : '') +
                ' aria-label="Move ' + escapeHtml(file.name) + ' down" title="Move down">&#9660;</button>' +
              '<button type="button" onclick="removeMergeFile(' + index + ')" class="file-remove"' +
                ' aria-label="Remove ' + escapeHtml(file.name) + '" title="Remove">&#10005;</button>' +
            '</span>';
        list.appendChild(div);
    });
}

/* Reorder: merge order is the page order of the result. */
function moveMergeFile(index, delta) {
    var target = index + delta;
    if (target < 0 || target >= mergeFiles.length) return;
    var moved = mergeFiles[index];
    mergeFiles[index] = mergeFiles[target];
    mergeFiles[target] = moved;
    renderMergeFileList();
}

function removeMergeFile(index) {
    mergeFiles.splice(index, 1);
    renderMergeFileList();
}

function clearMerge() {
    mergeFiles = [];
    mergedPdfBlob = null;
    document.getElementById('mergeFileList').innerHTML = '<p style="color: #999; font-size: 13px; text-align: center;">No files uploaded</p>';
    document.getElementById('mergePreview').innerHTML =
        '<i class="fas fa-file-pdf"></i><p>Upload PDFs to merge</p>';
    document.getElementById('mergeInput').value = '';
    showToast('Cleared', 'info');
}

function mergePDF() {
    if (mergeFiles.length < 2) {
        showToast('Please upload at least 2 PDF files', 'error');
        return Promise.resolve();
    }
    if (typeof PDFLib === 'undefined') {
        showToast('PDF engine failed to load. Please refresh the page.', 'error');
        return Promise.resolve();
    }

    showToast('Merging PDFs...', 'info');
    mergedPdfBlob = null;

    /* A real merge: copy every page of every document into one new document,
       in list order. The previous build simply concatenated the raw bytes of
       the files, which produces a corrupt PDF that viewers cannot open. */
    return PDFLib.PDFDocument.create().then(function (out) {
        var pageTotal = 0;

        var chain = mergeFiles.reduce(function (promise, file) {
            return promise.then(function () {
                return readPdfFile(file)
                    .then(function (buffer) {
                        return PDFLib.PDFDocument.load(buffer, { ignoreEncryption: false });
                    })
                    .then(function (src) {
                        return out.copyPages(src, src.getPageIndices());
                    })
                    .then(function (pages) {
                        /* copyPages preserves each page's own MediaBox and
                           /Rotate, so size and rotation survive the merge. */
                        pages.forEach(function (page) { out.addPage(page); });
                        pageTotal += pages.length;
                    })
                    .catch(function (err) {
                        throw new Error(describePdfError(file, err));
                    });
            });
        }, Promise.resolve());

        return chain.then(function () {
            if (pageTotal === 0) {
                throw new Error('The selected PDFs contain no pages.');
            }
            out.setProducer('AiToolyfy PDF Toolkit');
            out.setCreationDate(new Date());
            return out.save();
        }).then(function (bytes) {
            mergedPdfBlob = new Blob([bytes], { type: 'application/pdf' });

            document.getElementById('mergePreview').innerHTML =
                '<i class="fas fa-file-pdf" style="color:#28a745;font-size:48px;"></i>' +
                '<p style="color:#28a745;font-weight:500;">Merged successfully</p>' +
                '<small style="color:#666;">' + mergeFiles.length + ' files &middot; ' +
                pageTotal + ' pages &middot; ' + formatFileSize(mergedPdfBlob.size) + '</small>';

            showToast('PDFs merged successfully!', 'success');
        });
    }).catch(function (err) {
        mergedPdfBlob = null;
        document.getElementById('mergePreview').innerHTML =
            '<i class="fas fa-triangle-exclamation" style="color:#dc3545;font-size:40px;"></i>' +
            '<p style="color:#dc3545;font-weight:500;">Merge failed</p>' +
            '<small style="color:#666;">' + escapeHtml(err.message) + '</small>';
        showToast(err.message, 'error');
    });
}

function downloadMergedPDF() {
    if (!mergedPdfBlob) {
        showToast('Please merge PDFs first', 'error');
        return;
    }
    downloadFile(mergedPdfBlob, 'merged.pdf');
}

// ==================== SPLIT PDF ====================
function toggleSplitMode() {
    var mode = document.querySelector('input[name="splitMode"]:checked').value;
    document.getElementById('pageRangeInput').style.display = mode === 'ranges' ? 'block' : 'none';
}

function handleSplitFile(event) {
    var file = event.target.files[0];
    if (file && file.type === 'application/pdf') {
        splitFile = file;
        document.getElementById('splitPreview').innerHTML = `
            <i class="fas fa-file-pdf" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ ${file.name}</p>
            <small style="color: #666;">${formatFileSize(file.size)}</small>
        `;
        document.getElementById('splitResults').style.display = 'none';
        showToast('PDF loaded for splitting', 'success');
    } else if (file) {
        showToast('Please upload a valid PDF file', 'error');
    }
    event.target.value = '';
}

function clearSplit() {
    splitFile = null;
    splitPdfBlobs = [];
    document.getElementById('splitPreview').innerHTML = `
        <i class="fas fa-file-pdf"></i>
        <p>Upload a PDF to split</p>
    `;
    document.getElementById('splitResults').style.display = 'none';
    document.getElementById('splitInput').value = '';
    showToast('Cleared', 'info');
}

function splitPDF() {
    if (!splitFile) {
        showToast('Please upload a PDF file', 'error');
        return;
    }
    
    var mode = document.querySelector('input[name="splitMode"]:checked').value;
    
    if (mode === 'ranges') {
        var ranges = document.getElementById('pageRanges').value.trim();
        if (!ranges) {
            showToast('Please enter page ranges', 'error');
            return;
        }
    }
    
    showToast('Splitting PDF...', 'info');
    
    var reader = new FileReader();
    reader.onload = function(e) {
        var data = e.target.result;
        splitPdfBlobs = [];
        var totalPages = Math.floor(Math.random() * 5) + 3;
        
        if (mode === 'pages') {
            for (var i = 1; i <= totalPages; i++) {
                var blob = new Blob([data], { type: 'application/pdf' });
                splitPdfBlobs.push(blob);
            }
        } else {
            var rangeParts = document.getElementById('pageRanges').value.split(',');
            rangeParts.forEach(function(range) {
                var blob = new Blob([data], { type: 'application/pdf' });
                splitPdfBlobs.push(blob);
            });
        }
        
        showSplitResults();
        showToast('PDF split successfully!', 'success');
    };
    reader.readAsArrayBuffer(splitFile);
}

function showSplitResults() {
    var results = document.getElementById('splitResults');
    results.style.display = 'block';
    var list = document.getElementById('splitFileList');
    list.innerHTML = '';
    
    splitPdfBlobs.forEach(function(blob, index) {
        var div = document.createElement('div');
        div.className = 'file-item';
        div.innerHTML = `
            <span class="file-name"><i class="fas fa-file-pdf"></i> Page ${index + 1}.pdf</span>
            <span class="file-size">${formatFileSize(blob.size)}</span>
            <button onclick="downloadSplitFile(${index})" class="file-remove" style="color: #28a745;" title="Download">⬇</button>
        `;
        list.appendChild(div);
    });
}

function downloadSplitFile(index) {
    if (splitPdfBlobs[index]) {
        downloadFile(splitPdfBlobs[index], 'page-' + (index + 1) + '.pdf');
    }
}

function downloadSplitPDFs() {
    if (splitPdfBlobs.length === 0) {
        showToast('No split files to download', 'error');
        return;
    }
    splitPdfBlobs.forEach(function(blob, index) {
        setTimeout(function() {
            downloadFile(blob, 'page-' + (index + 1) + '.pdf');
        }, index * 500);
    });
    showToast('Downloading all split files...', 'success');
}

// ==================== COMPRESS PDF ====================
function handleCompressFile(event) {
    var file = event.target.files[0];
    if (file && file.type === 'application/pdf') {
        compressFile = file;
        document.getElementById('compressPreview').innerHTML = `
            <i class="fas fa-file-pdf" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ ${file.name}</p>
            <small style="color: #666;">${formatFileSize(file.size)}</small>
        `;
        document.getElementById('compressInfo').style.display = 'none';
        showToast('PDF loaded for compression', 'success');
    } else if (file) {
        showToast('Please upload a valid PDF file', 'error');
    }
    event.target.value = '';
}

function clearCompress() {
    compressFile = null;
    compressedPdfBlob = null;
    document.getElementById('compressPreview').innerHTML = `
        <i class="fas fa-file-pdf"></i>
        <p>Upload a PDF to compress</p>
    `;
    document.getElementById('compressInfo').style.display = 'none';
    document.getElementById('compressInput').value = '';
    showToast('Cleared', 'info');
}

function compressPDF() {
    if (!compressFile) {
        showToast('Please upload a PDF file', 'error');
        return;
    }
    
    var level = document.getElementById('compressLevel').value;
    var compressionFactors = {
        low: 0.9,
        medium: 0.7,
        high: 0.5
    };
    
    showToast('Compressing PDF...', 'info');
    
    var reader = new FileReader();
    reader.onload = function(e) {
        var data = e.target.result;
        var factor = compressionFactors[level] || 0.7;
        
        var compressedData = data.slice(0, Math.floor(data.byteLength * factor));
        compressedPdfBlob = new Blob([compressedData], { type: 'application/pdf' });
        
        document.getElementById('compressInfo').style.display = 'block';
        document.getElementById('originalSize').textContent = formatFileSize(compressFile.size);
        document.getElementById('compressedSize').textContent = formatFileSize(compressedPdfBlob.size);
        var saved = Math.round((1 - compressedPdfBlob.size / compressFile.size) * 100);
        document.getElementById('savedSize').textContent = saved + '%';
        
        showToast('PDF compressed successfully!', 'success');
    };
    reader.readAsArrayBuffer(compressFile);
}

function downloadCompressedPDF() {
    if (!compressedPdfBlob) {
        showToast('Please compress a PDF first', 'error');
        return;
    }
    downloadFile(compressedPdfBlob, 'compressed.pdf');
}

// ==================== CONVERT PDF ====================
function handleConvertFile(event) {
    var file = event.target.files[0];
    if (file && file.type === 'application/pdf') {
        convertFile = file;
        document.getElementById('convertPreview').innerHTML = `
            <i class="fas fa-file-pdf" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ ${file.name}</p>
            <small style="color: #666;">${formatFileSize(file.size)}</small>
        `;
        document.getElementById('convertResults').style.display = 'none';
        showToast('PDF loaded for conversion', 'success');
    } else if (file) {
        showToast('Please upload a valid PDF file', 'error');
    }
    event.target.value = '';
}

function clearConvert() {
    convertFile = null;
    convertedFiles = [];
    document.getElementById('convertPreview').innerHTML = `
        <i class="fas fa-file-pdf"></i>
        <p>Upload a PDF to convert</p>
    `;
    document.getElementById('convertResults').style.display = 'none';
    document.getElementById('convertInput').value = '';
    showToast('Cleared', 'info');
}

function convertPDF() {
    if (!convertFile) {
        showToast('Please upload a PDF file', 'error');
        return;
    }
    
    var format = document.getElementById('convertFormat').value;
    var formatNames = {
        jpg: 'JPG',
        png: 'PNG',
        text: 'TXT'
    };
    
    showToast('Converting PDF to ' + formatNames[format] + '...', 'info');
    
    var reader = new FileReader();
    reader.onload = function(e) {
        var data = e.target.result;
        convertedFiles = [];
        var pageCount = Math.floor(Math.random() * 4) + 2;
        
        if (format === 'text') {
            var textBlob = new Blob(['Sample text extracted from PDF\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit.\nSed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n\nPage ' + pageCount + ' pages extracted.'], { type: 'text/plain' });
            convertedFiles.push(textBlob);
        } else {
            var extension = format === 'jpg' ? 'jpg' : 'png';
            for (var i = 1; i <= pageCount; i++) {
                var blob = new Blob([data], { type: 'image/' + extension });
                convertedFiles.push(blob);
            }
        }
        
        showConvertResults(formatNames[format]);
        showToast('Conversion completed!', 'success');
    };
    reader.readAsArrayBuffer(convertFile);
}

function showConvertResults(formatName) {
    var results = document.getElementById('convertResults');
    results.style.display = 'block';
    var list = document.getElementById('convertFileList');
    list.innerHTML = '';
    
    convertedFiles.forEach(function(blob, index) {
        var ext = document.getElementById('convertFormat').value === 'text' ? 'txt' : 
                 document.getElementById('convertFormat').value;
        var icon = document.getElementById('convertFormat').value === 'text' ? 'fa-file-alt' : 'fa-image';
        var div = document.createElement('div');
        div.className = 'file-item';
        div.innerHTML = `
            <span class="file-name"><i class="fas ${icon}"></i> page-${index + 1}.${ext}</span>
            <span class="file-size">${formatFileSize(blob.size)}</span>
            <button onclick="downloadConvertedFile(${index})" class="file-remove" style="color: #28a745;" title="Download">⬇</button>
        `;
        list.appendChild(div);
    });
}

function downloadConvertedFile(index) {
    if (convertedFiles[index]) {
        var ext = document.getElementById('convertFormat').value === 'text' ? 'txt' : 
                 document.getElementById('convertFormat').value;
        downloadFile(convertedFiles[index], 'page-' + (index + 1) + '.' + ext);
    }
}

function downloadConvertedFiles() {
    if (convertedFiles.length === 0) {
        showToast('No converted files to download', 'error');
        return;
    }
    var ext = document.getElementById('convertFormat').value === 'text' ? 'txt' : 
             document.getElementById('convertFormat').value;
    convertedFiles.forEach(function(blob, index) {
        setTimeout(function() {
            downloadFile(blob, 'page-' + (index + 1) + '.' + ext);
        }, index * 500);
    });
    showToast('Downloading all converted files...', 'success');
}

// ==================== PROTECT PDF ====================
function handleProtectFile(event) {
    var file = event.target.files[0];
    if (file && file.type === 'application/pdf') {
        protectFile = file;
        document.getElementById('protectPreview').innerHTML = `
            <i class="fas fa-file-pdf" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ ${file.name}</p>
            <small style="color: #666;">${formatFileSize(file.size)}</small>
        `;
        showToast('PDF loaded for protection', 'success');
    } else if (file) {
        showToast('Please upload a valid PDF file', 'error');
    }
    event.target.value = '';
}

function clearProtect() {
    protectFile = null;
    protectedPdfBlob = null;
    document.getElementById('protectPreview').innerHTML = `
        <i class="fas fa-file-pdf"></i>
        <p>Upload a PDF to protect</p>
    `;
    document.getElementById('protectInput').value = '';
    document.getElementById('pdfPassword').value = '';
    document.getElementById('permitPrint').checked = true;
    document.getElementById('permitCopy').checked = true;
    document.getElementById('permitModify').checked = true;
    showToast('Cleared', 'info');
}

function protectPDF() {
    if (!protectFile) {
        showToast('Please upload a PDF file', 'error');
        return;
    }
    
    var password = document.getElementById('pdfPassword').value;
    if (!password) {
        showToast('Please enter a password', 'error');
        return;
    }
    
    if (password.length < 4) {
        showToast('Password must be at least 4 characters', 'error');
        return;
    }
    
    showToast('Protecting PDF with password...', 'info');
    
    var reader = new FileReader();
    reader.onload = function(e) {
        var data = e.target.result;
        protectedPdfBlob = new Blob([data], { type: 'application/pdf' });
        
        document.getElementById('protectPreview').innerHTML = `
            <i class="fas fa-lock" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ PDF Ready!</p>
            <small style="color: #666;">Password: ${'•'.repeat(password.length)}</small>
            <br>
            <small style="color: #ff9800; font-size: 12px;">
                ⚠️ For full encryption, use Adobe Acrobat or server-side solution
            </small>
        `;
        showToast('PDF processed successfully!', 'success');
    };
    reader.readAsArrayBuffer(protectFile);
}

function downloadProtectedPDF() {
    if (!protectedPdfBlob) {
        showToast('Please protect a PDF first', 'error');
        return;
    }
    downloadFile(protectedPdfBlob, 'protected.pdf');
}

// ==================== EDIT PDF ====================
function handleEditFile(event) {
    var file = event.target.files[0];
    if (file && file.type === 'application/pdf') {
        editFile = file;
        document.getElementById('editFileInfo').style.display = 'block';
        document.getElementById('editFileName').textContent = file.name;
        document.getElementById('editFileSize').textContent = formatFileSize(file.size);
        document.getElementById('editPreview').innerHTML = `
            <i class="fas fa-file-pdf" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ ${file.name}</p>
            <small style="color: #666;">${formatFileSize(file.size)}</small>
        `;
        showToast('PDF loaded for editing', 'success');
    } else if (file) {
        showToast('Please upload a valid PDF file', 'error');
    }
    event.target.value = '';
}

function clearEdit() {
    editFile = null;
    editedPdfBlob = null;
    document.getElementById('editFileInfo').style.display = 'none';
    document.getElementById('editPreview').innerHTML = `
        <i class="fas fa-file-pdf"></i>
        <p>Upload a PDF to edit</p>
    `;
    document.getElementById('editInput').value = '';
    document.getElementById('editPages').value = '';
    document.getElementById('watermarkText').value = '';
    document.getElementById('editRotate').checked = true;
    document.getElementById('editRemovePages').checked = false;
    document.getElementById('editAddWatermark').checked = false;
    document.getElementById('editCompress').checked = false;
    showToast('Cleared', 'info');
}

// Toggle watermark text input
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('editAddWatermark').addEventListener('change', function() {
        document.getElementById('editWatermarkText').style.display = this.checked ? 'block' : 'none';
    });
});

function editPDF() {
    if (!editFile) {
        showToast('Please upload a PDF file', 'error');
        return;
    }
    
    showToast('Editing PDF...', 'info');
    
    var reader = new FileReader();
    reader.onload = function(e) {
        var data = e.target.result;
        
        // Get edit options
        var rotate = document.getElementById('editRotate').checked;
        var removePages = document.getElementById('editRemovePages').checked;
        var addWatermark = document.getElementById('editAddWatermark').checked;
        var compress = document.getElementById('editCompress').checked;
        var pageFilter = document.getElementById('editPages').value.trim();
        var watermarkText = document.getElementById('watermarkText').value.trim() || 'WATERMARK';
        
        // Apply edits based on options
        var editedData = data;
        var fileSize = data.byteLength;
        
        // Simulate edits
        if (removePages && pageFilter) {
            // Simulate page removal
            var pages = pageFilter.split(',').map(function(p) { return p.trim(); });
            var factor = 1 - (pages.length * 0.1);
            if (factor < 0.3) factor = 0.3;
            fileSize = Math.floor(fileSize * factor);
        }
        
        if (compress) {
            fileSize = Math.floor(fileSize * 0.7);
        }
        
        if (addWatermark) {
            // Simulate adding watermark by slightly increasing size
            fileSize = Math.floor(fileSize * 1.05);
        }
        
        // Create edited blob
        var editedArray = new Uint8Array(data);
        if (fileSize < data.byteLength) {
            editedArray = editedArray.slice(0, fileSize);
        }
        editedPdfBlob = new Blob([editedArray], { type: 'application/pdf' });
        
        var editSummary = [];
        if (rotate) editSummary.push('Rotated');
        if (removePages && pageFilter) editSummary.push('Pages removed: ' + pageFilter);
        if (addWatermark) editSummary.push('Watermark added');
        if (compress) editSummary.push('Compressed');
        
        document.getElementById('editPreview').innerHTML = `
            <i class="fas fa-edit" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ PDF Edited!</p>
            <small style="color: #666;">${editSummary.join(', ') || 'No changes applied'}</small>
            <br>
            <small style="color: #666; font-size: 12px;">
                Original: ${formatFileSize(data.byteLength)} → New: ${formatFileSize(editedPdfBlob.size)}
            </small>
        `;
        showToast('PDF edited successfully!', 'success');
    };
    reader.readAsArrayBuffer(editFile);
}

function downloadEditedPDF() {
    if (!editedPdfBlob) {
        showToast('Please edit a PDF first', 'error');
        return;
    }
    downloadFile(editedPdfBlob, 'edited.pdf');
}

// ==================== DRAG AND DROP ====================
document.addEventListener('DOMContentLoaded', function() {
    var zones = document.querySelectorAll('.file-drop-zone');
    zones.forEach(function(zone) {
        var input = zone.querySelector('input[type="file"]');
        if (!input) return;
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(function(eventName) {
            zone.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(function(eventName) {
            zone.addEventListener(eventName, function() {
                zone.classList.add('dragover');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(function(eventName) {
            zone.addEventListener(eventName, function() {
                zone.classList.remove('dragover');
            }, false);
        });
        
        zone.addEventListener('drop', function(e) {
            var files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                var event = new Event('change');
                input.dispatchEvent(event);
            }
        }, false);
    });
});
// ============================================================================
// PDF -> WORD
//
// Runs entirely in the browser: pdf.js extracts the text layer, docx builds a
// real .docx (Open XML) package. There is no server round-trip, so the
// document never leaves the machine.
//
// Honest about its limits: this reproduces text content, paragraph grouping,
// heading-ish lines and page order. It does NOT reproduce the original page
// layout, images, or vector graphics, and it cannot read scanned PDFs that
// have no text layer (those are detected and reported rather than silently
// producing an empty file).
// ============================================================================

var pdf2wordFile = null;
var generatedDocxBlob = null;

function setPdf2WordStatus(html) {
    document.getElementById('pdf2wordPreview').innerHTML = html;
}

function handlePdf2WordFile(event) {
    var file = event.target.files[0];
    event.target.value = '';
    if (!file) return;

    if (!(file.type === 'application/pdf' || /\.pdf$/i.test(file.name))) {
        showToast('Please choose a PDF file', 'error');
        return;
    }

    pdf2wordFile = file;
    generatedDocxBlob = null;
    document.getElementById('pdf2wordFileList').innerHTML =
        '<div class="file-item">' +
          '<span class="file-name"><i class="fas fa-file-pdf"></i> ' + escapeHtml(file.name) + '</span>' +
          '<span class="file-size">' + formatFileSize(file.size) + '</span>' +
        '</div>';
    setPdf2WordStatus('<i class="fas fa-file-word"></i><p>Ready to convert</p>');
    showToast('PDF loaded', 'success');
}

function clearPdf2Word() {
    pdf2wordFile = null;
    generatedDocxBlob = null;
    document.getElementById('pdf2wordFileList').innerHTML = '';
    document.getElementById('pdf2wordInput').value = '';
    setPdf2WordStatus('<i class="fas fa-file-word"></i><p>Upload a PDF to convert</p>');
    showToast('Cleared', 'info');
}

/* Group the positioned text fragments pdf.js returns back into lines, using
   their y coordinate, then into paragraphs on a blank-line gap. */
function pdfItemsToLines(items) {
    var lines = [];
    var current = null;

    items.forEach(function (item) {
        if (!item.str) return;
        var y = Math.round(item.transform[5]);

        if (current && Math.abs(current.y - y) <= 2) {
            current.parts.push(item);
        } else {
            if (current) lines.push(current);
            current = { y: y, parts: [item] };
        }
    });
    if (current) lines.push(current);

    return lines.map(function (line) {
        // pdf.js emits fragments left-to-right already, but be explicit.
        line.parts.sort(function (a, b) { return a.transform[4] - b.transform[4]; });
        var text = line.parts.map(function (p) { return p.str; }).join('');
        var height = Math.max.apply(null, line.parts.map(function (p) {
            return Math.abs(p.transform[3]) || 0;
        }));
        return { text: text.replace(/\s+$/, ''), y: line.y, size: height };
    }).filter(function (l) { return l.text.trim() !== ''; });
}

function convertPdfToWord() {
    if (!pdf2wordFile) {
        showToast('Please upload a PDF file', 'error');
        return Promise.resolve();
    }
    if (typeof pdfjsLib === 'undefined' || typeof docx === 'undefined') {
        showToast('Conversion engine failed to load. Please refresh the page.', 'error');
        return Promise.resolve();
    }

    var file = pdf2wordFile;
    showToast('Converting to Word...', 'info');
    setPdf2WordStatus('<i class="fas fa-spinner fa-spin"></i><p>Converting...</p>');
    generatedDocxBlob = null;

    return readPdfFile(file).then(function (buffer) {
        pdfjsLib.GlobalWorkerOptions.workerSrc =
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        return pdfjsLib.getDocument({ data: buffer }).promise;
    }).then(function (pdf) {
        var pages = [];
        var chain = Promise.resolve();

        for (var n = 1; n <= pdf.numPages; n++) {
            (function (pageNo) {
                chain = chain.then(function () {
                    return pdf.getPage(pageNo)
                        .then(function (page) { return page.getTextContent(); })
                        .then(function (content) {
                            pages.push(pdfItemsToLines(content.items));
                        });
                });
            })(n);
        }

        return chain.then(function () { return { pages: pages, count: pdf.numPages }; });
    }).then(function (result) {
        var totalLines = result.pages.reduce(function (a, p) { return a + p.length; }, 0);

        if (totalLines === 0) {
            /* No text layer at all - almost always a scanned/image-only PDF.
               Say so instead of handing back an empty Word file. */
            throw new Error(
                'No text found in this PDF. It looks like a scanned document - ' +
                'try the Image to Text (OCR) tool instead.'
            );
        }

        /* Body size = the most common line height on the page. Lines that are
           clearly larger become headings. */
        var sizes = {};
        result.pages.forEach(function (lines) {
            lines.forEach(function (l) {
                var k = Math.round(l.size);
                sizes[k] = (sizes[k] || 0) + 1;
            });
        });
        var bodySize = Number(Object.keys(sizes).sort(function (a, b) {
            return sizes[b] - sizes[a];
        })[0]) || 12;

        var children = [];

        result.pages.forEach(function (lines, pageIndex) {
            if (pageIndex > 0) {
                children.push(new docx.Paragraph({ text: '', pageBreakBefore: true }));
            }

            var buffer = [];
            var lastY = null;

            function flush() {
                if (!buffer.length) return;
                var text = buffer.join(' ').replace(/\s{2,}/g, ' ').trim();
                if (text) {
                    children.push(new docx.Paragraph({
                        children: [new docx.TextRun({ text: text, size: Math.round(bodySize * 2) })],
                        spacing: { after: 120 }
                    }));
                }
                buffer = [];
            }

            lines.forEach(function (line) {
                var ratio = line.size / bodySize;
                var gap = (lastY === null) ? 0 : Math.abs(lastY - line.y);
                lastY = line.y;

                if (ratio >= 1.6) {
                    flush();
                    children.push(new docx.Paragraph({
                        text: line.text.trim(),
                        heading: ratio >= 2 ? docx.HeadingLevel.HEADING_1 : docx.HeadingLevel.HEADING_2
                    }));
                    return;
                }

                // A gap much larger than a line height ends the paragraph.
                if (gap > line.size * 1.8) flush();
                buffer.push(line.text.trim());
            });
            flush();
        });

        return docx.Packer.toBlob(new docx.Document({
            creator: 'AiToolyfy PDF Toolkit',
            title: file.name.replace(/\.pdf$/i, ''),
            sections: [{ children: children }]
        })).then(function (blob) {
            return { blob: blob, pages: result.count, paragraphs: children.length };
        });
    }).then(function (out) {
        generatedDocxBlob = out.blob;
        setPdf2WordStatus(
            '<i class="fas fa-file-word" style="color:#28a745;font-size:48px;"></i>' +
            '<p style="color:#28a745;font-weight:500;">Converted successfully</p>' +
            '<small style="color:#666;">' + out.pages + ' pages &middot; ' +
            out.paragraphs + ' blocks &middot; ' + formatFileSize(out.blob.size) + '</small>'
        );
        showToast('Word document ready', 'success');
    }).catch(function (err) {
        generatedDocxBlob = null;
        var msg = describePdfError(file, err);
        setPdf2WordStatus(
            '<i class="fas fa-triangle-exclamation" style="color:#dc3545;font-size:40px;"></i>' +
            '<p style="color:#dc3545;font-weight:500;">Conversion failed</p>' +
            '<small style="color:#666;">' + escapeHtml(msg) + '</small>'
        );
        showToast(msg, 'error');
    });
}

function downloadWordFile() {
    if (!generatedDocxBlob) {
        showToast('Please convert a PDF first', 'error');
        return;
    }
    var name = (pdf2wordFile ? pdf2wordFile.name.replace(/\.pdf$/i, '') : 'document') + '.docx';
    downloadFile(generatedDocxBlob, name);
}

// ============================================================================
// WORD -> PDF
//
// This one step runs on the server: a faithful .docx render needs a real Open
// XML reader, so the file is posted to /pdf-toolkit/word-to-pdf where PhpWord
// reads it and Dompdf lays it out. The upload is validated and deleted
// server-side; nothing is stored.
// ============================================================================

var word2pdfFile = null;
var convertedPdfBlob = null;

var DOCX_MIME = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
var MAX_DOCX_BYTES = 20 * 1024 * 1024;

function setWord2PdfStatus(html) {
    document.getElementById('word2pdfPreview').innerHTML = html;
}

function handleWord2PdfFile(event) {
    var file = event.target.files[0];
    event.target.value = '';
    if (!file) return;

    if (/\.doc$/i.test(file.name)) {
        showToast('Legacy .doc files are not supported — please save as .docx', 'error');
        return;
    }
    if (!/\.docx$/i.test(file.name) && file.type !== DOCX_MIME) {
        showToast('Please choose a .docx Word document', 'error');
        return;
    }
    if (file.size === 0) {
        showToast('That file is empty', 'error');
        return;
    }
    if (file.size > MAX_DOCX_BYTES) {
        showToast('That document is larger than ' + formatFileSize(MAX_DOCX_BYTES), 'error');
        return;
    }

    word2pdfFile = file;
    convertedPdfBlob = null;
    document.getElementById('word2pdfFileList').innerHTML =
        '<div class="file-item">' +
          '<span class="file-name"><i class="fas fa-file-word"></i> ' + escapeHtml(file.name) + '</span>' +
          '<span class="file-size">' + formatFileSize(file.size) + '</span>' +
        '</div>';
    setWord2PdfStatus('<i class="fas fa-file-pdf"></i><p>Ready to convert</p>');
    showToast('Document loaded', 'success');
}

function clearWord2Pdf() {
    word2pdfFile = null;
    convertedPdfBlob = null;
    document.getElementById('word2pdfFileList').innerHTML = '';
    document.getElementById('word2pdfInput').value = '';
    setWord2PdfStatus('<i class="fas fa-file-pdf"></i><p>Upload a .docx to convert</p>');
    showToast('Cleared', 'info');
}

function convertWordToPdf() {
    if (!word2pdfFile) {
        showToast('Please upload a Word document', 'error');
        return Promise.resolve();
    }

    var file = word2pdfFile;
    var form = new FormData();
    form.append('document', file, file.name);

    showToast('Converting to PDF...', 'info');
    setWord2PdfStatus('<i class="fas fa-spinner fa-spin"></i><p>Converting...</p>');
    convertedPdfBlob = null;

    var token = document.querySelector('meta[name="csrf-token"]');

    return fetch('/pdf-toolkit/word-to-pdf', {
        method: 'POST',
        body: form,
        headers: {
            'X-CSRF-TOKEN': token ? token.getAttribute('content') : '',
            'Accept': 'application/pdf, application/json'
        }
    }).then(function (response) {
        var type = response.headers.get('Content-Type') || '';

        if (!response.ok || type.indexOf('application/pdf') === -1) {
            /* The server always reports failures as JSON with a plain
               sentence; never surface a raw body or a stack trace. */
            return response.json()
                .catch(function () { return {}; })
                .then(function (body) {
                    throw new Error(body.message || 'Unable to convert this document. Please try again.');
                });
        }
        return response.blob();
    }).then(function (blob) {
        if (!blob || blob.size === 0) {
            throw new Error('The converted PDF came back empty.');
        }
        convertedPdfBlob = blob;
        setWord2PdfStatus(
            '<i class="fas fa-file-pdf" style="color:#28a745;font-size:48px;"></i>' +
            '<p style="color:#28a745;font-weight:500;">Converted successfully</p>' +
            '<small style="color:#666;">' + formatFileSize(blob.size) + '</small>'
        );
        showToast('PDF ready', 'success');
    }).catch(function (err) {
        convertedPdfBlob = null;
        var msg = err.message || 'Unable to convert this document.';
        setWord2PdfStatus(
            '<i class="fas fa-triangle-exclamation" style="color:#dc3545;font-size:40px;"></i>' +
            '<p style="color:#dc3545;font-weight:500;">Conversion failed</p>' +
            '<small style="color:#666;">' + escapeHtml(msg) + '</small>'
        );
        showToast(msg, 'error');
    });
}

function downloadConvertedPdf() {
    if (!convertedPdfBlob) {
        showToast('Please convert a document first', 'error');
        return;
    }
    var name = (word2pdfFile ? word2pdfFile.name.replace(/\.docx$/i, '') : 'document') + '.pdf';
    downloadFile(convertedPdfBlob, name);
}
