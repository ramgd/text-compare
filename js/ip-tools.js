// ============================================
// IP TOOLS - COMPLETE JAVASCRIPT (FIXED)
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

// ==================== MY IP FUNCTIONS ====================
var currentIP = '';

function getMyIP() {
    showToast('Fetching your IP address...', 'info');
    
    // Try multiple APIs for reliability
    var apis = [
        'https://api.ipify.org?format=json',
        'https://api.ip.sb/geoip'
    ];
    
    function tryApi(index) {
        if (index >= apis.length) {
            showToast('Failed to fetch IP. Please try again.', 'error');
            return;
        }
        
        fetch(apis[index])
            .then(function(response) {
                if (!response.ok) throw new Error('API failed');
                return response.json();
            })
            .then(function(data) {
                var ip = data.ip || data.ip_address || data.query || '';
                if (ip) {
                    currentIP = ip;
                    document.getElementById('myIPAddress').textContent = currentIP;
                    
                    // Get more details using ipapi.co
                    fetch('https://ipapi.co/' + currentIP + '/json/')
                        .then(function(resp) { return resp.json(); })
                        .then(function(info) {
                            if (info && !info.error) {
                                document.getElementById('ispInfo').textContent = info.org || info.isp || '-';
                                document.getElementById('countryInfo').textContent = info.country_name || info.country || '-';
                                document.getElementById('cityInfo').textContent = info.city || '-';
                                document.getElementById('timezoneInfo').textContent = info.timezone || '-';
                                document.getElementById('locationInfo').textContent = info.latitude && info.longitude ? 
                                    info.latitude + ', ' + info.longitude : '-';
                                document.getElementById('ipType').textContent = info.ip_type || 'IPv4';
                            }
                        })
                        .catch(function() {
                            // Fallback: use ip-api.com
                            fetch('http://ip-api.com/json/' + currentIP)
                                .then(function(res) { return res.json(); })
                                .then(function(info) {
                                    if (info && info.status === 'success') {
                                        document.getElementById('ispInfo').textContent = info.isp || '-';
                                        document.getElementById('countryInfo').textContent = info.country || '-';
                                        document.getElementById('cityInfo').textContent = info.city || '-';
                                        document.getElementById('timezoneInfo').textContent = info.timezone || '-';
                                        document.getElementById('locationInfo').textContent = info.lat && info.lon ? 
                                            info.lat + ', ' + info.lon : '-';
                                        document.getElementById('ipType').textContent = 'IPv4';
                                    }
                                })
                                .catch(function() {});
                        });
                    
                    showToast('IP fetched successfully!', 'success');
                } else {
                    tryApi(index + 1);
                }
            })
            .catch(function() {
                tryApi(index + 1);
            });
    }
    
    tryApi(0);
}

function copyIP() {
    if (!currentIP) {
        showToast('No IP to copy', 'error');
        return;
    }
    
    navigator.clipboard.writeText(currentIP).then(function() {
        showToast('IP copied to clipboard!', 'success');
    }).catch(function() {
        fallbackCopy(currentIP);
    });
}

