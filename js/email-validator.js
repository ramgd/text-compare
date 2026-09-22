// ============================================
// EMAIL VALIDATOR & GENERATOR - COMPLETE JS
// ============================================

// Disposable email domains
var disposableDomains = [
    'mailinator.com', 'guerrillamail.com', '10minutemail.com', 'throwawaymail.com',
    'tempmail.com', 'temp-mail.org', 'fakeinbox.com', 'dispostable.com',
    'spamgourmet.com', 'trashmail.com', 'mailnator.com', 'getnada.com',
    'yopmail.com', 'maildrop.cc', 'guerrillamail.org', 'sharklasers.com'
];

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

// ==================== VALIDATE EMAIL ====================
function setEmail(email) {
    document.getElementById('emailInput').value = email;
    validateEmail();
}

function validateEmail() {
    var email = document.getElementById('emailInput').value.trim();
    if (!email) {
        showToast('Please enter an email address', 'error');
        return;
    }
    
    document.getElementById('valEmail').textContent = email;
    
    // Basic format validation
    var isValidFormat = validateEmailFormat(email);
    var parts = email.split('@');
    var domain = parts.length === 2 ? parts[1] : '';
    var username = parts.length === 2 ? parts[0] : '';
    
    // Check MX records (simulated)
    var hasMx = domain ? checkMxRecords(domain) : false;
    
    // Check if disposable
    var isDisposable = domain ? isDisposableEmail(domain) : false;
    
    document.getElementById('valDomain').textContent = domain || '-';
    document.getElementById('valUsername').textContent = username || '-';
    document.getElementById('valFormat').textContent = isValidFormat ? '✅ Valid' : '❌ Invalid';
    
    if (isValidFormat) {
        document.getElementById('valStatus').textContent = '✅ Valid';
        document.getElementById('valStatus').className = 'result-value valid';
        document.getElementById('valMx').textContent = hasMx ? '✅ Found' : '⚠️ Not Found';
        document.getElementById('valDisposable').textContent = isDisposable ? '⚠️ Yes' : '✅ No';
        document.getElementById('valDisposable').className = 'result-value ' + (isDisposable ? 'disposable' : 'valid');
        showToast('Email is valid!', 'success');
    } else {
        document.getElementById('valStatus').textContent = '❌ Invalid';
        document.getElementById('valStatus').className = 'result-value invalid';
        document.getElementById('valMx').textContent = '-';
        document.getElementById('valDisposable').textContent = '-';
        document.getElementById('valDisposable').className = 'result-value';
        showToast('Invalid email format!', 'error');
    }
}

function validateEmailFormat(email) {
    var regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

function checkMxRecords(domain) {
    // Simulated MX record check
    // In production, you would use a real API
    var commonDomains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'google.com', 'microsoft.com'];
    return commonDomains.indexOf(domain.toLowerCase()) !== -1 || domain.length > 3;
}

function isDisposableEmail(domain) {
    return disposableDomains.indexOf(domain.toLowerCase()) !== -1;
}

// ==================== GENERATE EMAILS ====================
var generatedEmails = [];

function generateEmails() {
    var count = parseInt(document.getElementById('emailCount').value);
    var domain = document.getElementById('emailDomain').value;
    
    var firstNames = ['john', 'jane', 'mike', 'sarah', 'david', 'emma', 'chris', 'lisa', 'mark', 'anna'];
    var lastNames = ['smith', 'johnson', 'williams', 'brown', 'jones', 'garcia', 'miller', 'davis', 'martinez', 'wilson'];
    var numbers = ['123', '456', '789', '001', '007', '2023', '2024', '88', '99'];
    
    generatedEmails = [];
    
    for (var i = 0; i < count; i++) {
        var firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
        var lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
        var number = numbers[Math.floor(Math.random() * numbers.length)];
        
        var email = firstName + '.' + lastName + number + '@' + domain;
        generatedEmails.push(email);
    }
    
    document.getElementById('generatedList').value = generatedEmails.join('\n');
    document.getElementById('genCount').textContent = generatedEmails.length;
    showToast(generatedEmails.length + ' emails generated!', 'success');
}

