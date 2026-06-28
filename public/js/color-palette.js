// ============================================
// COLOR PALETTE GENERATOR
// ============================================

var currentPalette = [];
var savedPalettes = JSON.parse(localStorage.getItem('colorPalettes')) || [];

// ==================== COLOR UTILITY FUNCTIONS ====================

function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

function rgbToHex(r, g, b) {
    return '#' + [r, g, b].map(function(c) {
        var hex = c.toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    }).join('');
}

function rgbToHsl(r, g, b) {
    r /= 255, g /= 255, b /= 255;
    var max = Math.max(r, g, b),
        min = Math.min(r, g, b);
    var h, s, l = (max + min) / 2;
    if (max === min) {
        h = s = 0;
    } else {
        var d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch (max) {
            case r:
                h = ((g - b) / d + (g < b ? 6 : 0)) / 6;
                break;
            case g:
                h = ((b - r) / d + 2) / 6;
                break;
            case b:
                h = ((r - g) / d + 4) / 6;
                break;
        }
    }
    return {
        h: Math.round(h * 360),
        s: Math.round(s * 100),
        l: Math.round(l * 100)
    };
}

function hslToRgb(h, s, l) {
    h /= 360;
    s /= 100;
    l /= 100;
    var r, g, b;
    if (s === 0) {
        r = g = b = l;
    } else {
        function hue2rgb(p, q, t) {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1 / 6) return p + (q - p) * 6 * t;
            if (t < 1 / 2) return q;
            if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
            return p;
        }
        var q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        var p = 2 * l - q;
        r = hue2rgb(p, q, h + 1 / 3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1 / 3);
    }
    return {
        r: Math.round(r * 255),
        g: Math.round(g * 255),
        b: Math.round(b * 255)
    };
}

function randomColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0');
}

function lighten(hex, percent) {
    var rgb = hexToRgb(hex);
    var r = Math.min(255, rgb.r + (255 - rgb.r) * (percent / 100));
    var g = Math.min(255, rgb.g + (255 - rgb.g) * (percent / 100));
    var b = Math.min(255, rgb.b + (255 - rgb.b) * (percent / 100));
    return rgbToHex(Math.round(r), Math.round(g), Math.round(b));
}

function darken(hex, percent) {
    var rgb = hexToRgb(hex);
    var r = Math.max(0, rgb.r - rgb.r * (percent / 100));
    var g = Math.max(0, rgb.g - rgb.g * (percent / 100));
    var b = Math.max(0, rgb.b - rgb.b * (percent / 100));
    return rgbToHex(Math.round(r), Math.round(g), Math.round(b));
}

function getContrastRatio(hex1, hex2) {
    function luminance(hex) {
        var rgb = hexToRgb(hex);
        var rsrgb = rgb.r / 255;
        var gsrgb = rgb.g / 255;
        var bsrgb = rgb.b / 255;
        var r = rsrgb <= 0.03928 ? rsrgb / 12.92 : Math.pow((rsrgb + 0.055) / 1.055, 2.4);
        var g = gsrgb <= 0.03928 ? gsrgb / 12.92 : Math.pow((gsrgb + 0.055) / 1.055, 2.4);
        var b = bsrgb <= 0.03928 ? bsrgb / 12.92 : Math.pow((bsrgb + 0.055) / 1.055, 2.4);
        return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    }
    var l1 = luminance(hex1);
    var l2 = luminance(hex2);
    var ratio = (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);
    return Math.round(ratio * 100) / 100;
}

// ==================== PALETTE GENERATORS ====================

function generateMonochromatic(baseColor, count) {
    var colors = [];
    var rgb = hexToRgb(baseColor);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    
    for (var i = 0; i < count; i++) {
        var l = Math.max(10, Math.min(90, hsl.l + (i - (count - 1) / 2) * 20));
        var newRgb = hslToRgb(hsl.h, hsl.s, l);
        colors.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
    }
    return colors;
}

