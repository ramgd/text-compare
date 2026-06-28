// State Variables
let backgroundImage = null;
let watermarkLogo = null;
let isDragging = false;
let activeLayerIndex = 0;

// Initial Layers
let layers = [{
    id: Date.now(),
    name: "Layer 1",
    text: "Enter Your Text",
    x: 540,
    y: 180,
    fontSize: 50,
    color: "#ffffff",
    bold: false,
    italic: false,
    locked: false,
    rotation: 0,
    shadow: false,
    outline: false
}];

// Initialize on page load
document.addEventListener("DOMContentLoaded", function() {
    changePreset();
    renderLayers();
    updateCanvas();
    setupCanvasEvents();
    setupKeyboardShortcuts();
});

// Canvas Event Handlers
function setupCanvasEvents() {
    let canvas = document.getElementById("imageCanvas");
    if (!canvas) return;

    canvas.addEventListener("mousedown", function(e) {
        if (activeLayerIndex >= layers.length) return;
        if (layers[activeLayerIndex].locked) {
            showToast("Layer is locked!", "error");
            return;
        }
        isDragging = true;
        canvas.style.cursor = 'grabbing';
    });

    canvas.addEventListener("mouseup", function() {
        isDragging = false;
        canvas.style.cursor = 'grab';
    });

    canvas.addEventListener("mouseleave", function() {
        isDragging = false;
        canvas.style.cursor = 'default';
    });

    canvas.addEventListener("mousemove", function(e) {
        if (!isDragging || activeLayerIndex >= layers.length) return;
        if (layers[activeLayerIndex].locked) return;

        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        let x = (e.clientX - rect.left) * scaleX;
        let y = (e.clientY - rect.top) * scaleY;

        x = Math.max(0, Math.min(canvas.width, x));
        y = Math.max(0, Math.min(canvas.height, y));

        layers[activeLayerIndex].x = x;
        layers[activeLayerIndex].y = y;

        updateCanvas();
    });

    // Touch events for mobile
    canvas.addEventListener("touchstart", function(e) {
        e.preventDefault();
        if (activeLayerIndex >= layers.length) return;
        if (layers[activeLayerIndex].locked) {
            showToast("Layer is locked!", "error");
            return;
        }
        isDragging = true;
        handleTouchMove(e);
    }, { passive: false });

    canvas.addEventListener("touchmove", function(e) {
        e.preventDefault();
        if (!isDragging || activeLayerIndex >= layers.length) return;
        if (layers[activeLayerIndex].locked) return;
        handleTouchMove(e);
    }, { passive: false });

    canvas.addEventListener("touchend", function() {
        isDragging = false;
    });

    function handleTouchMove(e) {
        const touch = e.touches[0];
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;

        let x = (touch.clientX - rect.left) * scaleX;
        let y = (touch.clientY - rect.top) * scaleY;

        x = Math.max(0, Math.min(canvas.width, x));
        y = Math.max(0, Math.min(canvas.height, y));

        layers[activeLayerIndex].x = x;
        layers[activeLayerIndex].y = y;

        updateCanvas();
    }
}

// Keyboard Shortcuts
function setupKeyboardShortcuts() {
    document.addEventListener("keydown", function(e) {
        // Delete key to remove layer
        if (e.key === 'Delete' || e.key === 'Backspace') {
            if (document.activeElement.tagName !== 'INPUT' && 
                document.activeElement.tagName !== 'TEXTAREA') {
                if (layers.length > 1) {
                    e.preventDefault();
                    deleteLayer(activeLayerIndex);
                }
            }
        }
        
        // Arrow keys to move text
        if (e.key.startsWith('Arrow') && 
            document.activeElement.tagName !== 'INPUT' && 
            document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            const directions = {
                'ArrowUp': 'up',
                'ArrowDown': 'down',
                'ArrowLeft': 'left',
                'ArrowRight': 'right'
            };
            if (directions[e.key]) {
                moveText(directions[e.key]);
            }
        }
    });
}

// Preset Management
function changePreset() {
    let preset = document.getElementById("canvasPreset").value;
    let canvas = document.getElementById("imageCanvas");

    const presets = {
        'instagram': { width: 1080, height: 1080 },
        'linkedin': { width: 1584, height: 396 },
        'facebook': { width: 820, height: 312 },
        'story': { width: 1080, height: 1920 }
    };

    let dimensions = presets[preset] || presets['instagram'];
    canvas.width = dimensions.width;
    canvas.height = dimensions.height;

    // Center existing layers
    layers.forEach(layer => {
        layer.x = canvas.width / 2;
        layer.y = canvas.height / 3;
    });

    updateCanvas();
    showToast(`Switched to ${preset} template`, "info");
}

