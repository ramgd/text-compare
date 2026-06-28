// ============================================
// UNIT CONVERTER - COMPLETE JAVASCRIPT
// ============================================

// Conversion Data
var unitData = {
    length: {
        name: 'Length',
        units: {
            kilometer: { label: 'Kilometer', toBase: 1000, fromBase: 0.001 },
            meter: { label: 'Meter', toBase: 1, fromBase: 1 },
            centimeter: { label: 'Centimeter', toBase: 0.01, fromBase: 100 },
            millimeter: { label: 'Millimeter', toBase: 0.001, fromBase: 1000 },
            mile: { label: 'Mile', toBase: 1609.344, fromBase: 0.000621371 },
            yard: { label: 'Yard', toBase: 0.9144, fromBase: 1.09361 },
            foot: { label: 'Foot', toBase: 0.3048, fromBase: 3.28084 },
            inch: { label: 'Inch', toBase: 0.0254, fromBase: 39.3701 }
        }
    },
    weight: {
        name: 'Weight',
        units: {
            kilogram: { label: 'Kilogram', toBase: 1, fromBase: 1 },
            gram: { label: 'Gram', toBase: 0.001, fromBase: 1000 },
            milligram: { label: 'Milligram', toBase: 0.000001, fromBase: 1000000 },
            pound: { label: 'Pound', toBase: 0.453592, fromBase: 2.20462 },
            ounce: { label: 'Ounce', toBase: 0.0283495, fromBase: 35.274 },
            ton: { label: 'Ton', toBase: 1000, fromBase: 0.001 }
        }
    },
    temperature: {
        name: 'Temperature',
        units: {
            celsius: { label: 'Celsius', toBase: 1, fromBase: 1 },
            fahrenheit: { label: 'Fahrenheit', toBase: 1, fromBase: 1 },
            kelvin: { label: 'Kelvin', toBase: 1, fromBase: 1 }
        },
        isTemperature: true
    },
    speed: {
        name: 'Speed',
        units: {
            kmh: { label: 'km/h', toBase: 1, fromBase: 1 },
            mph: { label: 'mph', toBase: 1.60934, fromBase: 0.621371 },
            ms: { label: 'm/s', toBase: 3.6, fromBase: 0.277778 },
            knot: { label: 'Knot', toBase: 1.852, fromBase: 0.539957 }
        }
    },
    area: {
        name: 'Area',
        units: {
            sqmeter: { label: 'Square Meter', toBase: 1, fromBase: 1 },
            sqkilometer: { label: 'Square Kilometer', toBase: 1000000, fromBase: 0.000001 },
            sqfoot: { label: 'Square Foot', toBase: 0.092903, fromBase: 10.7639 },
            sqyard: { label: 'Square Yard', toBase: 0.836127, fromBase: 1.19599 },
            acre: { label: 'Acre', toBase: 4046.86, fromBase: 0.000247105 },
            hectare: { label: 'Hectare', toBase: 10000, fromBase: 0.0001 }
        }
    },
    volume: {
        name: 'Volume',
        units: {
            liter: { label: 'Liter', toBase: 1, fromBase: 1 },
            milliliter: { label: 'Milliliter', toBase: 0.001, fromBase: 1000 },
            gallon: { label: 'Gallon (US)', toBase: 3.78541, fromBase: 0.264172 },
            quart: { label: 'Quart (US)', toBase: 0.946353, fromBase: 1.05669 },
            pint: { label: 'Pint (US)', toBase: 0.473176, fromBase: 2.11338 },
            cup: { label: 'Cup', toBase: 0.236588, fromBase: 4.22675 }
        }
    },
    data: {
        name: 'Data Storage',
        units: {
            byte: { label: 'Byte', toBase: 1, fromBase: 1 },
            kilobyte: { label: 'Kilobyte', toBase: 1024, fromBase: 0.000976563 },
            megabyte: { label: 'Megabyte', toBase: 1048576, fromBase: 0.000000954 },
            gigabyte: { label: 'Gigabyte', toBase: 1073741824, fromBase: 0.000000000931 },
            terabyte: { label: 'Terabyte', toBase: 1099511627776, fromBase: 0.000000000000909 },
            petabyte: { label: 'Petabyte', toBase: 1125899906842624, fromBase: 0.000000000000000888 }
        }
    },
    time: {
        name: 'Time',
        units: {
            second: { label: 'Second', toBase: 1, fromBase: 1 },
            minute: { label: 'Minute', toBase: 60, fromBase: 0.0166667 },
            hour: { label: 'Hour', toBase: 3600, fromBase: 0.000277778 },
            day: { label: 'Day', toBase: 86400, fromBase: 0.0000115741 },
            week: { label: 'Week', toBase: 604800, fromBase: 0.00000165344 }
        }
    }
};