function generateComplementary(baseColor, count) {
    var colors = [baseColor];
    var rgb = hexToRgb(baseColor);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    
    var compHue = (hsl.h + 180) % 360;
    var compRgb = hslToRgb(compHue, hsl.s, hsl.l);
    colors.push(rgbToHex(compRgb.r, compRgb.g, compRgb.b));
    
    // If we need more colors, add variations
    while (colors.length < count) {
        var variation = colors[colors.length - 1];
        var varRgb = hexToRgb(variation);
        var varHsl = rgbToHsl(varRgb.r, varRgb.g, varRgb.b);
        var newRgb = hslToRgb(varHsl.h + 30, Math.min(100, varHsl.s + 10), Math.min(90, varHsl.l + 10));
        colors.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
    }
    return colors;
}

function generateTriadic(baseColor, count) {
    var colors = [baseColor];
    var rgb = hexToRgb(baseColor);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    
    var hues = [0, 120, 240];
    for (var i = 1; i < Math.min(count, 3); i++) {
        var newHue = (hsl.h + hues[i]) % 360;
        var newRgb = hslToRgb(newHue, hsl.s, hsl.l);
        colors.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
    }
    
    while (colors.length < count) {
        var last = colors[colors.length - 1];
        var lastRgb = hexToRgb(last);
        var lastHsl = rgbToHsl(lastRgb.r, lastRgb.g, lastRgb.b);
        var newRgb = hslToRgb(lastHsl.h + 20, Math.min(100, lastHsl.s + 10), Math.min(90, lastHsl.l + 10));
        colors.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
    }
    return colors;
}

function generateTetradic(baseColor, count) {
    var colors = [baseColor];
    var rgb = hexToRgb(baseColor);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    
    var hues = [0, 90, 180, 270];
    for (var i = 1; i < Math.min(count, 4); i++) {
        var newHue = (hsl.h + hues[i]) % 360;
        var newRgb = hslToRgb(newHue, hsl.s, hsl.l);
        colors.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
    }
    
    while (colors.length < count) {
        var last = colors[colors.length - 1];
        var lastRgb = hexToRgb(last);
        var lastHsl = rgbToHsl(lastRgb.r, lastRgb.g, lastRgb.b);
        var newRgb = hslToRgb(lastHsl.h + 15, Math.min(100, lastHsl.s + 10), Math.min(90, lastHsl.l + 10));
        colors.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
    }
    return colors;
}

function generateAnalogous(baseColor, count) {
    var colors = [baseColor];
    var rgb = hexToRgb(baseColor);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    
    var step = 30;
    var start = -((count - 1) / 2) * step;
    for (var i = 1; i < count; i++) {
        var newHue = (hsl.h + start + i * step) % 360;
        var newRgb = hslToRgb(newHue, Math.min(100, hsl.s + 10), Math.min(90, hsl.l + 10));
        colors.push(rgbToHex(newRgb.r, newRgb.g, newRgb.b));
    }
    return colors;
}

function generateRandomPalette(count) {
    var colors = [];
    for (var i = 0; i < count; i++) {
        colors.push(randomColor());
    }
    return colors;
}

// ==================== MAIN FUNCTIONS ====================

window.generatePalette = function() {
    var type = document.getElementById('paletteType').value;
    var count = parseInt(document.getElementById('colorCount').value);
    var baseColor = document.getElementById('baseColor').value;
    
    var colors = [];
    switch(type) {
        case 'monochromatic':
            colors = generateMonochromatic(baseColor, count);
            break;
        case 'complementary':
            colors = generateComplementary(baseColor, count);
            break;
        case 'triadic':
            colors = generateTriadic(baseColor, count);
            break;
        case 'tetradic':
            colors = generateTetradic(baseColor, count);
            break;
        case 'analogous':
            colors = generateAnalogous(baseColor, count);
            break;
        case 'random':
            colors = generateRandomPalette(count);
            break;
        default:
            colors = generateRandomPalette(count);
    }
    
    currentPalette = colors;
    renderPalette(colors);
};

window.randomPalette = function() {
    var count = parseInt(document.getElementById('colorCount').value);
    var colors = generateRandomPalette(count);
    document.getElementById('baseColor').value = colors[0];
    currentPalette = colors;
    renderPalette(colors);
    showToast('Random palette generated!', 'success');
};