// Main Canvas Update Function
function updateCanvas() {
    let canvas = document.getElementById("imageCanvas");
    if (!canvas) return;

    let ctx = canvas.getContext("2d");
    let bgColor = document.getElementById("bgColor").value;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw background
    if (backgroundImage) {
        ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);
    } else {
        let gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        gradient.addColorStop(0, bgColor);
        gradient.addColorStop(1, lightenColor(bgColor, 40));
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    }

    // Draw all layers
    layers.forEach((layer, index) => {
        if (!layer.text || layer.text.trim() === '') return;

        ctx.save();
        ctx.translate(layer.x, layer.y);
        ctx.rotate(layer.rotation * Math.PI / 180);

        // Build font style
        let style = "";
        if (layer.italic) style += "italic ";
        if (layer.bold) style += "bold ";
        style += layer.fontSize + "px " + document.getElementById("fontFamily").value;

        ctx.font = style;
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";

        // Apply shadow
        if (layer.shadow) {
            ctx.shadowColor = "rgba(0,0,0,0.5)";
            ctx.shadowBlur = 15;
            ctx.shadowOffsetX = 3;
            ctx.shadowOffsetY = 3;
        }

        // Draw text with wrapping
        let maxWidth = canvas.width - 80;
        let lines = getWrappedLines(ctx, layer.text, maxWidth);
        let lineHeight = layer.fontSize * 1.2;
        let totalHeight = lines.length * lineHeight;
        let startY = -(totalHeight / 2) + (lineHeight / 2);

        lines.forEach((line, i) => {
            let y = startY + (i * lineHeight);

            // Outline (only draw if outline is enabled)
            if (layer.outline) {
                ctx.shadowColor = "transparent";
                ctx.lineWidth = 4;
                ctx.strokeStyle = "#000000";
                ctx.strokeText(line, 0, y);
            }

            // Fill text
            ctx.fillStyle = layer.color;
            ctx.shadowColor = layer.shadow ? "rgba(0,0,0,0.5)" : "transparent";
            ctx.fillText(line, 0, y);
        });

        ctx.restore();
    });

    // Draw watermark
    if (watermarkLogo) {
        ctx.globalAlpha = 0.4;
        let size = Math.min(canvas.width, canvas.height) * 0.1;
        let x = canvas.width - size - 20;
        let y = canvas.height - size - 20;
        ctx.drawImage(watermarkLogo, x, y, size, size);
        ctx.globalAlpha = 1;
    }
}

// Text Wrapping Function
function getWrappedLines(ctx, text, maxWidth) {
    let paragraphs = text.split("\n");
    let lines = [];

    paragraphs.forEach(paragraph => {
        if (paragraph.trim() === '') {
            lines.push('');
            return;
        }

        let words = paragraph.split(" ");
        let line = "";

        words.forEach(word => {
            let testLine = line + word + " ";
            if (ctx.measureText(testLine).width > maxWidth && line.length > 0) {
                lines.push(line.trim());
                line = word + " ";
            } else {
                line = testLine;
            }
        });

        if (line.trim().length > 0) {
            lines.push(line.trim());
        }
    });

    return lines;
}

// Sync Active Layer with UI Controls
function syncActiveLayer() {
    if (activeLayerIndex >= layers.length) return;
    let layer = layers[activeLayerIndex];

    layer.text = document.getElementById("textInput").value;
    layer.fontSize = parseInt(document.getElementById("fontSize").value) || 50;
    layer.color = document.getElementById("textColor").value;
    layer.bold = document.getElementById("boldText").checked;
    layer.italic = document.getElementById("italicText").checked;
    layer.shadow = document.getElementById("shadowText").checked;
    layer.outline = document.getElementById("outlineText").checked;

    updateCanvas();
}

// File Upload Handlers
function loadBackground(event) {
    let file = event.target.files[0];
    if (!file) return;

    let reader = new FileReader();
    reader.onload = function(e) {
        backgroundImage = new Image();
        backgroundImage.onload = function() {
            document.getElementById('removeBgBtn').style.display = 'inline-block';
            updateCanvas();
            showToast("Background loaded successfully", "success");
        };
        backgroundImage.onerror = function() {
            showToast("Failed to load background image", "error");
        };
        backgroundImage.src = e.target.result;
    };
    reader.onerror = function() {
        showToast("Failed to read file", "error");
    };
    reader.readAsDataURL(file);
}

function removeBackground() {
    backgroundImage = null;
    document.getElementById('bgImageInput').value = '';
    document.getElementById('removeBgBtn').style.display = 'none';
    updateCanvas();
    showToast("Background removed", "info");
}

