// ============================================
// URL ENCODER/DECODER - COMPLETE JS
// ============================================

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

// ==================== URL ENCODER/DECODER ====================

function urlEncode() {
    var input = document.getElementById('urlInput').value;
    if (!input) {
        showToast('Please enter text to encode', 'error');
        return;
    }
    
    try {
        var encoded = encodeURIComponent(input);
        document.getElementById('urlOutput').value = encoded;
        showToast('URL encoded successfully!', 'success');
    } catch (e) {
        showToast('Error encoding: ' + e.message, 'error');
    }
}

function urlDecode() {
    var input = document.getElementById('urlInput').value;
    if (!input) {
        showToast('Please enter URL to decode', 'error');
        return;
    }
    
    try {
        var decoded = decodeURIComponent(input);
        document.getElementById('urlOutput').value = decoded;
        showToast('URL decoded successfully!', 'success');
    } catch (e) {
        showToast('Error decoding: Invalid URL encoding', 'error');
    }
}

function clearUrl() {
    document.getElementById('urlInput').value = '';
    document.getElementById('urlOutput').value = '';
    showToast('Cleared', 'info');
}

function copyUrlOutput() {
    copyText('urlOutput');
}

function downloadUrlOutput() {
    downloadText('urlOutput', 'url-output.txt');
}

function setUrlExample(type) {
    var input = document.getElementById('urlInput');
    if (type === 'encode') {
        input.value = 'Hello World! This is a test @ example.com?q=search&page=1';
    } else if (type === 'decode') {
        input.value = 'Hello%20World%21%20This%20is%20a%20test%20%40%20example.com%3Fq%3Dsearch%26page%3D1';
    } else if (type === 'special') {
        input.value = 'Special chars: !@#$%^&*()_+-=[]{}|;:,.<>?/';
    }
    showToast('Example loaded!', 'info');
}

// ==================== HTML ENTITY ENCODER/DECODER ====================

function htmlEncode() {
    var input = document.getElementById('htmlInput').value;
    if (!input) {
        showToast('Please enter HTML text to encode', 'error');
        return;
    }
    
    try {
        var encoded = input.replace(/&/g, '&amp;')
                          .replace(/</g, '&lt;')
                          .replace(/>/g, '&gt;')
                          .replace(/"/g, '&quot;')
                          .replace(/'/g, '&#39;');
        document.getElementById('htmlOutput').value = encoded;
        showToast('HTML encoded successfully!', 'success');
    } catch (e) {
        showToast('Error encoding: ' + e.message, 'error');
    }
}

function htmlDecode() {
    var input = document.getElementById('htmlInput').value;
    if (!input) {
        showToast('Please enter HTML entities to decode', 'error');
        return;
    }
    
    try {
        var decoded = input.replace(/&amp;/g, '&')
                          .replace(/&lt;/g, '<')
                          .replace(/&gt;/g, '>')
                          .replace(/&quot;/g, '"')
                          .replace(/&#39;/g, "'");
        document.getElementById('htmlOutput').value = decoded;
        showToast('HTML decoded successfully!', 'success');
    } catch (e) {
        showToast('Error decoding: ' + e.message, 'error');
    }
}

function clearHtml() {
    document.getElementById('htmlInput').value = '';
    document.getElementById('htmlOutput').value = '';
    showToast('Cleared', 'info');
}

function copyHtmlOutput() {
    copyText('htmlOutput');
}

function downloadHtmlOutput() {
    downloadText('htmlOutput', 'html-output.txt');
}

function setHtmlExample(type) {
    var input = document.getElementById('htmlInput');
    if (type === 'encode') {
        input.value = '<h1>Hello World!</h1><p>This is a test & example</p>';
    } else if (type === 'decode') {
        input.value = '&lt;h1&gt;Hello World!&lt;/h1&gt;&lt;p&gt;This is a test &amp; example&lt;/p&gt;';
    }
    showToast('Example loaded!', 'info');
}

// ==================== BASE64 ENCODER/DECODER ====================

function base64Encode() {
    var input = document.getElementById('base64Input').value;
    if (!input) {
        showToast('Please enter text to encode', 'error');
        return;
    }
    
    try {
        var encoded = btoa(unescape(encodeURIComponent(input)));
        document.getElementById('base64Output').value = encoded;
        showToast('Base64 encoded successfully!', 'success');
    } catch (e) {
        showToast('Error encoding: ' + e.message, 'error');
    }
}

function base64Decode() {
    var input = document.getElementById('base64Input').value;
    if (!input) {
        showToast('Please enter Base64 to decode', 'error');
        return;
    }
    
    try {
        var decoded = decodeURIComponent(escape(atob(input)));
        document.getElementById('base64Output').value = decoded;
        showToast('Base64 decoded successfully!', 'success');
    } catch (e) {
        showToast('Error decoding: Invalid Base64 string', 'error');
    }
}

function clearBase64() {
    document.getElementById('base64Input').value = '';
    document.getElementById('base64Output').value = '';
    showToast('Cleared', 'info');
}

function copyBase64Output() {
    copyText('base64Output');
}

function downloadBase64Output() {
    downloadText('base64Output', 'base64-output.txt');
}

function setBase64Example(type) {
    var input = document.getElementById('base64Input');
    if (type === 'encode') {
        input.value = 'Hello World! This is a test.';
    } else if (type === 'decode') {
        input.value = 'SGVsbG8gV29ybGQhIFRoaXMgaXMgYSB0ZXN0Lg==';
    }
    showToast('Example loaded!', 'info');
}

// ==================== UTILITY FUNCTIONS ====================

function copyText(elementId) {
    var element = document.getElementById(elementId);
    if (!element.value) {
        showToast('Nothing to copy', 'error');
        return;
    }
    
    navigator.clipboard.writeText(element.value).then(function() {
        showToast('Copied to clipboard!', 'success');
    }).catch(function() {
        fallbackCopy(element.value);
    });
}

function fallbackCopy(text) {
    var textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        showToast('Copied!', 'success');
    } catch (e) {
        showToast('Failed to copy', 'error');
    }
    document.body.removeChild(textarea);
}

function downloadText(elementId, filename) {
    var element = document.getElementById(elementId);
    if (!element.value) {
        showToast('No content to download', 'error');
        return;
    }
    
    var blob = new Blob([element.value], { type: 'text/plain' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
    showToast('File downloaded!', 'success');
}

// ==================== TOAST SYSTEM ====================

/* showToast() lives in js/common.js - every tool had a byte-for-byte
   equivalent copy of it. common.js is loaded first on every page. */
