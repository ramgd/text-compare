// State variables
let currentFileData = null;
let currentImageData = null;
let currentFileBase64 = null;
let currentImageBase64 = null;

// Tab Switching
function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.tab-btn[data-tab="${tabName}"]`).classList.add('active');
    
    // Update tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(`${tabName}-tab`).classList.add('active');
}

// ==================== TEXT FUNCTIONS ====================

function encodeText() {
    const input = document.getElementById('textInput').value;
    if (!input) {
        showToast('Please enter some text to encode', 'error');
        return;
    }
    
    try {
        // Encode to Base64
        const encoded = btoa(unescape(encodeURIComponent(input)));
        document.getElementById('textOutput').value = encoded;
        showToast('Text encoded successfully!', 'success');
    } catch (error) {
        showToast('Error encoding text: ' + error.message, 'error');
    }
}

function decodeText() {
    const input = document.getElementById('textInput').value;
    if (!input) {
        showToast('Please enter Base64 text to decode', 'error');
        return;
    }
    
    try {
        // Decode from Base64
        const decoded = decodeURIComponent(escape(atob(input)));
        document.getElementById('textOutput').value = decoded;
        showToast('Text decoded successfully!', 'success');
    } catch (error) {
        showToast('Invalid Base64 string. Please check your input.', 'error');
    }
}

function clearText() {
    document.getElementById('textInput').value = '';
    document.getElementById('textOutput').value = '';
    showToast('Cleared', 'info');
}

// ==================== FILE FUNCTIONS ====================

function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;
    processFile(file);
}

function processFile(file) {
    currentFileData = file;
    
    // Display file info
    document.getElementById('fileInfo').style.display = 'block';
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
    document.getElementById('fileType').textContent = file.type || 'Unknown';
    
    // Read file as Base64
    const reader = new FileReader();
    reader.onload = function(e) {
        // Store the full data URL including the MIME type
        currentFileBase64 = e.target.result;
        // Show just the base64 part in the output
        const base64Part = e.target.result.split(',')[1] || e.target.result;
        document.getElementById('fileOutput').value = base64Part;
        showToast('File loaded successfully!', 'success');
    };
    reader.onerror = function() {
        showToast('Error reading file', 'error');
    };
    reader.readAsDataURL(file);
}

function encodeFile() {
    if (!currentFileData) {
        showToast('Please select a file first', 'error');
        return;
    }
    
    if (currentFileBase64) {
        document.getElementById('fileOutput').value = currentFileBase64.split(',')[1] || currentFileBase64;
        showToast('File encoded to Base64!', 'success');
    } else {
        showToast('No file data available', 'error');
    }
}

function clearFile() {
    currentFileData = null;
    currentFileBase64 = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').style.display = 'none';
    document.getElementById('fileOutput').value = '';
    showToast('Cleared', 'info');
}

function copyFileOutput() {
    const output = document.getElementById('fileOutput').value;
    if (!output) {
        showToast('Nothing to copy', 'error');
        return;
    }
    copyToClipboard(output);
}

function downloadFileOutput() {
    const output = document.getElementById('fileOutput').value;
    if (!output) {
        showToast('No output to download', 'error');
        return;
    }
    
    const blob = new Blob([output], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'base64-output.txt';
    a.click();
    URL.revokeObjectURL(url);
    showToast('File downloaded!', 'success');
}

// ==================== IMAGE FUNCTIONS ====================

function handleImageSelect(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // Check if it's an image
    if (!file.type.startsWith('image/')) {
        showToast('Please select an image file', 'error');
        return;
    }
    
    processImage(file);
}

function processImage(file) {
    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('imagePreview').style.display = 'block';
        var previewImage = document.getElementById('previewImage');
        previewImage.src = e.target.result;
        previewImage.alt = 'Preview of the selected image';
        previewImage.hidden = false;   // the placeholder starts hidden so an
                                       // empty <img> never renders as broken
    };
    reader.readAsDataURL(file);
    
    // Read as Base64
    const base64Reader = new FileReader();
    base64Reader.onload = function(e) {
        currentImageBase64 = e.target.result;
        // Show just the base64 part
        const base64Part = e.target.result.split(',')[1] || e.target.result;
        document.getElementById('imageOutput').value = base64Part;
        currentImageData = file;
        showToast('Image loaded successfully!', 'success');
    };
    base64Reader.onerror = function() {
        showToast('Error reading image', 'error');
    };
    base64Reader.readAsDataURL(file);
}

function encodeImage() {
    if (!currentImageData) {
        showToast('Please select an image first', 'error');
        return;
    }
    
    if (currentImageBase64) {
        const base64Part = currentImageBase64.split(',')[1] || currentImageBase64;
        document.getElementById('imageOutput').value = base64Part;
        showToast('Image encoded to Base64!', 'success');
    } else {
        showToast('No image data available', 'error');
    }
}

function clearImage() {
    currentImageData = null;
    currentImageBase64 = null;
    document.getElementById('imageInput').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('imageOutput').value = '';
    showToast('Cleared', 'info');
}

function copyImageOutput() {
    const output = document.getElementById('imageOutput').value;
    if (!output) {
        showToast('Nothing to copy', 'error');
        return;
    }
    copyToClipboard(output);
}

function downloadImageOutput() {
    const output = document.getElementById('imageOutput').value;
    if (!output) {
        showToast('No output to download', 'error');
        return;
    }
    
    const blob = new Blob([output], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'image-base64.txt';
    a.click();
    URL.revokeObjectURL(url);
    showToast('Downloaded!', 'success');
}

// ==================== GENERAL FUNCTIONS ====================

function copyOutput() {
    const output = document.getElementById('textOutput').value;
    if (!output) {
        showToast('Nothing to copy', 'error');
        return;
    }
    copyToClipboard(output);
}

function downloadText() {
    const output = document.getElementById('textOutput').value;
    if (!output) {
        showToast('No output to download', 'error');
        return;
    }
    
    const blob = new Blob([output], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'base64-output.txt';
    a.click();
    URL.revokeObjectURL(url);
    showToast('Downloaded!', 'success');
}

function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard!', 'success');
        }).catch(() => {
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

function fallbackCopy(text) {
    const textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        showToast('Copied to clipboard!', 'success');
    } catch (e) {
        showToast('Failed to copy', 'error');
    }
    document.body.removeChild(textarea);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// ==================== DRAG AND DROP ====================

// Setup drag and drop for file and image zones
document.addEventListener('DOMContentLoaded', function() {
    setupDragAndDrop('fileDropZone', 'fileInput', processFile);
    setupDragAndDrop('imageDropZone', 'imageInput', processImage);
});

function setupDragAndDrop(zoneId, inputId, callback) {
    const zone = document.getElementById(zoneId);
    const input = document.getElementById(inputId);
    
    if (!zone || !input) return;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        zone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        zone.addEventListener(eventName, () => {
            zone.classList.add('dragover');
        }, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        zone.addEventListener(eventName, () => {
            zone.classList.remove('dragover');
        }, false);
    });
    
    zone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            input.files = files;
            callback(files[0]);
        }
    }, false);
}

// ==================== TOAST SYSTEM ====================

/* showToast() lives in js/common.js - every tool had a byte-for-byte
   equivalent copy of it. common.js is loaded first on every page. */