function loadLogo(event) {
    let file = event.target.files[0];
    if (!file) return;

    let reader = new FileReader();
    reader.onload = function(e) {
        watermarkLogo = new Image();
        watermarkLogo.onload = function() {
            document.getElementById('removeLogoBtn').style.display = 'inline-block';
            updateCanvas();
            showToast("Logo loaded successfully", "success");
        };
        watermarkLogo.onerror = function() {
            showToast("Failed to load logo image", "error");
        };
        watermarkLogo.src = e.target.result;
    };
    reader.onerror = function() {
        showToast("Failed to read file", "error");
    };
    reader.readAsDataURL(file);
}

function removeLogo() {
    watermarkLogo = null;
    document.getElementById('logoImageInput').value = '';
    document.getElementById('removeLogoBtn').style.display = 'none';
    updateCanvas();
    showToast("Logo removed", "info");
}

// Download Functions
function downloadPNG() {
    let canvas = document.getElementById("imageCanvas");
    let link = document.createElement("a");
    link.download = "text-image.png";
    link.href = canvas.toDataURL("image/png");
    link.click();
    showToast("PNG Downloaded successfully!", "success");
}

function downloadJPG() {
    let canvas = document.getElementById("imageCanvas");
    let link = document.createElement("a");
    link.download = "text-image.jpg";
    link.href = canvas.toDataURL("image/jpeg", 1.0);
    link.click();
    showToast("JPG Downloaded successfully!", "success");
}

// Clear Canvas
function clearCanvas() {
    if (!confirm("Are you sure you want to clear everything?")) return;

    document.getElementById("textInput").value = "";
    backgroundImage = null;
    watermarkLogo = null;
    
    // Reset file inputs
    document.getElementById('bgImageInput').value = '';
    document.getElementById('logoImageInput').value = '';
    document.getElementById('removeBgBtn').style.display = 'none';
    document.getElementById('removeLogoBtn').style.display = 'none';

    let canvas = document.getElementById("imageCanvas");
    layers = [{
        id: Date.now(),
        name: "Layer 1",
        text: "Enter Your Text",
        x: canvas.width / 2,
        y: canvas.height / 3,
        fontSize: 50,
        color: "#ffffff",
        bold: false,
        italic: false,
        locked: false,
        rotation: 0,
        shadow: false,
        outline: false
    }];

    activeLayerIndex = 0;
    renderLayers();
    updateCanvas();
    showToast("Canvas cleared", "info");
}

// Utility Functions
function lightenColor(color, percent) {
    let num = parseInt(color.replace("#", ""), 16);
    let amt = Math.round(2.55 * percent);
    let R = (num >> 16) + amt;
    let G = ((num >> 8) & 255) + amt;
    let B = (num & 255) + amt;
    return "#" + (0x1000000 + (R < 255 ? R : 255) * 0x10000 + 
                  (G < 255 ? G : 255) * 0x100 + 
                  (B < 255 ? B : 255)).toString(16).slice(1);
}

// Layer Management Functions
function moveText(direction) {
    if (activeLayerIndex >= layers.length) return;
    let layer = layers[activeLayerIndex];

    if (layer.locked) {
        showToast("Layer is locked!", "error");
        return;
    }

    let step = 20;
    let canvas = document.getElementById("imageCanvas");
    
    switch (direction) {
        case "left": layer.x -= step; break;
        case "right": layer.x += step; break;
        case "up": layer.y -= step; break;
        case "down": layer.y += step; break;
    }

    layer.x = Math.max(0, Math.min(canvas.width, layer.x));
    layer.y = Math.max(0, Math.min(canvas.height, layer.y));

    updateCanvas();
}

function renderLayers() {
    let html = '';
    layers.forEach((layer, index) => {
        let isActive = index === activeLayerIndex;
        html += `
            <div class="layer-row ${isActive ? 'active' : ''}">
                <button onclick="selectLayer(${index})">
                    ${layer.name} ${isActive ? '◀' : ''}
                </button>
                <button onclick="duplicateLayer(${index})" title="Duplicate">📄</button>
                <button onclick="renameLayer(${index})" title="Rename">✏️</button>
                <button onclick="toggleLock(${index})" title="Lock/Unlock">
                    ${layer.locked ? '🔒' : '🔓'}
                </button>
                <button onclick="bringForward(${index})" title="Bring Forward">⬆</button>
                <button onclick="sendBackward(${index})" title="Send Backward">⬇</button>
                <button onclick="deleteLayer(${index})" title="Delete">❌</button>
            </div>
        `;
    });

    document.getElementById("layersContainer").innerHTML = html;
}

