// ============================================
// DATE & TIME CALCULATOR - COMPLETE JS
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

// ==================== DATE DIFFERENCE FUNCTIONS ====================
function setDateRange(days) {
    var endDate = new Date();
    var startDate = new Date();
    startDate.setDate(startDate.getDate() - days);
    
    document.getElementById('startDate').value = formatDateInput(startDate);
    document.getElementById('endDate').value = formatDateInput(endDate);
    calculateDifference();
}

function formatDateInput(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
}

function calculateDifference() {
    var startVal = document.getElementById('startDate').value;
    var endVal = document.getElementById('endDate').value;
    
    if (!startVal || !endVal) {
        showToast('Please select both dates', 'error');
        return;
    }
    
    var start = new Date(startVal);
    var end = new Date(endVal);
    
    if (start > end) {
        showToast('Start date must be before end date', 'error');
        return;
    }
    
    var diffMs = end - start;
    var diffSeconds = Math.floor(diffMs / 1000);
    var diffMinutes = Math.floor(diffSeconds / 60);
    var diffHours = Math.floor(diffMinutes / 60);
    var diffDays = Math.floor(diffHours / 24);
    
    // Calculate years, months, days
    var years = end.getFullYear() - start.getFullYear();
    var months = end.getMonth() - start.getMonth();
    var days = end.getDate() - start.getDate();
    
    if (days < 0) {
        months--;
        var tempDate = new Date(end.getFullYear(), end.getMonth(), 0);
        days += tempDate.getDate();
    }
    if (months < 0) {
        years--;
        months += 12;
    }
    
    // Calculate weekdays and weekends
    var weekdays = 0;
    var weekends = 0;
    var current = new Date(start);
    current.setHours(0, 0, 0, 0);
    var endDate = new Date(end);
    endDate.setHours(0, 0, 0, 0);
    
    while (current <= endDate) {
        var dayOfWeek = current.getDay();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            weekends++;
        } else {
            weekdays++;
        }
        current.setDate(current.getDate() + 1);
    }
    
    document.getElementById('diffYears').textContent = years;
    document.getElementById('diffMonths').textContent = months;
    document.getElementById('diffDays').textContent = diffDays;
    document.getElementById('diffHours').textContent = diffHours;
    document.getElementById('diffMinutes').textContent = diffMinutes;
    document.getElementById('diffSeconds').textContent = diffSeconds;
    document.getElementById('diffWeeks').textContent = Math.floor(diffDays / 7);
    document.getElementById('diffWeekdays').textContent = weekdays;
    document.getElementById('diffWeekends').textContent = weekends;
}

function clearDifference() {
    document.getElementById('startDate').value = '';
    document.getElementById('endDate').value = '';
    document.querySelectorAll('#differenceResults .result-value').forEach(function(el) {
        el.textContent = '-';
    });
    showToast('Cleared', 'info');
}

// ==================== ADD/SUBTRACT FUNCTIONS ====================
var addMode = 'add';

function setAddMode(mode) {
    addMode = mode;
    document.getElementById('addModeBtn').className = 'btn-add-mode' + (mode === 'add' ? ' active' : '');
    document.getElementById('subtractModeBtn').className = 'btn-subtract-mode' + (mode === 'subtract' ? ' active' : '');
    calculateAddSubtract();
}

function calculateAddSubtract() {
    var baseVal = document.getElementById('baseDate').value;
    if (!baseVal) {
        showToast('Please select a base date', 'error');
        return;
    }
    
    var baseDate = new Date(baseVal);
    var years = parseInt(document.getElementById('addYears').value) || 0;
    var months = parseInt(document.getElementById('addMonths').value) || 0;
    var days = parseInt(document.getElementById('addDays').value) || 0;
    var hours = parseInt(document.getElementById('addHours').value) || 0;
    var minutes = parseInt(document.getElementById('addMinutes').value) || 0;
    
    var resultDate = new Date(baseDate);
    
    if (addMode === 'add') {
        resultDate.setFullYear(resultDate.getFullYear() + years);
        resultDate.setMonth(resultDate.getMonth() + months);
        resultDate.setDate(resultDate.getDate() + days);
        resultDate.setHours(resultDate.getHours() + hours);
        resultDate.setMinutes(resultDate.getMinutes() + minutes);
    } else {
        resultDate.setFullYear(resultDate.getFullYear() - years);
        resultDate.setMonth(resultDate.getMonth() - months);
        resultDate.setDate(resultDate.getDate() - days);
        resultDate.setHours(resultDate.getHours() - hours);
        resultDate.setMinutes(resultDate.getMinutes() - minutes);
    }
    
    document.getElementById('addOriginalDate').textContent = formatDateDisplay(baseDate);
    document.getElementById('addNewDate').textContent = formatDateDisplay(resultDate);
    document.getElementById('addDayOfWeek').textContent = getDayOfWeek(resultDate);
    document.getElementById('addWeekNumber').textContent = getWeekNumber(resultDate);
}