function renderPalette(colors) {
    var grid = document.getElementById('paletteGrid');
    grid.innerHTML = '';
    
    colors.forEach(function(color, index) {
        var rgb = hexToRgb(color);
        var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
        var isLight = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000 > 128;
        
        var card = document.createElement('div');
        card.className = 'color-card';
        card.innerHTML = `
            <div class="color-preview" style="background: ${color};">
                <div class="color-info" style="color: ${isLight ? '#000' : '#fff'};">
                    <span class="hex">${color}</span>
                    <span class="rgb">RGB(${rgb.r}, ${rgb.g}, ${rgb.b})</span>
                    <div class="color-actions">
                        <button onclick="copyColor('${color}')" title="Copy HEX">📋 HEX</button>
                        <button onclick="copyRgb('${color}')" title="Copy RGB">📋 RGB</button>
                        <button onclick="copyHsl('${color}')" title="Copy HSL">📋 HSL</button>
                        <button onclick="showColorDetails('${color}')" title="Details">🔍</button>
                    </div>
                </div>
                <div class="copy-badge">Click to copy</div>
            </div>
        `;
        card.onclick = function() {
            copyColor(color);
        };
        grid.appendChild(card);
    });
    
    // Update grid columns based on count
    var count = colors.length;
    if (count <= 3) {
        grid.style.gridTemplateColumns = 'repeat(' + count + ', 1fr)';
    } else if (count <= 4) {
        grid.style.gridTemplateColumns = 'repeat(4, 1fr)';
    } else if (count <= 6) {
        grid.style.gridTemplateColumns = 'repeat(3, 1fr)';
    } else {
        grid.style.gridTemplateColumns = 'repeat(4, 1fr)';
    }
}

// ==================== COLOR ACTIONS ====================

window.copyColor = function(color) {
    navigator.clipboard.writeText(color).then(function() {
        showToast('Copied: ' + color, 'success');
    }).catch(function() {
        fallbackCopy(color);
    });
};

window.copyRgb = function(color) {
    var rgb = hexToRgb(color);
    var rgbText = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
    navigator.clipboard.writeText(rgbText).then(function() {
        showToast('Copied: ' + rgbText, 'success');
    }).catch(function() {
        fallbackCopy(rgbText);
    });
};

window.copyHsl = function(color) {
    var rgb = hexToRgb(color);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    var hslText = 'hsl(' + hsl.h + ', ' + hsl.s + '%, ' + hsl.l + '%)';
    navigator.clipboard.writeText(hslText).then(function() {
        showToast('Copied: ' + hslText, 'success');
    }).catch(function() {
        fallbackCopy(hslText);
    });
};

window.showColorDetails = function(color) {
    var rgb = hexToRgb(color);
    var hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    
    document.getElementById('modalColorPreview').style.background = color;
    document.getElementById('modalHex').textContent = color;
    document.getElementById('modalRgb').textContent = 'rgb(' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')';
    document.getElementById('modalHsl').textContent = 'hsl(' + hsl.h + ', ' + hsl.s + '%, ' + hsl.l + '%)';
    
    // Calculate contrast with white and black
    var contrastWhite = getContrastRatio(color, '#ffffff');
    var contrastBlack = getContrastRatio(color, '#000000');
    document.getElementById('modalContrast').textContent = 'White: ' + contrastWhite + ':1 | Black: ' + contrastBlack + ':1';
    
    document.getElementById('colorModal').style.display = 'flex';
};

window.closeModal = function() {
    document.getElementById('colorModal').style.display = 'none';
};

window.copyModalHex = function() {
    var hex = document.getElementById('modalHex').textContent;
    copyColor(hex);
};

window.copyModalRgb = function() {
    var rgb = document.getElementById('modalRgb').textContent;
    navigator.clipboard.writeText(rgb).then(function() {
        showToast('Copied: ' + rgb, 'success');
    });
};

window.copyModalHsl = function() {
    var hsl = document.getElementById('modalHsl').textContent;
    navigator.clipboard.writeText(hsl).then(function() {
        showToast('Copied: ' + hsl, 'success');
    });
};

// ==================== PALETTE ACTIONS ====================

window.copyAllColors = function() {
    if (currentPalette.length === 0) {
        showToast('No colors to copy', 'error');
        return;
    }
    var text = currentPalette.join(', ');
    navigator.clipboard.writeText(text).then(function() {
        showToast('All colors copied!', 'success');
    }).catch(function() {
        fallbackCopy(text);
    });
};