function addLayer() {
    let canvas = document.getElementById("imageCanvas");
    let newLayer = {
        id: Date.now(),
        name: "Layer " + (layers.length + 1),
        text: "New Layer",
        x: canvas.width / 2,
        y: canvas.height / 3,
        fontSize: 50,
        color: "#ffffff",
        bold: false,
        italic: false,
        locked: false,
        rotation: 0,
        shadow: false,
        outline: false
    };
    
    // Offset new layer slightly
    if (layers.length > 0) {
        let lastLayer = layers[layers.length - 1];
        newLayer.x = lastLayer.x + 30;
        newLayer.y = lastLayer.y + 30;
    }
    
    layers.push(newLayer);
    activeLayerIndex = layers.length - 1;
    renderLayers();
    updateCanvas();
    showToast("Layer added successfully!", "success");
}

function selectLayer(index) {
    if (index >= layers.length) return;
    activeLayerIndex = index;
    let layer = layers[index];

    // Update controls with layer values
    document.getElementById("textInput").value = layer.text;
    document.getElementById("fontSize").value = layer.fontSize;
    document.getElementById("textColor").value = layer.color;
    document.getElementById("boldText").checked = layer.bold || false;
    document.getElementById("italicText").checked = layer.italic || false;
    document.getElementById("shadowText").checked = layer.shadow || false;
    document.getElementById("outlineText").checked = layer.outline || false;

    renderLayers();
    updateCanvas();
    showToast(`Selected: ${layer.name}`, "info");
}

function deleteLayer(index) {
    if (layers.length <= 1) {
        showToast("Cannot delete the last layer!", "error");
        return;
    }

    if (!confirm(`Delete "${layers[index].name}"?`)) return;

    layers.splice(index, 1);
    if (activeLayerIndex >= layers.length) {
        activeLayerIndex = layers.length - 1;
    }
    if (activeLayerIndex === index && index > 0) {
        activeLayerIndex = index - 1;
    }

    renderLayers();
    updateCanvas();
    showToast("Layer deleted", "info");
}

function duplicateLayer(index) {
    let layer = JSON.parse(JSON.stringify(layers[index]));
    layer.id = Date.now();
    layer.name += " Copy";
    layer.x += 30;
    layer.y += 30;

    layers.push(layer);
    activeLayerIndex = layers.length - 1;
    renderLayers();
    updateCanvas();
    showToast("Layer duplicated", "success");
}

function renameLayer(index) {
    let name = prompt("Enter new layer name:", layers[index].name);
    if (name && name.trim()) {
        layers[index].name = name.trim();
        renderLayers();
        showToast("Layer renamed", "success");
    }
}

function toggleLock(index) {
    layers[index].locked = !layers[index].locked;
    renderLayers();
    showToast(layers[index].locked ? "Layer locked" : "Layer unlocked", "info");
}

function bringForward(index) {
    if (index >= layers.length - 1) return;
    [layers[index], layers[index + 1]] = [layers[index + 1], layers[index]];
    if (activeLayerIndex === index) activeLayerIndex = index + 1;
    else if (activeLayerIndex === index + 1) activeLayerIndex = index;
    renderLayers();
    updateCanvas();
}

function sendBackward(index) {
    if (index <= 0) return;
    [layers[index], layers[index - 1]] = [layers[index - 1], layers[index]];
    if (activeLayerIndex === index) activeLayerIndex = index - 1;
    else if (activeLayerIndex === index - 1) activeLayerIndex = index;
    renderLayers();
    updateCanvas();
}

function rotateLayer(degrees) {
    if (activeLayerIndex >= layers.length) return;
    if (layers[activeLayerIndex].locked) {
        showToast("Layer is locked!", "error");
        return;
    }
    layers[activeLayerIndex].rotation += degrees;
    updateCanvas();
    showToast(`Rotated ${degrees}°`, "info");
}

function resetRotation() {
    if (activeLayerIndex >= layers.length) return;
    if (layers[activeLayerIndex].locked) {
        showToast("Layer is locked!", "error");
        return;
    }
    layers[activeLayerIndex].rotation = 0;
    updateCanvas();
    showToast("Rotation reset", "info");
}

// Toast Notification System
function showToast(message, type = "info") {
    let toast = document.getElementById("toast");
    if (!toast) {
        toast = document.createElement("div");
        toast.id = "toast";
        toast.className = "toast";
        document.body.appendChild(toast);
    }

    toast.textContent = message;
    toast.className = "toast show " + type;

    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(() => {
        toast.className = "toast";
    }, 3000);
}