function refreshIP() {
    getMyIP();
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

// ==================== IP LOOKUP FUNCTIONS ====================
function setLookupIP(ip) {
    document.getElementById('lookupInput').value = ip;
    lookupIP();
}

function lookupIP() {
    var input = document.getElementById('lookupInput').value.trim();
    if (!input) {
        showToast('Please enter an IP or domain', 'error');
        return;
    }
    
    showToast('Looking up ' + input + '...', 'info');
    
    // Clear previous results
    document.getElementById('lookupIPAddress').textContent = 'Loading...';
    document.getElementById('lookupHostname').textContent = 'Loading...';
    document.getElementById('lookupCountry').textContent = 'Loading...';
    document.getElementById('lookupRegion').textContent = 'Loading...';
    document.getElementById('lookupCity').textContent = 'Loading...';
    document.getElementById('lookupISP').textContent = 'Loading...';
    document.getElementById('lookupCoordinates').textContent = 'Loading...';
    document.getElementById('lookupTimezone').textContent = 'Loading...';
    
    // Try multiple APIs
    var isIP = /^[0-9.]+$/.test(input) || input.includes(':');
    
    // Use ip-api.com (works without API key)
    var url = 'http://ip-api.com/json/' + input + '?fields=status,message,country,regionName,city,isp,lat,lon,timezone,query,org';
    
    fetch(url)
        .then(function(response) {
            if (!response.ok) throw new Error('API failed');
            return response.json();
        })
        .then(function(data) {
            if (data.status === 'success') {
                document.getElementById('lookupIPAddress').textContent = data.query || input;
                document.getElementById('lookupHostname').textContent = data.hostname || '-';
                document.getElementById('lookupCountry').textContent = data.country || '-';
                document.getElementById('lookupRegion').textContent = data.regionName || '-';
                document.getElementById('lookupCity').textContent = data.city || '-';
                document.getElementById('lookupISP').textContent = data.isp || data.org || '-';
                document.getElementById('lookupCoordinates').textContent = data.lat && data.lon ? 
                    data.lat + ', ' + data.lon : '-';
                document.getElementById('lookupTimezone').textContent = data.timezone || '-';
                
                showToast('Lookup completed!', 'success');
            } else {
                showToast('Error: ' + (data.message || 'Lookup failed'), 'error');
                document.getElementById('lookupIPAddress').textContent = 'Error';
                document.getElementById('lookupHostname').textContent = 'Error';
                document.getElementById('lookupCountry').textContent = 'Error';
                document.getElementById('lookupRegion').textContent = 'Error';
                document.getElementById('lookupCity').textContent = 'Error';
                document.getElementById('lookupISP').textContent = 'Error';
                document.getElementById('lookupCoordinates').textContent = 'Error';
                document.getElementById('lookupTimezone').textContent = 'Error';
            }
        })
        .catch(function(error) {
            showToast('Error looking up: ' + error.message, 'error');
            document.getElementById('lookupIPAddress').textContent = 'Error';
            document.getElementById('lookupHostname').textContent = 'Error';
            document.getElementById('lookupCountry').textContent = 'Error';
            document.getElementById('lookupRegion').textContent = 'Error';
            document.getElementById('lookupCity').textContent = 'Error';
            document.getElementById('lookupISP').textContent = 'Error';
            document.getElementById('lookupCoordinates').textContent = 'Error';
            document.getElementById('lookupTimezone').textContent = 'Error';
        });
}

// ==================== VALIDATE IP FUNCTIONS ====================
function setValidateIP(ip) {
    document.getElementById('validateInput').value = ip;
    validateIP();
}

function validateIP() {
    var input = document.getElementById('validateInput').value.trim();
    if (!input) {
        showToast('Please enter an IP address', 'error');
        return;
    }
    
    document.getElementById('validateIP').textContent = input;
    
    // Check if it's a valid IP
    var isIPv4 = validateIPv4(input);
    var isIPv6 = validateIPv6(input);
    
    if (isIPv4 || isIPv6) {
        document.getElementById('validateStatus').textContent = '✅ Valid';
        document.getElementById('validateStatus').className = 'validate-value valid';
        document.getElementById('validateType').textContent = isIPv4 ? 'IPv4' : 'IPv6';
        document.getElementById('validateVersion').textContent = isIPv4 ? '4' : '6';
        document.getElementById('validatePrivate').textContent = isPrivateIP(input) ? '✅ Yes' : '❌ No';
        document.getElementById('validateLoopback').textContent = isLoopbackIP(input) ? '✅ Yes' : '❌ No';
        document.getElementById('validateMulticast').textContent = isMulticastIP(input) ? '✅ Yes' : '❌ No';
        
        showToast('Valid IP address!', 'success');
    } else {
        document.getElementById('validateStatus').textContent = '❌ Invalid';
        document.getElementById('validateStatus').className = 'validate-value invalid';
        document.getElementById('validateType').textContent = '-';
        document.getElementById('validateVersion').textContent = '-';
        document.getElementById('validatePrivate').textContent = '-';
        document.getElementById('validateLoopback').textContent = '-';
        document.getElementById('validateMulticast').textContent = '-';
        
        showToast('Invalid IP address!', 'error');
    }
}

function validateIPv4(ip) {
    var parts = ip.split('.');
    if (parts.length !== 4) return false;
    
    for (var i = 0; i < parts.length; i++) {
        var part = parseInt(parts[i]);
        if (isNaN(part) || part < 0 || part > 255) return false;
        if (parts[i] !== part.toString()) return false;
    }
    return true;
}

function validateIPv6(ip) {
    var parts = ip.split(':');
    if (parts.length < 3 || parts.length > 8) return false;
    
    for (var i = 0; i < parts.length; i++) {
        if (parts[i] === '') {
            if (i === 0 || i === parts.length - 1) continue;
            return false;
        }
        if (!/^[0-9a-fA-F]{1,4}$/.test(parts[i])) return false;
    }
    return true;
}

function isPrivateIP(ip) {
    var parts = ip.split('.');
    if (parts.length !== 4) return false;
    
    var first = parseInt(parts[0]);
    var second = parseInt(parts[1]);
    
    if (first === 10) return true;
    if (first === 172 && second >= 16 && second <= 31) return true;
    if (first === 192 && second === 168) return true;
    
    return false;
}

function isLoopbackIP(ip) {
    return ip === '127.0.0.1' || ip === '::1';
}

function isMulticastIP(ip) {
    var parts = ip.split('.');
    if (parts.length !== 4) return false;
    
    var first = parseInt(parts[0]);
    return first >= 224 && first <= 239;
}

// ==================== WHOIS FUNCTIONS ====================
function setWhois(domain) {
    document.getElementById('whoisInput').value = domain;
    whoisLookup();
}

function whoisLookup() {
    var input = document.getElementById('whoisInput').value.trim();
    if (!input) {
        showToast('Please enter a domain or IP', 'error');
        return;
    }
    
    showToast('Fetching whois data for ' + input + '...', 'info');
    
    document.getElementById('whoisDomain').textContent = input;
    document.getElementById('whoisRegistrar').textContent = 'Loading...';
    document.getElementById('whoisCreation').textContent = 'Loading...';
    document.getElementById('whoisExpiry').textContent = 'Loading...';
    document.getElementById('whoisNameservers').textContent = 'Loading...';
    document.getElementById('whoisStatus').textContent = 'Loading...';
    document.getElementById('whoisRaw').style.display = 'block';
    document.getElementById('whoisRawData').textContent = 'Loading...';
    
    // Use ip-api.com for whois-like data
    var url = 'http://ip-api.com/json/' + input + '?fields=status,message,country,regionName,city,isp,lat,lon,timezone,query,org';
    
    fetch(url)
        .then(function(response) {
            if (!response.ok) throw new Error('API failed');
            return response.json();
        })
        .then(function(data) {
            if (data.status === 'success') {
                document.getElementById('whoisDomain').textContent = input;
                document.getElementById('whoisRegistrar').textContent = data.isp || data.org || 'Not available';
                document.getElementById('whoisCreation').textContent = 'Not available via free API';
                document.getElementById('whoisExpiry').textContent = 'Not available via free API';
                document.getElementById('whoisNameservers').textContent = 'Not available via free API';
                document.getElementById('whoisStatus').textContent = 'Active';
                
                document.getElementById('whoisRawData').textContent = JSON.stringify(data, null, 2);
                showToast('Whois data fetched!', 'success');
            } else {
                throw new Error(data.message || 'Lookup failed');
            }
        })
        .catch(function(error) {
            showToast('Error fetching whois data', 'error');
            document.getElementById('whoisRegistrar').textContent = 'Could not fetch data';
            document.getElementById('whoisCreation').textContent = 'Try again later';
            document.getElementById('whoisExpiry').textContent = 'Try again later';
            document.getElementById('whoisNameservers').textContent = 'Try again later';
            document.getElementById('whoisStatus').textContent = 'Unknown';
            document.getElementById('whoisRawData').textContent = 'Error: ' + error.message;
        });
}

function copyWhoisRaw() {
    var data = document.getElementById('whoisRawData').textContent;
    if (!data || data === 'Loading...') {
        showToast('No data to copy', 'error');
        return;
    }
    
    navigator.clipboard.writeText(data).then(function() {
        showToast('Raw data copied!', 'success');
    }).catch(function() {
        fallbackCopy(data);
    });
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
    // Get IP on load
    setTimeout(function() {
        getMyIP();
    }, 500);
});