// ============================================
// HASH GENERATOR - COMPLETE JAVASCRIPT
// Using IIFE to avoid conflicts
// ============================================

(function() {
    'use strict';

    // State Variables
    var currentFileData = null;

    // ==================== TAB SWITCHING ====================
    window.switchTab = function(tabName) {
        var btns = document.querySelectorAll('.tab-btn');
        for (var i = 0; i < btns.length; i++) {
            btns[i].classList.remove('active');
        }
        document.querySelector('.tab-btn[data-tab="' + tabName + '"]').classList.add('active');
        
        var contents = document.querySelectorAll('.tab-content');
        for (var i = 0; i < contents.length; i++) {
            contents[i].classList.remove('active');
        }
        document.getElementById(tabName + '-tab').classList.add('active');
    };

    // ==================== MD5 IMPLEMENTATION ====================
    /* ====================================================================
       HASH CORE

       The previous build shipped hand-written MD5/SHA-256/SHA-512. They were
       wrong: md51() produced four 32-bit words but md5hex() emitted only two
       hex characters per word, so MD5("abc") came out as "903cd628" instead
       of the 32-character digest. SHA-512 returned mostly zeroes. They also
       used charCodeAt(), so any non-ASCII input hashed the wrong bytes.

       SHA-1/256/384/512 now come from the platform's own crypto.subtle, and
       MD5 from js-md5 (loaded by the view). Both take UTF-8 bytes.
       ==================================================================== */

    var SUBTLE_ALGO = {
        sha1: 'SHA-1',
        sha256: 'SHA-256',
        sha384: 'SHA-384',
        sha512: 'SHA-512'
    };

    function toHex(buffer) {
        var bytes = new Uint8Array(buffer);
        var out = '';
        for (var i = 0; i < bytes.length; i++) {
            out += bytes[i].toString(16).padStart(2, '0');
        }
        return out;
    }

    /* Text -> UTF-8 bytes. TextEncoder is always UTF-8 per spec. */
    function utf8Bytes(text) {
        return new TextEncoder().encode(text);
    }

    /* Returns a Promise of the lowercase hex digest.
       `input` is a string (hashed as UTF-8) or an ArrayBuffer/TypedArray. */
    function hashDigest(algo, input) {
        var isText = (typeof input === 'string');

        if (algo === 'md5') {
            if (typeof md5 === 'undefined') {
                return Promise.reject(new Error('MD5 library not loaded'));
            }
            // js-md5 handles UTF-8 strings and ArrayBuffers itself.
            var data = isText ? input : (input.buffer ? input.buffer : input);
            return Promise.resolve(md5(data));
        }

        var subtle = SUBTLE_ALGO[algo];
        if (!subtle) return Promise.reject(new Error('Unsupported algorithm: ' + algo));

        if (!window.crypto || !window.crypto.subtle) {
            return Promise.reject(new Error(
                'Secure hashing needs a secure context (https:// or localhost)'
            ));
        }

        var bytes = isText ? utf8Bytes(input) : input;
        return window.crypto.subtle.digest(subtle, bytes).then(toHex);
    }

    window.hashDigest = hashDigest;   /* exposed for the automated tests */

    // ==================== TEXT HASH FUNCTIONS ====================
    var ALL_ALGOS = ['md5', 'sha1', 'sha256', 'sha384', 'sha512'];

    function setResult(algo, value) {
        var el = document.getElementById(algo + 'Result');
        if (el) el.value = value;
    }

    window.generateHash = function() {
        var text = document.getElementById('hashTextInput').value;

        /* An empty string has a perfectly well-defined digest, so hash it
           rather than refusing - but say so, since it is easy to do by
           accident. */
        if (!text) {
            showToast('Hashing an empty string', 'info');
        }

        var selected = document.querySelectorAll('.hash-algo:checked');
        if (selected.length === 0) {
            showToast('Please select at least one hash algorithm', 'error');
            return Promise.resolve();
        }

        ALL_ALGOS.forEach(function (a) { setResult(a, ''); });

        var jobs = Array.prototype.map.call(selected, function (checkbox) {
            var algo = checkbox.value;
            setResult(algo, 'Working...');
            return hashDigest(algo, text)
                .then(function (hex) { setResult(algo, hex); })
                .catch(function (err) {
                    setResult(algo, '');
                    showToast('Could not generate ' + algo.toUpperCase() + ': ' + err.message, 'error');
                });
        });

        return Promise.all(jobs).then(function () {
            showToast('Hash generated successfully!', 'success');
        });
    };

    window.clearTextHash = function() {
        document.getElementById('hashTextInput').value = '';
        ALL_ALGOS.forEach(function (a) { setResult(a, ''); });
        showToast('Cleared', 'info');
    };

    window.copyHash = function(elementId) {
        var element = document.getElementById(elementId);
        if (!element.value) {
            showToast('No hash to copy', 'error');
            return;
        }
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(element.value).then(function() {
                showToast('Hash copied to clipboard!', 'success');
            }).catch(function() {
                fallbackCopy(element.value);
            });
        } else {
            fallbackCopy(element.value);
        }
    };

    function fallbackCopy(text) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showToast('Hash copied to clipboard!', 'success');
        } catch (e) {
            showToast('Failed to copy', 'error');
        }
        document.body.removeChild(textarea);
    }

    var ALGO_LABEL = {
        md5: 'MD5', sha1: 'SHA-1', sha256: 'SHA-256',
        sha384: 'SHA-384', sha512: 'SHA-512'
    };

    window.downloadAllHashes = function() {
        var lines = [];
        ALL_ALGOS.forEach(function (a) {
            var el = document.getElementById(a + 'Result');
            if (el && el.value && el.value !== 'Working...') {
                lines.push(ALGO_LABEL[a] + ': ' + el.value);
            }
        });

        if (!lines.length) {
            showToast('No hashes to download', 'error');
            return;
        }

        var content = '=== HASH RESULTS ===\n'
            + 'Generated: ' + new Date().toLocaleString() + '\n---\n'
            + lines.join('\n') + '\n=== END ===';

        downloadFile(content, 'hashes.txt', 'text/plain');
        showToast('Hashes downloaded!', 'success');
    };

    // ==================== FILE HASH FUNCTIONS ====================
    window.handleFileSelect = function(event) {
        var file = event.target.files[0];
        if (!file) return;
        processFile(file);
    };

    function processFile(file) {
        currentFileData = file;
        
        document.getElementById('fileInfo').style.display = 'block';
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        document.getElementById('fileType').textContent = file.type || 'Unknown';
        
        showToast('File loaded successfully!', 'success');
    }

    window.generateFileHash = function() {
        if (!currentFileData) {
            showToast('Please select a file first', 'error');
            return Promise.resolve();
        }

        var fileAlgos = ['md5', 'sha1', 'sha256', 'sha384', 'sha512'];

        function setFileResult(algo, value) {
            var id = 'file' + algo.charAt(0).toUpperCase() + algo.slice(1) + 'Result';
            var el = document.getElementById(id);
            if (el) el.value = value;
        }

        return new Promise(function (resolve) {
            var reader = new FileReader();

            reader.onerror = function () {
                showToast('Could not read this file. It may be unreadable or locked.', 'error');
                resolve();
            };

            reader.onload = function (e) {
                /* Hash the raw bytes. The previous build ran the file through
                   TextDecoder first, which replaces every byte sequence that
                   is not valid UTF-8 with U+FFFD - so the digest of any
                   binary file was computed over corrupted data. */
                var bytes = new Uint8Array(e.target.result);

                fileAlgos.forEach(function (a) { setFileResult(a, 'Working...'); });

                Promise.all(fileAlgos.map(function (algo) {
                    return hashDigest(algo, bytes)
                        .then(function (hex) { setFileResult(algo, hex); })
                        .catch(function (err) {
                            setFileResult(algo, '');
                            showToast('Could not generate ' + algo.toUpperCase() + ': ' + err.message, 'error');
                        });
                })).then(function () {
                    showToast('File hashes generated successfully!', 'success');
                    resolve();
                });
            };

            reader.readAsArrayBuffer(currentFileData);
        });
    };

    window.clearFileHash = function() {
        currentFileData = null;
        document.getElementById('fileInput').value = '';
        document.getElementById('fileInfo').style.display = 'none';
        ['fileMd5Result', 'fileSha1Result', 'fileSha256Result',
         'fileSha384Result', 'fileSha512Result'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.value = '';
        });
        showToast('Cleared', 'info');
    };

    window.downloadFileHashes = function() {
        var lines = [];
        ALL_ALGOS.forEach(function (a) {
            var id = 'file' + a.charAt(0).toUpperCase() + a.slice(1) + 'Result';
            var el = document.getElementById(id);
            if (el && el.value && el.value !== 'Working...') {
                lines.push(ALGO_LABEL[a] + ': ' + el.value);
            }
        });

        if (!lines.length) {
            showToast('No file hashes to download', 'error');
            return;
        }

        var fileName = document.getElementById('fileName').textContent || 'file';
        var content = '=== FILE HASH RESULTS ===\n'
            + 'File: ' + fileName + '\n'
            + 'Generated: ' + new Date().toLocaleString() + '\n---\n'
            + lines.join('\n') + '\n=== END ===';

        downloadFile(content, fileName + '.hashes.txt', 'text/plain');
        showToast('File hashes downloaded!', 'success');
    };

    // ==================== COMPARE FUNCTIONS ====================
    window.compareHashes = function() {
        var hash1 = document.getElementById('hash1Input').value.trim();
        var hash2 = document.getElementById('hash2Input').value.trim();
        
        if (!hash1 || !hash2) {
            showToast('Please enter both hashes to compare', 'error');
            return;
        }
        
        var detailsDiv = document.getElementById('compareDetails');
        detailsDiv.style.display = 'block';
        document.getElementById('compareHash1').textContent = hash1;
        document.getElementById('compareHash2').textContent = hash2;
        
        var isMatch = hash1 === hash2;
        var lengthMatch = hash1.length === hash2.length;
        
        var statusElement = document.getElementById('compareStatus');
        statusElement.textContent = isMatch ? '✅ MATCHING' : '❌ NOT MATCHING';
        statusElement.className = 'compare-status ' + (isMatch ? 'matching' : 'not-matching');
        
        document.getElementById('compareLength').textContent = lengthMatch ? 
            '✅ Both hashes are ' + hash1.length + ' characters long' : 
            '⚠️ Different lengths: ' + hash1.length + ' vs ' + hash2.length;
        
        document.getElementById('compareResult').style.display = 'none';
        detailsDiv.style.display = 'block';
        
        showToast(isMatch ? 'Hashes match!' : 'Hashes do not match', isMatch ? 'success' : 'error');
    };

    window.clearCompare = function() {
        document.getElementById('hash1Input').value = '';
        document.getElementById('hash2Input').value = '';
        document.getElementById('compareResult').style.display = 'flex';
        document.getElementById('compareDetails').style.display = 'none';
        showToast('Cleared', 'info');
    };

    // ==================== HASH DECODER FUNCTIONS ====================
    // Common word list for rainbow table
    var commonWords = [
        'password', '123456', '12345678', '123456789', '12345', '1234567', 
        'qwerty', 'abc123', 'password1', 'admin', 'welcome', 'letmein',
        'monkey', 'dragon', 'master', 'hello', 'freedom', 'whatever',
        'qwertyuiop', 'asdfghjkl', 'zxcvbnm', '1234567890', '987654321',
        'iloveyou', 'sunshine', 'princess', 'football', 'baseball',
        'shadow', 'ashley', 'michael', 'jessica', 'charlie',
        'hello123', 'test123', 'admin123', 'root123', 'qwerty123',
        '123123', '111111', '222222', '333333', '444444', '555555',
        '666666', '777777', '888888', '999999', '000000', 'test',
        'user', 'login', 'secret', 'pass', 'demo', 'sample', 'default',
        'changeme', 'temp', 'guest', 'support', 'office', 'internet',
        'computer', 'system', 'network', 'database', 'server', 'webmaster',
        'web', 'site', 'online', 'email', 'mail', 'admin123', 'password123',
        'pass123', '1234', '987654321', 'qwerty123', 'qwe123',
        'qwertyuiop', 'asdfghjkl', 'zxcvbnm', '1q2w3e4r', 'zaq12wsx',
        'azerty', 'azertyuiop', 'dragonball', 'pokemon', 'starwars', 
        'batman', 'superman', 'spiderman', 'ironman', 'thor', 'hulk',
        'captain', 'marvel', 'dc', 'comics', 'naruto', 'sasuke',
        'goku', 'vegeta', 'luffy', 'zoro', 'nami', 'sanji',
        'one piece', 'bleach', 'death note', 'attack on titan',
        'kimetsu', 'demon slayer', 'jujutsu', 'kaisen'
    ];

    // Pre-build rainbow table with MD5, SHA-1, SHA-256, SHA-512
    var rainbowTable = {
        md5: {},
        sha1: {},
        sha256: {},
        sha384: {},
        sha512: {}
    };

    // Build rainbow table on load
    function buildRainbowTable() {
        /* Now async, because the digests come from crypto.subtle. The table
           is only used by the "decode" tab's dictionary lookup, so nothing
           needs to wait on it. */
        var algos = ['md5', 'sha1', 'sha256', 'sha384', 'sha512'];
        algos.forEach(function (a) { rainbowTable[a] = rainbowTable[a] || {}; });

        return Promise.all(commonWords.map(function (word) {
            return Promise.all(algos.map(function (algo) {
                return hashDigest(algo, word)
                    .then(function (hex) { rainbowTable[algo][hex] = word; })
                    .catch(function () { /* algorithm unavailable - skip */ });
            }));
        }));
    }

    // Lookup hash in rainbow table
    function lookupHash(hash, type) {
        // Try MD5 first
        if (type === null || type === 'md5') {
            if (rainbowTable.md5[hash]) {
                return { found: true, value: rainbowTable.md5[hash], type: 'MD5' };
            }
        }
        
        // Try SHA-256
        if (type === null || type === 'sha256') {
            if (rainbowTable.sha256[hash]) {
                return { found: true, value: rainbowTable.sha256[hash], type: 'SHA-256' };
            }
        }
        
        // Try SHA-512
        if (type === null || type === 'sha512') {
            if (rainbowTable.sha512[hash]) {
                return { found: true, value: rainbowTable.sha512[hash], type: 'SHA-512' };
            }
        }
        
        return { found: false };
    }

    // Detect hash type based on length
    function detectHashType(hash) {
        var length = hash.length;
        var patterns = {
            '32': 'MD5',
            '40': 'SHA-1',
            '64': 'SHA-256',
            '128': 'SHA-512'
        };
        
        // Check if it's a valid hex string
        var isHex = /^[0-9a-fA-F]+$/.test(hash);
        if (!isHex) return 'Unknown (not a valid hash)';
        
        return patterns[length] || 'Unknown (length ' + length + ')';
    }

    // Decode hash from input
    window.decodeHashInput = function() {
        var hash = document.getElementById('decodeInput').value.trim();
        if (!hash) {
            showToast('Please enter a hash to decode', 'error');
            return;
        }
        
        decodeHashInternal(hash);
    };

    // Decode hash from result
    window.decodeHash = function(elementId) {
        var element = document.getElementById(elementId);
        var hash = element.value.trim();
        if (!hash) {
            showToast('No hash to decode', 'error');
            return;
        }
        
        // Switch to decode tab
        switchTab('decode');
        document.getElementById('decodeInput').value = hash;
        decodeHashInternal(hash);
    };

    // Internal decode function
    function decodeHashInternal(hash) {
        var hashType = detectHashType(hash);
        var selectedType = document.querySelector('input[name="decodeType"]:checked').value;
        
        // Show results
        document.getElementById('decodeStatus').textContent = 'Searching...';
        document.getElementById('decodeStatus').style.color = '#333';
        document.getElementById('decodeOriginal').textContent = '-';
        document.getElementById('decodeTypeResult').textContent = hashType;
        document.getElementById('decodeLength').textContent = hash.length + ' characters';
        document.getElementById('decodeConfidence').textContent = 'Searching rainbow table...';
        document.getElementById('decodeConfidence').style.color = '#333';
        
        // Hide previous results
        document.getElementById('decodeOriginalValue').style.display = 'none';
        document.getElementById('decodeNotFound').style.display = 'none';
        
        // Search in rainbow table
        var type = selectedType === 'auto' ? null : selectedType;
        var result = lookupHash(hash, type);
        
        if (result.found) {
            document.getElementById('decodeStatus').textContent = '✅ Found!';
            document.getElementById('decodeStatus').style.color = '#28a745';
            document.getElementById('decodeOriginal').textContent = result.value;
            document.getElementById('decodeTypeResult').textContent = result.type + ' (detected)';
            document.getElementById('decodeConfidence').textContent = 'High - Exact match found in database';
            document.getElementById('decodeConfidence').style.color = '#28a745';
            
            // Show original value
            document.getElementById('decodeOriginalValue').style.display = 'block';
            document.getElementById('decodeOriginalText').textContent = result.value;
            document.getElementById('decodeNotFound').style.display = 'none';
            
            showToast('Hash decoded successfully!', 'success');
        } else {
            document.getElementById('decodeStatus').textContent = '❌ Not Found';
            document.getElementById('decodeStatus').style.color = '#dc3545';
            document.getElementById('decodeOriginal').textContent = '-';
            document.getElementById('decodeConfidence').textContent = 'No match found in database';
            document.getElementById('decodeConfidence').style.color = '#dc3545';
            
            document.getElementById('decodeOriginalValue').style.display = 'none';
            document.getElementById('decodeNotFound').style.display = 'block';
            document.getElementById('decodeNotFoundText').textContent = 'Could not find original value for this hash. The hash might be:\n• Not a common password\n• From a complex string\n• Not in our database\n\nTry generating a hash from the "Generate" tab and then decode it.';
            
            showToast('Hash not found in database', 'error');
        }
    }

    window.copyDecodedValue = function() {
        var text = document.getElementById('decodeOriginalText').textContent;
        if (!text || text === '-') {
            showToast('Nothing to copy', 'error');
            return;
        }
        
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showToast('Value copied to clipboard!', 'success');
            }).catch(function() {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    };

    window.clearDecode = function() {
        document.getElementById('decodeInput').value = '';
        document.getElementById('decodeStatus').textContent = 'Waiting for input...';
        document.getElementById('decodeStatus').style.color = '#333';
        document.getElementById('decodeOriginal').textContent = '-';
        document.getElementById('decodeTypeResult').textContent = '-';
        document.getElementById('decodeLength').textContent = '-';
        document.getElementById('decodeConfidence').textContent = '-';
        document.getElementById('decodeConfidence').style.color = '#333';
        document.getElementById('decodeOriginalValue').style.display = 'none';
        document.getElementById('decodeNotFound').style.display = 'none';
        showToast('Cleared', 'info');
    };

    // ==================== UTILITY FUNCTIONS ====================
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var sizes = ['Bytes', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function downloadFile(content, filename, type) {
        var blob = new Blob([content], { type: type });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    // ==================== DRAG AND DROP ====================
    document.addEventListener('DOMContentLoaded', function() {
        // Build rainbow table
        buildRainbowTable();
        
        var zone = document.getElementById('fileDropZone');
        var input = document.getElementById('fileInput');
        
        if (zone && input) {
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
                    processFile(files[0]);
                }
            }, false);
        }
        
        console.log('Hash Generator loaded successfully!');
    });

    // ==================== TOAST SYSTEM ====================
    /* showToast() lives in js/common.js - every tool had a byte-for-byte
       equivalent copy of it. common.js is loaded first on every page. */


})();