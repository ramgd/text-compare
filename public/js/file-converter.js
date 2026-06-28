// ============================================
// FILE FORMAT CONVERTER - COMPLETE JS
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

// ==================== CSV ↔ JSON CONVERTERS ====================

function csvToJson() {
    var csv = document.getElementById('csvInput').value;
    if (!csv.trim()) {
        showToast('Please enter CSV data', 'error');
        return;
    }
    
    try {
        var lines = csv.split('\n').filter(function(line) { return line.trim(); });
        if (lines.length < 2) {
            showToast('CSV needs at least 2 rows (headers + data)', 'error');
            return;
        }
        
        var headers = lines[0].split(',').map(function(h) { return h.trim(); });
        var result = [];
        
        for (var i = 1; i < lines.length; i++) {
            var values = lines[i].split(',').map(function(v) { return v.trim(); });
            var obj = {};
            for (var j = 0; j < headers.length; j++) {
                obj[headers[j]] = values[j] || '';
            }
            result.push(obj);
        }
        
        document.getElementById('csvOutput').value = JSON.stringify(result, null, 2);
        showToast('CSV converted to JSON successfully!', 'success');
    } catch (e) {
        showToast('Error parsing CSV: ' + e.message, 'error');
    }
}

function jsonToCsv() {
    var json = document.getElementById('csvOutput').value;
    if (!json.trim()) {
        showToast('Please enter JSON data in the output field', 'error');
        return;
    }
    
    try {
        var data = JSON.parse(json);
        if (!Array.isArray(data) || data.length === 0) {
            showToast('JSON must be an array of objects', 'error');
            return;
        }
        
        var headers = Object.keys(data[0]);
        var csv = headers.join(',') + '\n';
        
        data.forEach(function(row) {
            var values = headers.map(function(h) {
                var val = row[h] || '';
                if (typeof val === 'string' && val.includes(',')) {
                    val = '"' + val + '"';
                }
                return val;
            });
            csv += values.join(',') + '\n';
        });
        
        document.getElementById('csvInput').value = csv;
        showToast('JSON converted to CSV successfully!', 'success');
    } catch (e) {
        showToast('Invalid JSON: ' + e.message, 'error');
    }
}

function clearCSV() {
    document.getElementById('csvInput').value = '';
    document.getElementById('csvOutput').value = '';
    showToast('Cleared', 'info');
}

function copyCSVOutput() {
    copyText('csvOutput');
}

function downloadCSVOutput(format) {
    var content = document.getElementById('csvOutput').value;
    if (!content) {
        showToast('No content to download', 'error');
        return;
    }
    var ext = format === 'json' ? 'json' : 'csv';
    var mime = format === 'json' ? 'application/json' : 'text/csv';
    downloadFile(content, 'converted.' + ext, mime);
}

// ==================== JSON ↔ XML CONVERTERS ====================

function jsonToXml() {
    var json = document.getElementById('jsonXmlInput').value;
    if (!json.trim()) {
        showToast('Please enter JSON data', 'error');
        return;
    }
    
    try {
        var data = JSON.parse(json);
        var xml = jsonToXmlHelper(data, 'root');
        document.getElementById('jsonXmlOutput').value = xml;
        showToast('JSON converted to XML successfully!', 'success');
    } catch (e) {
        showToast('Invalid JSON: ' + e.message, 'error');
    }
}

function jsonToXmlHelper(obj, nodeName) {
    if (Array.isArray(obj)) {
        var result = '';
        obj.forEach(function(item) {
            result += jsonToXmlHelper(item, nodeName);
        });
        return result;
    }
    
    if (typeof obj === 'object' && obj !== null) {
        var xml = '<' + nodeName + '>';
        for (var key in obj) {
            if (obj.hasOwnProperty(key)) {
                var value = obj[key];
                if (typeof value === 'object' && value !== null) {
                    xml += jsonToXmlHelper(value, key);
                } else {
                    xml += '<' + key + '>' + escapeXml(value) + '</' + key + '>';
                }
            }
        }
        xml += '</' + nodeName + '>';
        return xml;
    }
    
    return '<' + nodeName + '>' + escapeXml(obj) + '</' + nodeName + '>';
}