// State
var conversionHistory = JSON.parse(localStorage.getItem('conversionHistory')) || [];
var currentCategory = 'length';

// ==================== INITIALIZATION ====================

document.addEventListener('DOMContentLoaded', function() {
    changeCategory();
    renderHistory();
    convert();
});

// ==================== CATEGORY FUNCTIONS ====================

window.changeCategory = function() {
    currentCategory = document.getElementById('category').value;
    var fromSelect = document.getElementById('fromUnit');
    var toSelect = document.getElementById('toUnit');
    
    // Clear current options
    fromSelect.innerHTML = '';
    toSelect.innerHTML = '';
    
    // Add new options
    var units = unitData[currentCategory].units;
    var unitKeys = Object.keys(units);
    
    unitKeys.forEach(function(key, index) {
        var option1 = document.createElement('option');
        option1.value = key;
        option1.textContent = units[key].label;
        fromSelect.appendChild(option1);
        
        var option2 = document.createElement('option');
        option2.value = key;
        option2.textContent = units[key].label;
        toSelect.appendChild(option2);
    });
    
    // Set default selections
    if (unitKeys.length > 1) {
        fromSelect.selectedIndex = 0;
        toSelect.selectedIndex = Math.min(1, unitKeys.length - 1);
    }
    
    convert();
};

// ==================== CONVERSION FUNCTIONS ====================

window.convert = function() {
    var fromUnit = document.getElementById('fromUnit').value;
    var toUnit = document.getElementById('toUnit').value;
    var fromValue = parseFloat(document.getElementById('fromValue').value);
    
    if (isNaN(fromValue) || fromValue === '') {
        document.getElementById('toValue').value = '';
        return;
    }
    
    var category = currentCategory;
    var data = unitData[category];
    
    // Handle temperature separately
    if (data.isTemperature) {
        var result = convertTemperature(fromUnit, toUnit, fromValue);
        document.getElementById('toValue').value = result;
        return;
    }
    
    var fromUnitData = data.units[fromUnit];
    var toUnitData = data.units[toUnit];
    
    // Convert to base unit first
    var baseValue = fromValue * fromUnitData.toBase;
    
    // Convert from base unit to target
    var result = baseValue * toUnitData.fromBase;
    
    // Format result
    if (Number.isInteger(result) && result < 1000000) {
        document.getElementById('toValue').value = result;
    } else {
        document.getElementById('toValue').value = result.toFixed(6);
    }
};

function convertTemperature(from, to, value) {
    // Convert to Celsius first
    var celsius;
    switch(from) {
        case 'celsius':
            celsius = value;
            break;
        case 'fahrenheit':
            celsius = (value - 32) * 5 / 9;
            break;
        case 'kelvin':
            celsius = value - 273.15;
            break;
        default:
            celsius = value;
    }
    
    // Convert from Celsius to target
    var result;
    switch(to) {
        case 'celsius':
            result = celsius;
            break;
        case 'fahrenheit':
            result = celsius * 9 / 5 + 32;
            break;
        case 'kelvin':
            result = celsius + 273.15;
            break;
        default:
            result = celsius;
    }
    
    // Format result
    if (Number.isInteger(result) && result < 1000000) {
        return result;
    } else {
        return result.toFixed(2);
    }
}

// ==================== SWAP FUNCTION ====================

