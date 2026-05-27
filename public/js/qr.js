(function () {
    'use strict';

    let qrCode = null;
    let uploadedLogoBase64 = '';

    document.addEventListener('DOMContentLoaded', function () {
        const textInput = document.getElementById('qr_text');
        const sizeInput = document.getElementById('qr_size');
        const marginInput = document.getElementById('qr_margin');
        const qrColorInput = document.getElementById('qr_color');
        const bgColorInput = document.getElementById('bg_color');
        const logoInput = document.getElementById('qr_logo');

        const generateBtn = document.getElementById('generateQrBtn');
        const copyBtn = document.getElementById('copyQrTextBtn');
        const downloadSvgBtn = document.getElementById('downloadSvgBtn');
        const downloadPngBtn = document.getElementById('downloadPngBtn');
        const clearBtn = document.getElementById('clearQrForm');

        const qrPreview = document.getElementById('qrPreview');
        const qrResultBox = document.getElementById('qrResultBox');
        const encodedText = document.getElementById('encodedText');
        const textError = document.getElementById('textError');
        const copyMessage = document.getElementById('copyMessage');

        // Safe guard: agar QR page elements hi nahi hain to file exit
        if (
            !textInput || !sizeInput || !marginInput || !qrColorInput || !bgColorInput ||
            !logoInput || !generateBtn || !copyBtn || !downloadSvgBtn || !downloadPngBtn ||
            !clearBtn || !qrPreview || !qrResultBox || !encodedText || !textError || !copyMessage
        ) {
            return;
        }

        function showMessage(message, success) {
            copyMessage.textContent = message;
            copyMessage.style.display = 'block';
            copyMessage.className = success ? 'copy-message success' : 'copy-message error-msg';

            setTimeout(function () {
                copyMessage.style.display = 'none';
            }, 2200);
        }

        function scrollToResult() {
            if (qrResultBox.classList.contains('hidden')) {
                return;
            }

            setTimeout(function () {
                qrResultBox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                qrResultBox.classList.add('qr-highlight');

                setTimeout(function () {
                    qrResultBox.classList.remove('qr-highlight');
                }, 2200);
            }, 200);
        }

        function resetPreview() {
            qrPreview.innerHTML = '';
            qrCode = null;
        }

        function isLibraryLoaded() {
            return typeof QRCodeStyling !== 'undefined';
        }

        function buildQrOptions() {
            const text = textInput.value.trim();
            const size = parseInt(sizeInput.value, 10) || 250;
            const margin = parseInt(marginInput.value, 10) || 10;
            const qrColor = qrColorInput.value || '#000000';
            const bgColor = bgColorInput.value || '#ffffff';

            return {
                width: size,
                height: size,
                type: 'svg',
                data: text,
                margin: margin,
                image: uploadedLogoBase64 || '',
                dotsOptions: {
                    color: qrColor,
                    type: 'rounded'
                },
                cornersSquareOptions: {
                    color: qrColor,
                    type: 'extra-rounded'
                },
                cornersDotOptions: {
                    color: qrColor,
                    type: 'dot'
                },
                backgroundOptions: {
                    color: bgColor
                },
                imageOptions: {
                    crossOrigin: 'anonymous',
                    margin: 6,
                    imageSize: 0.22,
                    hideBackgroundDots: true
                }
            };
        }

        function createQrInstance() {
            const text = textInput.value.trim();

            if (text === '') {
                textError.textContent = 'Please enter text or URL';
                qrResultBox.classList.add('hidden');
                resetPreview();
                return false;
            }

            if (!isLibraryLoaded()) {
                textError.textContent = 'QR library failed to load. Please refresh the page once.';
                qrResultBox.classList.add('hidden');
                resetPreview();
                return false;
            }

            textError.textContent = '';
            encodedText.textContent = text;
            qrPreview.innerHTML = '';

            try {
                qrCode = new QRCodeStyling(buildQrOptions());
                qrCode.append(qrPreview);

                qrResultBox.classList.remove('hidden');
                scrollToResult();
                return true;
            } catch (error) {
                console.error('QR generation failed:', error);
                textError.textContent = 'QR generate nahi ho pa raha. Please try again.';
                qrResultBox.classList.add('hidden');
                resetPreview();
                return false;
            }
        }

        function updateQrIfVisible() {
            if (!qrCode || textInput.value.trim() === '') {
                return;
            }
            createQrInstance();
        }

        function readLogoFile(file, callback) {
            const reader = new FileReader();

            reader.onload = function (e) {
                callback(e.target.result);
            };

            reader.onerror = function () {
                showMessage('Logo read failed', false);
            };

            reader.readAsDataURL(file);
        }

        generateBtn.addEventListener('click', function () {
            createQrInstance();
        });

        copyBtn.addEventListener('click', function () {
            const text = textInput.value.trim();

            if (text === '') {
                showMessage('No text to copy', false);
                return;
            }

            if (!navigator.clipboard) {
                showMessage('Clipboard not supported', false);
                return;
            }

            navigator.clipboard.writeText(text).then(function () {
                showMessage('Text copied successfully', true);
            }).catch(function () {
                showMessage('Copy failed', false);
            });
        });

        downloadSvgBtn.addEventListener('click', function () {
            if (!qrCode) {
                showMessage('Please generate QR first', false);
                return;
            }

            try {
                qrCode.download({
                    name: 'qr-code',
                    extension: 'svg'
                });
            } catch (error) {
                console.error(error);
                showMessage('SVG download failed', false);
            }
        });

        downloadPngBtn.addEventListener('click', function () {
            if (!qrCode) {
                showMessage('Please generate QR first', false);
                return;
            }

            try {
                qrCode.download({
                    name: 'qr-code',
                    extension: 'png'
                });
            } catch (error) {
                console.error(error);
                showMessage('PNG download failed', false);
            }
        });

        clearBtn.addEventListener('click', function () {
            textInput.value = '';
            sizeInput.value = '250';
            marginInput.value = '10';
            qrColorInput.value = '#000000';
            bgColorInput.value = '#ffffff';
            logoInput.value = '';
            uploadedLogoBase64 = '';
            encodedText.textContent = '';
            textError.textContent = '';
            copyMessage.style.display = 'none';
            qrResultBox.classList.add('hidden');
            resetPreview();
        });

        logoInput.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];

            if (!file) {
                uploadedLogoBase64 = '';
                updateQrIfVisible();
                return;
            }

            if (!file.type.startsWith('image/')) {
                showMessage('Please upload a valid image file', false);
                logoInput.value = '';
                uploadedLogoBase64 = '';
                return;
            }

            readLogoFile(file, function (base64Image) {
                uploadedLogoBase64 = base64Image;
                updateQrIfVisible();
            });
        });

        qrColorInput.addEventListener('change', updateQrIfVisible);
        bgColorInput.addEventListener('change', updateQrIfVisible);
        sizeInput.addEventListener('change', updateQrIfVisible);
        marginInput.addEventListener('change', updateQrIfVisible);
    });
})();