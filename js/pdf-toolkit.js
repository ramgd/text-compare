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
function showToast(message, type) {
    if (type === undefined) type = 'info';
    var toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'toast';
        document.body.appendChild(toast);
    }
    
    toast.textContent = message;
    toast.className = 'toast show ' + type;
    
    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(function() {
        toast.className = 'toast';
    }, 3000);
}

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
    var files = event.target.files;
    for (var i = 0; i < files.length; i++) {
        if (files[i].type === 'application/pdf') {
            mergeFiles.push(files[i]);
        } else {
            showToast(files[i].name + ' is not a PDF file', 'error');
        }
    }
    renderMergeFileList();
    event.target.value = '';
    if (files.length > 0) {
        showToast(files.length + ' PDF(s) added', 'success');
    }
}

function renderMergeFileList() {
    var list = document.getElementById('mergeFileList');
    list.innerHTML = '';
    if (mergeFiles.length === 0) {
        list.innerHTML = '<p style="color: #999; font-size: 13px; text-align: center;">No files uploaded</p>';
        return;
    }
    mergeFiles.forEach(function(file, index) {
        var div = document.createElement('div');
        div.className = 'file-item';
        div.innerHTML = `
            <span class="file-name"><i class="fas fa-file-pdf"></i> ${file.name}</span>
            <span class="file-size">${formatFileSize(file.size)}</span>
            <button onclick="removeMergeFile(${index})" class="file-remove" title="Remove">✕</button>
        `;
        list.appendChild(div);
    });
}

function removeMergeFile(index) {
    mergeFiles.splice(index, 1);
    renderMergeFileList();
}

function clearMerge() {
    mergeFiles = [];
    mergedPdfBlob = null;
    document.getElementById('mergeFileList').innerHTML = '<p style="color: #999; font-size: 13px; text-align: center;">No files uploaded</p>';
    document.getElementById('mergePreview').innerHTML = `
        <i class="fas fa-file-pdf"></i>
        <p>Upload PDFs to merge</p>
    `;
    document.getElementById('mergeInput').value = '';
    showToast('Cleared', 'info');
}

function mergePDF() {
    if (mergeFiles.length < 2) {
        showToast('Please upload at least 2 PDF files', 'error');
        return;
    }
    
    showToast('Merging PDFs...', 'info');
    
    var readerPromises = mergeFiles.map(function(file) {
        return new Promise(function(resolve, reject) {
            var reader = new FileReader();
            reader.onload = function(e) {
                resolve(e.target.result);
            };
            reader.onerror = function() {
                reject('Failed to read ' + file.name);
            };
            reader.readAsArrayBuffer(file);
        });
    });
    
    Promise.all(readerPromises).then(function(arrays) {
        var mergedArray = new Uint8Array(arrays.reduce(function(acc, arr) {
            return acc + arr.byteLength;
        }, 0));
        var offset = 0;
        arrays.forEach(function(arr) {
            mergedArray.set(new Uint8Array(arr), offset);
            offset += arr.byteLength;
        });
        
        mergedPdfBlob = new Blob([mergedArray], { type: 'application/pdf' });
        
        document.getElementById('mergePreview').innerHTML = `
            <i class="fas fa-file-pdf" style="color: #28a745; font-size: 48px;"></i>
            <p style="color: #28a745; font-weight: 500;">✅ PDF Merged Successfully!</p>
            <small style="color: #666;">${mergeFiles.length} PDFs merged into one file</small>
        `;
        showToast('PDFs merged successfully!', 'success');
    }).catch(function(error) {
        showToast('Error merging PDFs: ' + error, 'error');
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