window.exportCSS = function() {
    if (currentPalette.length === 0) {
        showToast('No colors to export', 'error');
        return;
    }
    
    var css = ':root {\n';
    currentPalette.forEach(function(color, index) {
        css += '  --color-' + (index + 1) + ': ' + color + ';\n';
    });
    css += '}';
    
    var blob = new Blob([css], { type: 'text/css' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'palette.css';
    a.click();
    URL.revokeObjectURL(url);
    showToast('CSS exported!', 'success');
};

window.exportTailwind = function() {
    if (currentPalette.length === 0) {
        showToast('No colors to export', 'error');
        return;
    }
    
    var tailwind = 'module.exports = {\n  theme: {\n    extend: {\n      colors: {\n';
    currentPalette.forEach(function(color, index) {
        var name = 'color-' + (index + 1);
        tailwind += '        ' + name + ': "' + color + '",\n';
    });
    tailwind += '      }\n    }\n  }\n}';
    
    var blob = new Blob([tailwind], { type: 'text/plain' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'tailwind.config.js';
    a.click();
    URL.revokeObjectURL(url);
    showToast('Tailwind config exported!', 'success');
};

window.savePalette = function() {
    if (currentPalette.length === 0) {
        showToast('No colors to save', 'error');
        return;
    }
    
    var name = prompt('Enter a name for this palette:', 'My Palette');
    if (!name) return;
    
    var palette = {
        id: Date.now(),
        name: name,
        colors: currentPalette.slice(),
        date: new Date().toISOString()
    };
    
    savedPalettes.push(palette);
    localStorage.setItem('colorPalettes', JSON.stringify(savedPalettes));
    
    renderHistory();
    showToast('Palette saved!', 'success');
};

window.downloadPalette = function() {
    if (currentPalette.length === 0) {
        showToast('No colors to download', 'error');
        return;
    }
    
    var canvas = document.createElement('canvas');
    var size = 300;
    canvas.width = size * currentPalette.length;
    canvas.height = size;
    var ctx = canvas.getContext('2d');
    
    currentPalette.forEach(function(color, index) {
        ctx.fillStyle = color;
        ctx.fillRect(index * size, 0, size, size);
        
        // Add color code text
        ctx.fillStyle = '#fff';
        ctx.font = '24px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
        ctx.shadowColor = 'rgba(0,0,0,0.5)';
        ctx.shadowBlur = 10;
        ctx.fillText(color, index * size + size/2, size - 20);
    });
    
    var link = document.createElement('a');
    link.download = 'palette.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
    showToast('Palette downloaded!', 'success');
};

// ==================== HISTORY FUNCTIONS ====================

function renderHistory() {
    var historyDiv = document.getElementById('colorHistory');
    var listDiv = document.getElementById('historyList');
    
    if (savedPalettes.length === 0) {
        historyDiv.style.display = 'none';
        return;
    }
    
    historyDiv.style.display = 'block';
    listDiv.innerHTML = '';
    
    savedPalettes.slice().reverse().forEach(function(palette) {
        var item = document.createElement('div');
        item.className = 'history-item';
        item.innerHTML = `
            <div class="color-dot" style="background: ${palette.colors[0]}"></div>
            <span class="color-hex">${palette.name}</span>
            <span style="font-size:11px; color:#999;">(${palette.colors.length} colors)</span>
            <button onclick="loadPalette(${palette.id})" style="background:none; border:none; cursor:pointer;">📂</button>
            <button onclick="deletePalette(${palette.id})" class="delete-history">✕</button>
        `;
        listDiv.appendChild(item);
    });
}

window.loadPalette = function(id) {
    var palette = savedPalettes.find(function(p) { return p.id === id; });
    if (!palette) return;
    
    currentPalette = palette.colors;
    renderPalette(palette.colors);
    document.getElementById('baseColor').value = palette.colors[0];
    showToast('Loaded: ' + palette.name, 'success');
};

window.deletePalette = function(id) {
    if (!confirm('Delete this palette?')) return;
    savedPalettes = savedPalettes.filter(function(p) { return p.id !== id; });
    localStorage.setItem('colorPalettes', JSON.stringify(savedPalettes));
    renderHistory();
    showToast('Palette deleted', 'info');
};

// ==================== UTILITY FUNCTIONS ====================

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

// ==================== INITIALIZATION ====================

document.addEventListener('DOMContentLoaded', function() {
    renderHistory();
    generatePalette();
    
    // Close modal on outside click
    document.getElementById('colorModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
});