function formatDateDisplay(date) {
    var options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    };
    return date.toLocaleDateString('en-US', options);
}

function getDayOfWeek(date) {
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    return days[date.getDay()];
}

function getWeekNumber(date) {
    var startOfYear = new Date(date.getFullYear(), 0, 1);
    var diff = (date - startOfYear) / (7 * 24 * 60 * 60 * 1000);
    return Math.ceil(diff);
}

// ==================== BUSINESS DAYS FUNCTIONS ====================
function calculateBusinessDays() {
    var startVal = document.getElementById('businessStartDate').value;
    var businessDays = parseInt(document.getElementById('businessDays').value) || 0;
    
    if (!startVal) {
        showToast('Please select a start date', 'error');
        return;
    }
    
    var startDate = new Date(startVal);
    var resultDate = new Date(startDate);
    var added = 0;
    var weekends = 0;
    
    while (added < businessDays) {
        resultDate.setDate(resultDate.getDate() + 1);
        var dayOfWeek = resultDate.getDay();
        if (dayOfWeek !== 0 && dayOfWeek !== 6) {
            added++;
        } else {
            weekends++;
        }
    }
    
    document.getElementById('businessStart').textContent = formatDateDisplay(startDate);
    document.getElementById('businessEnd').textContent = formatDateDisplay(resultDate);
    document.getElementById('businessTotal').textContent = businessDays;
    document.getElementById('businessWeekends').textContent = weekends;
}

// ==================== TIMEZONE FUNCTIONS ====================
function convertTimezone() {
    var dateTimeVal = document.getElementById('timezoneDateTime').value;
    var fromTz = document.getElementById('fromTimezone').value;
    var toTz = document.getElementById('toTimezone').value;
    
    if (!dateTimeVal) {
        // Set default to current time
        var now = new Date();
        var offset = now.getTimezoneOffset() * 60000;
        var localISOTime = new Date(now - offset).toISOString().slice(0, 16);
        document.getElementById('timezoneDateTime').value = localISOTime;
        dateTimeVal = localISOTime;
    }
    
    var date = new Date(dateTimeVal);
    
    // Get timezone offsets
    var fromOffset = getTimezoneOffset(fromTz);
    var toOffset = getTimezoneOffset(toTz);
    
    // Convert to UTC then to target timezone
    var utcDate = new Date(date.getTime() - fromOffset * 60000);
    var convertedDate = new Date(utcDate.getTime() + toOffset * 60000);
    
    document.getElementById('tzOriginal').textContent = date.toLocaleString() + ' (' + fromTz + ')';
    document.getElementById('tzConverted').textContent = convertedDate.toLocaleString() + ' (' + toTz + ')';
    document.getElementById('tzDifference').textContent = (toOffset - fromOffset) + ' hours';
    document.getElementById('tzFrom').textContent = fromTz;
    document.getElementById('tzTo').textContent = toTz;
}

function getTimezoneOffset(timezone) {
    var offsets = {
        'UTC': 0,
        'America/New_York': -5,
        'America/Chicago': -6,
        'America/Denver': -7,
        'America/Los_Angeles': -8,
        'Europe/London': 0,
        'Europe/Paris': 1,
        'Asia/Dubai': 4,
        'Asia/Kolkata': 5.5,
        'Asia/Tokyo': 9,
        'Australia/Sydney': 11
    };
    return offsets[timezone] || 0;
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates for difference
    var today = new Date();
    var yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    
    document.getElementById('startDate').value = formatDateInput(yesterday);
    document.getElementById('endDate').value = formatDateInput(today);
    document.getElementById('baseDate').value = formatDateInput(today);
    document.getElementById('businessStartDate').value = formatDateInput(today);
    
    // Set default timezone datetime
    var now = new Date();
    var offset = now.getTimezoneOffset() * 60000;
    var localISOTime = new Date(now - offset).toISOString().slice(0, 16);
    document.getElementById('timezoneDateTime').value = localISOTime;
    
    // Calculate defaults
    calculateDifference();
    calculateAddSubtract();
    calculateBusinessDays();
    convertTimezone();
});

// ==================== TOAST SYSTEM ====================
/* showToast() lives in js/common.js - every tool had a byte-for-byte
   equivalent copy of it. common.js is loaded first on every page. */