function xmlToJson() {
    var xml = document.getElementById('jsonXmlInput').value;
    if (!xml.trim()) {
        showToast('Please enter XML data', 'error');
        return;
    }
    
    try {
        var parser = new DOMParser();
        var xmlDoc = parser.parseFromString(xml, 'text/xml');
        var json = xmlToJsonHelper(xmlDoc.documentElement);
        document.getElementById('jsonXmlOutput').value = JSON.stringify(json, null, 2);
        showToast('XML converted to JSON successfully!', 'success');
    } catch (e) {
        showToast('Error parsing XML: ' + e.message, 'error');
    }
}

function xmlToJsonHelper(node) {
    var obj = {};
    
    if (node.children.length === 0) {
        return node.textContent;
    }
    
    for (var i = 0; i < node.children.length; i++) {
        var child = node.children[i];
        var tagName = child.tagName;
        
        if (obj[tagName]) {
            if (!Array.isArray(obj[tagName])) {
                obj[tagName] = [obj[tagName]];
            }
            obj[tagName].push(xmlToJsonHelper(child));
        } else {
            obj[tagName] = xmlToJsonHelper(child);
        }
    }
    
    return obj;
}

function clearJsonXml() {
    document.getElementById('jsonXmlInput').value = '';
    document.getElementById('jsonXmlOutput').value = '';
    showToast('Cleared', 'info');
}

function copyJsonXmlOutput() {
    copyText('jsonXmlOutput');
}

function downloadJsonXmlOutput(format) {
    var content = document.getElementById('jsonXmlOutput').value;
    if (!content) {
        showToast('No content to download', 'error');
        return;
    }
    var ext = format === 'xml' ? 'xml' : 'json';
    var mime = format === 'xml' ? 'application/xml' : 'application/json';
    downloadFile(content, 'converted.' + ext, mime);
}

// ==================== JSON ↔ YAML CONVERTERS ====================

function jsonToYaml() {
    var json = document.getElementById('jsonYamlInput').value;
    if (!json.trim()) {
        showToast('Please enter JSON data', 'error');
        return;
    }
    
    try {
        var data = JSON.parse(json);
        var yaml = jsonToYamlHelper(data, 0);
        document.getElementById('jsonYamlOutput').value = yaml;
        showToast('JSON converted to YAML successfully!', 'success');
    } catch (e) {
        showToast('Invalid JSON: ' + e.message, 'error');
    }
}

function jsonToYamlHelper(obj, indent) {
    var spaces = '  '.repeat(indent);
    var result = '';
    
    if (Array.isArray(obj)) {
        obj.forEach(function(item) {
            if (typeof item === 'object' && item !== null) {
                result += spaces + '- \n' + jsonToYamlHelper(item, indent + 1);
            } else {
                result += spaces + '- ' + item + '\n';
            }
        });
        return result;
    }
    
    if (typeof obj === 'object' && obj !== null) {
        for (var key in obj) {
            if (obj.hasOwnProperty(key)) {
                var value = obj[key];
                if (typeof value === 'object' && value !== null) {
                    result += spaces + key + ':\n' + jsonToYamlHelper(value, indent + 1);
                } else {
                    result += spaces + key + ': ' + value + '\n';
                }
            }
        }
        return result;
    }
    
    return String(obj);
}

function yamlToJson() {
    var yaml = document.getElementById('jsonYamlInput').value;
    if (!yaml.trim()) {
        showToast('Please enter YAML data', 'error');
        return;
    }
    
    try {
        var json = yamlToJsonHelper(yaml);
        document.getElementById('jsonYamlOutput').value = JSON.stringify(json, null, 2);
        showToast('YAML converted to JSON successfully!', 'success');
    } catch (e) {
        showToast('Error parsing YAML: ' + e.message, 'error');
    }
}