window.swapUnits = function() {
    var fromSelect = document.getElementById('fromUnit');
    var toSelect = document.getElementById('toUnit');
    var fromValue = document.getElementById('fromValue');
    var toValue = document.getElementById('toValue');
    
    // Swap selections
    var tempUnit = fromSelect.value;
    fromSelect.value = toSelect.value;
    toSelect.value = tempUnit;
    
    // Swap values
    var tempValue = fromValue.value;
    fromValue.value = toValue.value;
    toValue.value = tempValue;
    
    // Reconvert if needed
    if (fromValue.value !== '') {
        convert();
    }
    
    showToast('Units swapped!', 'info');
};

// ==================== QUICK PRESETS ====================

window.setValue = function(value) {
    document.getElementById('fromValue').value = value;
    convert();
};

// ==================== RESULT ACTIONS ====================

window.copyResult = function() {
    var result = document.getElementById('toValue').value;
    if (!result) {
        showToast('No result to copy', 'error');
        return;
    }
    
    var fromValue = document.getElementById('fromValue').value;
    var fromUnit = document.getElementById('fromUnit');
    var toUnit = document.getElementById('toUnit');
    var category = document.getElementById('category');
    
    var fullResult = fromValue + ' ' + fromUnit.options[fromUnit.selectedIndex].text + 
                     ' = ' + result + ' ' + toUnit.options[toUnit.selectedIndex].text;
    
    navigator.clipboard.writeText(fullResult).then(function() {
        showToast('Copied: ' + fullResult, 'success');
    }).catch(function() {
        fallbackCopy(fullResult);
    });
};

window.saveConversion = function() {
    var result = document.getElementById('toValue').value;
    if (!result) {
        showToast('No conversion to save', 'error');
        return;
    }
    
    var fromValue = document.getElementById('fromValue').value;
    var fromUnit = document.getElementById('fromUnit');
    var toUnit = document.getElementById('toUnit');
    var category = document.getElementById('category');
    var categoryName = unitData[currentCategory].name;
    
    var historyItem = {
        id: Date.now(),
        fromValue: fromValue,
        fromUnit: fromUnit.options[fromUnit.selectedIndex].text,
        toValue: result,
        toUnit: toUnit.options[toUnit.selectedIndex].text,
        category: categoryName,
        time: new Date().toLocaleString()
    };
    
    conversionHistory.push(historyItem);
    localStorage.setItem('conversionHistory', JSON.stringify(conversionHistory));
    renderHistory();
    showToast('Conversion saved!', 'success');
};

window.clearAll = function() {
    document.getElementById('fromValue').value = '';
    document.getElementById('toValue').value = '';
    showToast('Cleared', 'info');
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
        showToast('Copied!', 'success');
    } catch (e) {
        showToast('Failed to copy', 'error');
    }
    document.body.removeChild(textarea);
}

// ==================== HISTORY FUNCTIONS ====================

function renderHistory() {
    var historyDiv = document.getElementById('historySection');
    var listDiv = document.getElementById('historyList');
    
    if (conversionHistory.length === 0) {
        historyDiv.style.display = 'none';
        return;
    }
    
    historyDiv.style.display = 'block';
    listDiv.innerHTML = '';
    
    conversionHistory.slice().reverse().forEach(function(item) {
        var div = document.createElement('div');
        div.className = 'history-item';
        div.innerHTML = `
            <span class="history-value">${item.fromValue} ${item.fromUnit} = ${item.toValue} ${item.toUnit}</span>
            <span class="history-detail">${item.category}</span>
            <span class="history-time">${item.time}</span>
            <button onclick="deleteHistory(${item.id})" style="background:none; border:none; color:#dc3545; cursor:pointer;">✕</button>
        `;
        listDiv.appendChild(div);
    });
}

window.deleteHistory = function(id) {
    conversionHistory = conversionHistory.filter(function(item) {
        return item.id !== id;
    });
    localStorage.setItem('conversionHistory', JSON.stringify(conversionHistory));
    renderHistory();
    showToast('History item deleted', 'info');
};

window.clearHistory = function() {
    if (!confirm('Clear all history?')) return;
    conversionHistory = [];
    localStorage.setItem('conversionHistory', JSON.stringify(conversionHistory));
    renderHistory();
    showToast('History cleared', 'info');
};

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