function copyGeneratedEmails() {
    var text = document.getElementById('generatedList').value;
    if (!text) {
        showToast('No emails to copy', 'error');
        return;
    }
    
    navigator.clipboard.writeText(text).then(function() {
        showToast('Emails copied to clipboard!', 'success');
    }).catch(function() {
        fallbackCopy(text);
    });
}

function downloadEmails() {
    var text = document.getElementById('generatedList').value;
    if (!text) {
        showToast('No emails to download', 'error');
        return;
    }
    
    var blob = new Blob([text], { type: 'text/plain' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'generated-emails.txt';
    a.click();
    URL.revokeObjectURL(url);
    showToast('Emails downloaded!', 'success');
}

function clearGenerated() {
    document.getElementById('generatedList').value = '';
    document.getElementById('genCount').textContent = '0';
    generatedEmails = [];
    showToast('Cleared', 'info');
}

// ==================== BULK VALIDATE ====================
function loadSampleEmails() {
    var sample = 'john.doe@gmail.com\njane.smith@yahoo.com\ninvalid-email\nmark@mailinator.com\nsarah@company.com\ntest@outlook.com\nuser@temp-mail.org\nadmin@google.com';
    document.getElementById('bulkInput').value = sample;
    bulkValidate();
}

function bulkValidate() {
    var input = document.getElementById('bulkInput').value;
    var emails = input.split('\n').filter(function(email) { return email.trim(); });
    
    if (emails.length === 0) {
        showToast('Please enter some emails', 'error');
        return;
    }
    
    var valid = 0;
    var invalid = 0;
    var disposable = 0;
    var results = [];
    
    emails.forEach(function(email) {
        email = email.trim();
        var isValid = validateEmailFormat(email);
        var parts = email.split('@');
        var domain = parts.length === 2 ? parts[1] : '';
        var isDisposable = domain ? isDisposableEmail(domain) : false;
        
        var status = 'invalid';
        if (isValid) {
            valid++;
            status = isDisposable ? 'disposable' : 'valid';
            if (isDisposable) disposable++;
        } else {
            invalid++;
        }
        
        results.push({
            email: email,
            valid: isValid,
            disposable: isDisposable,
            status: status
        });
    });
    
    document.getElementById('bulkTotal').textContent = results.length;
    document.getElementById('bulkValid').textContent = valid;
    document.getElementById('bulkInvalid').textContent = invalid;
    document.getElementById('bulkDisposable').textContent = disposable;
    
    var listHtml = '';
    results.forEach(function(result) {
        var statusText = result.status === 'valid' ? '✅ Valid' : 
                         result.status === 'disposable' ? '⚠️ Disposable' : '❌ Invalid';
        var statusClass = result.status === 'valid' ? 'valid' : 
                         result.status === 'disposable' ? 'disposable' : 'invalid';
        listHtml += '<div class="bulk-item ' + statusClass + '">' +
                    '<span class="bulk-email">' + result.email + '</span>' +
                    '<span class="bulk-status ' + statusClass + '">' + statusText + '</span>' +
                    '</div>';
    });
    
    document.getElementById('bulkList').innerHTML = listHtml;
    showToast('Validated ' + results.length + ' emails!', 'success');
}

function clearBulk() {
    document.getElementById('bulkInput').value = '';
    document.getElementById('bulkTotal').textContent = '0';
    document.getElementById('bulkValid').textContent = '0';
    document.getElementById('bulkInvalid').textContent = '0';
    document.getElementById('bulkDisposable').textContent = '0';
    document.getElementById('bulkList').innerHTML = '';
    showToast('Cleared', 'info');
}

function downloadBulkResults() {
    var items = document.querySelectorAll('.bulk-item');
    if (items.length === 0) {
        showToast('No results to download', 'error');
        return;
    }
    
    var csv = 'Email,Status\n';
    items.forEach(function(item) {
        var email = item.querySelector('.bulk-email').textContent;
        var status = item.querySelector('.bulk-status').textContent;
        csv += email + ',' + status + '\n';
    });
    
    var blob = new Blob([csv], { type: 'text/csv' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'bulk-validation-results.csv';
    a.click();
    URL.revokeObjectURL(url);
    showToast('Results downloaded!', 'success');
}

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
/* showToast() lives in js/common.js - every tool had a byte-for-byte
   equivalent copy of it. common.js is loaded first on every page. */


// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Generate initial emails
    generateEmails();
});