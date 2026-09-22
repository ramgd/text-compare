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
    function generateMD5(string) {
        function md5cycle(x, k) {
            var a = x[0], b = x[1], c = x[2], d = x[3];
            a = ff(a, b, c, d, k[0], 7, -680876936);
            d = ff(d, a, b, c, k[1], 12, -389564586);
            c = ff(c, d, a, b, k[2], 17, 606105819);
            b = ff(b, c, d, a, k[3], 22, -1044525330);
            a = ff(a, b, c, d, k[4], 7, -176418897);
            d = ff(d, a, b, c, k[5], 12, 1200080426);
            c = ff(c, d, a, b, k[6], 17, -1473231341);
            b = ff(b, c, d, a, k[7], 22, -45705983);
            a = ff(a, b, c, d, k[8], 7, 1770035416);
            d = ff(d, a, b, c, k[9], 12, -1958414417);
            c = ff(c, d, a, b, k[10], 17, -42063);
            b = ff(b, c, d, a, k[11], 22, -1990404162);
            a = ff(a, b, c, d, k[12], 7, 1804603682);
            d = ff(d, a, b, c, k[13], 12, -40341101);
            c = ff(c, d, a, b, k[14], 17, -1502002290);
            b = ff(b, c, d, a, k[15], 22, 1236535329);
            a = gg(a, b, c, d, k[1], 5, -165796510);
            d = gg(d, a, b, c, k[6], 9, -1069501632);
            c = gg(c, d, a, b, k[11], 14, 643717713);
            b = gg(b, c, d, a, k[0], 20, -373897302);
            a = gg(a, b, c, d, k[5], 5, -701558691);
            d = gg(d, a, b, c, k[10], 9, 38016083);
            c = gg(c, d, a, b, k[15], 14, -660478335);
            b = gg(b, c, d, a, k[4], 20, -405537848);
            a = gg(a, b, c, d, k[9], 5, 568446438);
            d = gg(d, a, b, c, k[14], 9, -1019803690);
            c = gg(c, d, a, b, k[3], 14, -187363961);
            b = gg(b, c, d, a, k[8], 20, 1163531501);
            a = gg(a, b, c, d, k[13], 5, -1444681467);
            d = gg(d, a, b, c, k[2], 9, -51403784);
            c = gg(c, d, a, b, k[7], 14, 1735328473);
            b = gg(b, c, d, a, k[12], 20, -1926607734);
            a = hh(a, b, c, d, k[5], 4, -378558);
            d = hh(d, a, b, c, k[8], 11, -2022574463);
            c = hh(c, d, a, b, k[11], 16, 1839030562);
            b = hh(b, c, d, a, k[14], 23, -35309556);
            a = hh(a, b, c, d, k[1], 4, -1530992060);
            d = hh(d, a, b, c, k[4], 11, 1272893353);
            c = hh(c, d, a, b, k[7], 16, -155497632);
            b = hh(b, c, d, a, k[10], 23, -1094730640);
            a = hh(a, b, c, d, k[13], 4, 681279174);
            d = hh(d, a, b, c, k[0], 11, -358537222);
            c = hh(c, d, a, b, k[3], 16, -722521979);
            b = hh(b, c, d, a, k[6], 23, 76029189);
            a = hh(a, b, c, d, k[9], 4, -640364487);
            d = hh(d, a, b, c, k[12], 11, -421815835);
            c = hh(c, d, a, b, k[15], 16, 530742520);
            b = hh(b, c, d, a, k[2], 23, -995338651);
            a = ii(a, b, c, d, k[0], 6, -198630844);
            d = ii(d, a, b, c, k[7], 10, 1126891415);
            c = ii(c, d, a, b, k[14], 15, -1416354905);
            b = ii(b, c, d, a, k[5], 21, -57434055);
            a = ii(a, b, c, d, k[12], 6, 1700485571);
            d = ii(d, a, b, c, k[3], 10, -1894986606);
            c = ii(c, d, a, b, k[10], 15, -1051523);
            b = ii(b, c, d, a, k[1], 21, -2054922799);
            a = ii(a, b, c, d, k[8], 6, 1873313359);
            d = ii(d, a, b, c, k[15], 10, -30611744);
            c = ii(c, d, a, b, k[6], 15, -1560198380);
            b = ii(b, c, d, a, k[13], 21, 1309151649);
            a = ii(a, b, c, d, k[4], 6, -145523070);
            d = ii(d, a, b, c, k[11], 10, -1120210379);
            c = ii(c, d, a, b, k[2], 15, 718787259);
            b = ii(b, c, d, a, k[9], 21, -343485551);
            x[0] = add32(a, x[0]);
            x[1] = add32(b, x[1]);
            x[2] = add32(c, x[2]);
            x[3] = add32(d, x[3]);
        }
        
        function cmn(q, a, b, x, s, t) {
            a = add32(add32(a, q), add32(x, t));
            return add32((a << s) | (a >>> (32 - s)), b);
        }
        
        function ff(a, b, c, d, x, s, t) {
            return cmn((b & c) | ((~b) & d), a, b, x, s, t);
        }
        
        function gg(a, b, c, d, x, s, t) {
            return cmn((b & d) | (c & (~d)), a, b, x, s, t);
        }
        
        function hh(a, b, c, d, x, s, t) {
            return cmn(b ^ c ^ d, a, b, x, s, t);
        }
        
        function ii(a, b, c, d, x, s, t) {
            return cmn(c ^ (b | (~d)), a, b, x, s, t);
        }
        
        function md5blk(s) {
            var md5blks = [], i;
            for (i = 0; i < 64; i += 4) {
                md5blks[i >> 2] = s.charCodeAt(i) + (s.charCodeAt(i + 1) << 8) + (s.charCodeAt(i + 2) << 16) + (s.charCodeAt(i + 3) << 24);
            }
            return md5blks;
        }
        
        function md51(s) {
            var n = s.length,
                state = [1732584193, -271733879, -1732584194, 271733878], i;
            for (i = 64; i <= s.length; i += 64) {
                md5cycle(state, md5blk(s.substring(i - 64, i)));
            }
            s = s.substring(i - 64);
            var tail = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            for (i = 0; i < s.length; i++)
                tail[i >> 2] |= s.charCodeAt(i) << ((i % 4) << 3);
            tail[i >> 2] |= 0x80 << ((i % 4) << 3);
            if (i > 55) {
                md5cycle(state, tail);
                for (i = 0; i < 16; i++) tail[i] = 0;
            }
            tail[14] = n * 8;
            md5cycle(state, tail);
            return state;
        }
        
        function md5hex(x) {
            var hex_chr = '0123456789abcdef';
            return hex_chr.charAt((x >> 4) & 0x0F) + hex_chr.charAt(x & 0x0F);
        }
        
        function add32(a, b) {
            return (a + b) & 0xFFFFFFFF;
        }
        
        return md51(string).map(md5hex).join('');
    }

    // ==================== SHA-256 IMPLEMENTATION ====================
    function generateSHA256(message) {
        function sha256(message) {
            function rightRotate(value, amount) {
                return (value >>> amount) | (value << (32 - amount));
            }
            
            var K = [
                0x428a2f98, 0x71374491, 0xb5c0fbcf, 0xe9b5dba5, 0x3956c25b, 0x59f111f1, 0x923f82a4, 0xab1c5ed5,
                0xd807aa98, 0x12835b01, 0x243185be, 0x550c7dc3, 0x72be5d74, 0x80deb1fe, 0x9bdc06a7, 0xc19bf174,
                0xe49b69c1, 0xefbe4786, 0x0fc19dc6, 0x240ca1cc, 0x2de92c6f, 0x4a7484aa, 0x5cb0a9dc, 0x76f988da,
                0x983e5152, 0xa831c66d, 0xb00327c8, 0xbf597fc7, 0xc6e00bf3, 0xd5a79147, 0x06ca6351, 0x14292967,
                0x27b70a85, 0x2e1b2138, 0x4d2c6dfc, 0x53380d13, 0x650a7354, 0x766a0abb, 0x81c2c92e, 0x92722c85,
                0xa2bfe8a1, 0xa81a664b, 0xc24b8b70, 0xc76c51a3, 0xd192e819, 0xd6990624, 0xf40e3585, 0x106aa070,
                0x19a4c116, 0x1e376c08, 0x2748774c, 0x34b0bcb5, 0x391c0cb3, 0x4ed8aa4a, 0x5b9cca4f, 0x682e6ff3,
                0x748f82ee, 0x78a5636f, 0x84c87814, 0x8cc70208, 0x90befffa, 0xa4506ceb, 0xbef9a3f7, 0xc67178f2
            ];
            
            var H = [
                0x6a09e667, 0xbb67ae85, 0x3c6ef372, 0xa54ff53a,
                0x510e527f, 0x9b05688c, 0x1f83d9ab, 0x5be0cd19
            ];
            
            var msgBits = [];
            for (var i = 0; i < message.length; i++) {
                var code = message.charCodeAt(i);
                msgBits.push(code >> 8 & 0xff);
                msgBits.push(code & 0xff);
            }
            msgBits.push(0x80);
            while (msgBits.length % 64 !== 56) {
                msgBits.push(0);
            }
            var bitLength = message.length * 8;
            for (var i = 0; i < 8; i++) {
                msgBits.push((bitLength >>> (56 - i * 8)) & 0xff);
            }
            
            var chunks = [];
            for (var i = 0; i < msgBits.length; i += 64) {
                chunks.push(msgBits.slice(i, i + 64));
            }
            
            chunks.forEach(function(chunk) {
                var W = new Array(64);
                for (var i = 0; i < 16; i++) {
                    W[i] = (chunk[i * 4] << 24) | (chunk[i * 4 + 1] << 16) | (chunk[i * 4 + 2] << 8) | chunk[i * 4 + 3];
                }
                for (var i = 16; i < 64; i++) {
                    var s0 = rightRotate(W[i-15], 7) ^ rightRotate(W[i-15], 18) ^ (W[i-15] >>> 3);
                    var s1 = rightRotate(W[i-2], 17) ^ rightRotate(W[i-2], 19) ^ (W[i-2] >>> 10);
                    W[i] = (W[i-16] + s0 + W[i-7] + s1) >>> 0;
                }
                
                var a = H[0], b = H[1], c = H[2], d = H[3];
                var e = H[4], f = H[5], g = H[6], h = H[7];
                
                for (var i = 0; i < 64; i++) {
                    var S1 = rightRotate(e, 6) ^ rightRotate(e, 11) ^ rightRotate(e, 25);
                    var ch = (e & f) ^ (~e & g);
                    var temp1 = (h + S1 + ch + K[i] + W[i]) >>> 0;
                    var S0 = rightRotate(a, 2) ^ rightRotate(a, 13) ^ rightRotate(a, 22);
                    var maj = (a & b) ^ (a & c) ^ (b & c);
                    var temp2 = (S0 + maj) >>> 0;
                    
                    h = g;
                    g = f;
                    f = e;
                    e = (d + temp1) >>> 0;
                    d = c;
                    c = b;
                    b = a;
                    a = (temp1 + temp2) >>> 0;
                }
                
                H[0] = (H[0] + a) >>> 0;
                H[1] = (H[1] + b) >>> 0;
                H[2] = (H[2] + c) >>> 0;
                H[3] = (H[3] + d) >>> 0;
                H[4] = (H[4] + e) >>> 0;
                H[5] = (H[5] + f) >>> 0;
                H[6] = (H[6] + g) >>> 0;
                H[7] = (H[7] + h) >>> 0;
            });
            
            var result = '';
            for (var i = 0; i < H.length; i++) {
                result += H[i].toString(16).padStart(8, '0');
            }
            return result;
        }
        
        return sha256(message);
    }

    // ==================== SHA-512 IMPLEMENTATION ====================
    function generateSHA512(message) {
        function sha512(message) {
            function rightRotate(value, amount) {
                return (value >>> amount) | (value << (64 - amount));
            }
            
            var K = [
                0x428a2f98d728ae22, 0x7137449123ef65cd, 0xb5c0fbcfec4d3b2f, 0xe9b5dba58189dbbc,
                0x3956c25bf348b538, 0x59f111f1b605d019, 0x923f82a4af194f9b, 0xab1c5ed5da6d8118,
                0xd807aa98a3030242, 0x12835b0145706fbe, 0x243185be4ee4b28c, 0x550c7dc3d5ffb4e2,
                0x72be5d74f27b896f, 0x80deb1fe3b1696b1, 0x9bdc06a725c71235, 0xc19bf174cf692694,
                0xe49b69c19ef14ad2, 0xefbe4786384f25e3, 0x0fc19dc68b8cd5b5, 0x240ca1cc77ac9c65,
                0x2de92c6f592b0275, 0x4a7484aa6ea6e483, 0x5cb0a9dcbd41fbd4, 0x76f988da831153b5,
                0x983e5152ee66dfab, 0xa831c66d2db43210, 0xb00327c898fb213f, 0xbf597fc7beef0ee4,
                0xc6e00bf33da88fc2, 0xd5a79147930aa725, 0x06ca6351e003826f, 0x142929670a0e6e70,
                0x27b70a8546d22ffc, 0x2e1b21385c26c926, 0x4d2c6dfc5ac42aed, 0x53380d139d95b3df,
                0x650a73548baf63de, 0x766a0abb3c77b2a8, 0x81c2c92e47edaee6, 0x92722c851482353b,
                0xa2bfe8a14cf10364, 0xa81a664bbc423001, 0xc24b8b70d0f89791, 0xc76c51a30654be30,
                0xd192e819d6ef5218, 0xd69906245565a910, 0xf40e35855771202a, 0x106aa07032bbd1b8,
                0x19a4c116b8d2d0c8, 0x1e376c085141ab53, 0x2748774cdf8eeb99, 0x34b0bcb5e19b48a8,
                0x391c0cb3c5c95a63, 0x4ed8aa4ae3418acb, 0x5b9cca4f7763e373, 0x682e6ff3d6b2b8a3,
                0x748f82ee5defb2fc, 0x78a5636f43172f60, 0x84c87814a1f0ab72, 0x8cc702081a6439ec,
                0x90befffa23631e28, 0xa4506cebde82bde9, 0xbef9a3f7b2c67915, 0xc67178f2e372532b,
                0xca273eceea26619c, 0xd186b8c721c0c207, 0xeada7dd6cde0eb1e, 0xf57d4f7fee6ed178,
                0x06f067aa72176fba, 0x0a637dc5a2c898a6, 0x113f9804bef90dae, 0x1b710b35131c471b,
                0x28db77f523047d84, 0x32caab7b40c72493, 0x3c9ebe0a15c9bebc, 0x431d67c49c100d4c,
                0x4cc5d4becb3e42b6, 0x597f299cfc657e2a, 0x5fcb6fab3ad6faec, 0x6c44198c4a475817
            ];
            
            var H = [
                0x6a09e667f3bcc908, 0xbb67ae8584caa73b, 0x3c6ef372fe94f82b, 0xa54ff53a5f1d36f1,
                0x510e527fade682d1, 0x9b05688c2b3e6c1f, 0x1f83d9abfb41bd6b, 0x5be0cd19137e2179
            ];
            
            var msgBits = [];
            for (var i = 0; i < message.length; i++) {
                var code = message.charCodeAt(i);
                msgBits.push(code >> 8 & 0xff);
                msgBits.push(code & 0xff);
            }
            msgBits.push(0x80);
            while (msgBits.length % 128 !== 112) {
                msgBits.push(0);
            }
            var bitLength = message.length * 8;
            for (var i = 0; i < 16; i++) {
                msgBits.push((bitLength >>> (120 - i * 8)) & 0xff);
            }
            
            var chunks = [];
            for (var i = 0; i < msgBits.length; i += 128) {
                chunks.push(msgBits.slice(i, i + 128));
            }
            
            chunks.forEach(function(chunk) {
                var W = new Array(80);
                for (var i = 0; i < 16; i++) {
                    W[i] = (chunk[i * 8] << 56) | (chunk[i * 8 + 1] << 48) | (chunk[i * 8 + 2] << 40) | 
                           (chunk[i * 8 + 3] << 32) | (chunk[i * 8 + 4] << 24) | (chunk[i * 8 + 5] << 16) | 
                           (chunk[i * 8 + 6] << 8) | chunk[i * 8 + 7];
                }
                for (var i = 16; i < 80; i++) {
                    var s0 = rightRotate(W[i-15], 1) ^ rightRotate(W[i-15], 8) ^ (W[i-15] >>> 7);
                    var s1 = rightRotate(W[i-2], 19) ^ rightRotate(W[i-2], 61) ^ (W[i-2] >>> 6);
                    W[i] = (W[i-16] + s0 + W[i-7] + s1) >>> 0;
                }
                
                var a = H[0], b = H[1], c = H[2], d = H[3];
                var e = H[4], f = H[5], g = H[6], h = H[7];
                
                for (var i = 0; i < 80; i++) {
                    var S1 = rightRotate(e, 14) ^ rightRotate(e, 18) ^ rightRotate(e, 41);
                    var ch = (e & f) ^ (~e & g);
                    var temp1 = (h + S1 + ch + K[i] + W[i]) >>> 0;
                    var S0 = rightRotate(a, 28) ^ rightRotate(a, 34) ^ rightRotate(a, 39);
                    var maj = (a & b) ^ (a & c) ^ (b & c);
                    var temp2 = (S0 + maj) >>> 0;
                    
                    h = g;
                    g = f;
                    f = e;
                    e = (d + temp1) >>> 0;
                    d = c;
                    c = b;
                    b = a;
                    a = (temp1 + temp2) >>> 0;
                }
                
                H[0] = (H[0] + a) >>> 0;
                H[1] = (H[1] + b) >>> 0;
                H[2] = (H[2] + c) >>> 0;
                H[3] = (H[3] + d) >>> 0;
                H[4] = (H[4] + e) >>> 0;
                H[5] = (H[5] + f) >>> 0;
                H[6] = (H[6] + g) >>> 0;
                H[7] = (H[7] + h) >>> 0;
            });
            
            var result = '';
            for (var i = 0; i < H.length; i++) {
                result += H[i].toString(16).padStart(16, '0');
            }
            return result;
        }
        
        return sha512(message);
    }

    // ==================== TEXT HASH FUNCTIONS ====================
    window.generateHash = function() {
        var text = document.getElementById('hashTextInput').value;
        if (!text) {
            showToast('Please enter some text to hash', 'error');
            return;
        }
        
        var selectedAlgos = document.querySelectorAll('.hash-algo:checked');
        if (selectedAlgos.length === 0) {
            showToast('Please select at least one hash algorithm', 'error');
            return;
        }
        
        // Clear previous results
        document.getElementById('md5Result').value = '';
        document.getElementById('sha1Result').value = '';
        document.getElementById('sha256Result').value = '';
        document.getElementById('sha512Result').value = '';
        
        // Process each selected algorithm
        selectedAlgos.forEach(function(checkbox) {
            var algo = checkbox.value;
            var resultId = algo + 'Result';
            
            try {
                if (algo === 'md5') {
                    var md5Hash = generateMD5(text);
                    document.getElementById(resultId).value = md5Hash;
                } else if (algo === 'sha1') {
                    // Use Web Crypto API for SHA-1
                    var encoder = new TextEncoder();
                    var data = encoder.encode(text);
                    crypto.subtle.digest('SHA-1', data)
                        .then(function(hashBuffer) {
                            var hashArray = Array.from(new Uint8Array(hashBuffer));
                            var hashHex = hashArray.map(function(b) { 
                                return b.toString(16).padStart(2, '0'); 
                            }).join('');
                            document.getElementById(resultId).value = hashHex;
                        })
                        .catch(function(err) {
                            showToast('Error generating SHA-1: ' + err.message, 'error');
                        });
                } else if (algo === 'sha256') {
                    var sha256Hash = generateSHA256(text);
                    document.getElementById(resultId).value = sha256Hash;
                } else if (algo === 'sha512') {
                    var sha512Hash = generateSHA512(text);
                    document.getElementById(resultId).value = sha512Hash;
                }
            } catch (err) {
                showToast('Error generating ' + algo + ': ' + err.message, 'error');
            }
        });
        
        showToast('Hash generated successfully!', 'success');
    };

    window.clearTextHash = function() {
        document.getElementById('hashTextInput').value = '';
        document.getElementById('md5Result').value = '';
        document.getElementById('sha1Result').value = '';
        document.getElementById('sha256Result').value = '';
        document.getElementById('sha512Result').value = '';
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

    window.downloadAllHashes = function() {
        var md5 = document.getElementById('md5Result').value;
        var sha1 = document.getElementById('sha1Result').value;
        var sha256 = document.getElementById('sha256Result').value;
        var sha512 = document.getElementById('sha512Result').value;
        
        if (!md5 && !sha1 && !sha256 && !sha512) {
            showToast('No hashes to download', 'error');
            return;
        }
        
        var content = '=== HASH RESULTS ===\nGenerated: ' + new Date().toLocaleString() + '\n---\nMD5: ' + (md5 || 'N/A') + '\nSHA-1: ' + (sha1 || 'N/A') + '\nSHA-256: ' + (sha256 || 'N/A') + '\nSHA-512: ' + (sha512 || 'N/A') + '\n=== END ===';
        
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
            return;
        }
        
        var reader = new FileReader();
        reader.onload = function(e) {
            var data = e.target.result;
            var text = new TextDecoder().decode(data);
            
            // Generate MD5
            var md5Hash = generateMD5(text);
            document.getElementById('fileMd5Result').value = md5Hash;
            
            // Generate SHA-1 using Web Crypto API
            var uint8Array = new Uint8Array(data);
            crypto.subtle.digest('SHA-1', uint8Array)
                .then(function(hashBuffer) {
                    var hashArray = Array.from(new Uint8Array(hashBuffer));
                    var hashHex = hashArray.map(function(b) { 
                        return b.toString(16).padStart(2, '0'); 
                    }).join('');
                    document.getElementById('fileSha1Result').value = hashHex;
                })
                .catch(function(err) {
                    showToast('Error generating SHA-1: ' + err.message, 'error');
                });
            
            // Generate SHA-256
            var sha256Hash = generateSHA256(text);
            document.getElementById('fileSha256Result').value = sha256Hash;
            
            // Generate SHA-512
            var sha512Hash = generateSHA512(text);
            document.getElementById('fileSha512Result').value = sha512Hash;
            
            showToast('File hashes generated successfully!', 'success');
        };
        reader.readAsArrayBuffer(currentFileData);
    };

    window.clearFileHash = function() {
        currentFileData = null;
        document.getElementById('fileInput').value = '';
        document.getElementById('fileInfo').style.display = 'none';
        document.getElementById('fileMd5Result').value = '';
        document.getElementById('fileSha1Result').value = '';
        document.getElementById('fileSha256Result').value = '';
        document.getElementById('fileSha512Result').value = '';
        showToast('Cleared', 'info');
    };

    window.downloadFileHashes = function() {
        var md5 = document.getElementById('fileMd5Result').value;
        var sha1 = document.getElementById('fileSha1Result').value;
        var sha256 = document.getElementById('fileSha256Result').value;
        var sha512 = document.getElementById('fileSha512Result').value;
        
        if (!md5 && !sha1 && !sha256 && !sha512) {
            showToast('No file hashes to download', 'error');
            return;
        }
        
        var fileName = document.getElementById('fileName').textContent || 'file';
        var content = '=== FILE HASH RESULTS ===\nFile: ' + fileName + '\nGenerated: ' + new Date().toLocaleString() + '\n---\nMD5: ' + (md5 || 'N/A') + '\nSHA-1: ' + (sha1 || 'N/A') + '\nSHA-256: ' + (sha256 || 'N/A') + '\nSHA-512: ' + (sha512 || 'N/A') + '\n=== END ===';
        
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
        sha512: {}
    };

    // Build rainbow table on load
    function buildRainbowTable() {
        console.log('Building rainbow table with ' + commonWords.length + ' words...');
        
        // MD5 hashes (synchronous)
        for (var i = 0; i < commonWords.length; i++) {
            var word = commonWords[i];
            try {
                var md5Hash = generateMD5(word);
                rainbowTable.md5[md5Hash] = word;
            } catch(e) {
                // Skip errors
            }
        }
        
        // SHA-256 hashes (synchronous)
        for (var i = 0; i < commonWords.length; i++) {
            var word = commonWords[i];
            try {
                var sha256Hash = generateSHA256(word);
                rainbowTable.sha256[sha256Hash] = word;
            } catch(e) {
                // Skip errors
            }
        }
        
        // SHA-512 hashes (synchronous)
        for (var i = 0; i < commonWords.length; i++) {
            var word = commonWords[i];
            try {
                var sha512Hash = generateSHA512(word);
                rainbowTable.sha512[sha512Hash] = word;
            } catch(e) {
                // Skip errors
            }
        }
        
        console.log('Rainbow table built successfully!');
        console.log('MD5 entries: ' + Object.keys(rainbowTable.md5).length);
        console.log('SHA-256 entries: ' + Object.keys(rainbowTable.sha256).length);
        console.log('SHA-512 entries: ' + Object.keys(rainbowTable.sha512).length);
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