function yamlToJsonHelper(yaml) {
    var lines = yaml.split('\n');
    var result = {};
    var currentKey = '';
    
    for (var i = 0; i < lines.length; i++) {
        var line = lines[i].trim();
        if (!line) continue;
        
        if (line.includes(':')) {
            var parts = line.split(':');
            var key = parts[0].trim();
            var value = parts.slice(1).join(':').trim();
            
            if (value) {
                result[key] = value;
            } else {
                currentKey = key;
                result[key] = {};
            }
        } else if (line.startsWith('- ')) {
            var item = line.substring(2);
            if (result[currentKey] && !Array.isArray(result[currentKey])) {
                result[currentKey] = [];
            }
            result[currentKey].push(item);
        }
    }
    
    return result;
}

function clearJsonYaml() {
    document.getElementById('jsonYamlInput').value = '';
    document.getElementById('jsonYamlOutput').value = '';
    showToast('Cleared', 'info');
}

function copyJsonYamlOutput() {
    copyText('jsonYamlOutput');
}

function downloadJsonYamlOutput(format) {
    var content = document.getElementById('jsonYamlOutput').value;
    if (!content) {
        showToast('No content to download', 'error');
        return;
    }
    var ext = format === 'yaml' ? 'yaml' : 'json';
    var mime = format === 'yaml' ? 'text/yaml' : 'application/json';
    downloadFile(content, 'converted.' + ext, mime);
}

// ==================== XML ↔ JSON CONVERTERS (Alternate) ====================

function xmlToJsonAlt() {
    var xml = document.getElementById('xmlJsonInput').value;
    if (!xml.trim()) {
        showToast('Please enter XML data', 'error');
        return;
    }
    
    try {
        var parser = new DOMParser();
        var xmlDoc = parser.parseFromString(xml, 'text/xml');
        var json = xmlToJsonHelper(xmlDoc.documentElement);
        document.getElementById('xmlJsonOutput').value = JSON.stringify(json, null, 2);
        showToast('XML converted to JSON successfully!', 'success');
    } catch (e) {
        showToast('Error parsing XML: ' + e.message, 'error');
    }
}

function jsonToXmlAlt() {
    var json = document.getElementById('xmlJsonInput').value;
    if (!json.trim()) {
        showToast('Please enter JSON data', 'error');
        return;
    }
    
    try {
        var data = JSON.parse(json);
        var xml = jsonToXmlHelper(data, 'root');
        document.getElementById('xmlJsonOutput').value = xml;
        showToast('JSON converted to XML successfully!', 'success');
    } catch (e) {
        showToast('Invalid JSON: ' + e.message, 'error');
    }
}

function clearXmlJson() {
    document.getElementById('xmlJsonInput').value = '';
    document.getElementById('xmlJsonOutput').value = '';
    showToast('Cleared', 'info');
}

function copyXmlJsonOutput() {
    copyText('xmlJsonOutput');
}

function downloadXmlJsonOutput(format) {
    var content = document.getElementById('xmlJsonOutput').value;
    if (!content) {
        showToast('No content to download', 'error');
        return;
    }
    var ext = format === 'json' ? 'json' : 'xml';
    var mime = format === 'json' ? 'application/json' : 'application/xml';
    downloadFile(content, 'converted.' + ext, mime);
}

// ==================== FILE UPLOAD HANDLERS ====================

function handleCSVFile(event) {
    var file = event.target.files[0];
    if (!file) return;
    
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('csvInput').value = e.target.result;
        showToast('File loaded successfully!', 'success');
    };
    reader.readAsText(file);
}

// ==================== UTILITY FUNCTIONS ====================

function escapeXml(text) {
    return String(text).replace(/&/g, '&amp;')
                      .replace(/</g, '&lt;')
                      .replace(/>/g, '&gt;')
                      .replace(/"/g, '&quot;')
                      .replace(/'/g, '&apos;');
}

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

function downloadFile(content, filename, mimeType) {
    var blob = new Blob([content], { type: mimeType });
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

// ==================== DRAG AND DROP ====================

document.addEventListener('DOMContentLoaded', function() {
    var zone = document.getElementById('csvUploadZone');
    var input = zone.querySelector('input[type="file"]');
    
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