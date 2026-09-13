// ============================================
// SPEED TEST - COMPLETE JS
// ============================================

// ==================== STATE ====================
var speedTestState = {
    isRunning: false,
    isExpanded: false,
    downloadSpeed: 0,
    uploadSpeed: 0,
    ping: 0,
    jitter: 0,
    history: []
};

// ==================== DOM REFS ====================
var speedNumber = document.getElementById('speedtestNumber');
var speedProgress = document.getElementById('speedtestProgress');
var speedDownload = document.getElementById('speedtestDownload');
var speedUpload = document.getElementById('speedtestUpload');
var speedPing = document.getElementById('speedtestPing');
var speedJitter = document.getElementById('speedtestJitter');
var speedBtn = document.getElementById('speedtestBtn');
var speedBtnText = document.getElementById('speedtestBtnText');
var speedBtnIcon = document.getElementById('speedtestBtnIcon');
var statusDot = document.getElementById('statusDot');
var statusText = document.getElementById('statusText');
var statusIP = document.getElementById('statusIP');
var historyList = document.getElementById('speedtestHistoryList');
var detailsSection = document.getElementById('speedtestDetails');
var detailsContent = document.getElementById('speedtestDetailsContent');
var speedArrow = document.getElementById('speedtestArrow');

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    speedTestLoadHistory();
    speedTestGetIP();
    speedTestUpdateStatus('ready', 'Ready');
    console.log('Speed Test loaded successfully!');
});

// ==================== START TEST ====================
function speedTestStart() {
    if (speedTestState.isRunning) return;

    speedTestState.isRunning = true;
    speedNumber.textContent = '0';
    speedNumber.className = 'speedtest-number testing';
    speedBtn.disabled = true;
    speedBtnText.textContent = 'Testing...';
    speedBtnIcon.className = 'fas fa-spinner fa-spin';
    speedDownload.textContent = '0 Mbps';
    speedUpload.textContent = '0 Mbps';
    speedPing.textContent = '0 ms';
    speedJitter.textContent = '0 ms';
    detailsSection.style.display = 'none';

    speedTestUpdateStatus('testing', 'Testing...');
    speedTestShowToast('Testing your internet speed...', 'info');

    // Reset progress circle
    speedProgress.style.strokeDashoffset = '339.292';
    speedProgress.classList.remove('complete');

    // Simulate speed test phases
    var phases = [
        { duration: 1000, label: 'Measuring ping...' },
        { duration: 2500, label: 'Testing download...' },
        { duration: 2500, label: 'Testing upload...' },
        { duration: 1500, label: 'Calculating jitter...' }
    ];

    var phaseIndex = 0;

    function runPhase() {
        if (phaseIndex >= phases.length) {
            speedTestFinish();
            return;
        }

        var phase = phases[phaseIndex];
        var phaseStart = Date.now();

        var interval = setInterval(function() {
            var elapsed = Date.now() - phaseStart;
            var progress = Math.min(1, elapsed / phase.duration);
            var circumference = 339.292;
            var offset = circumference - (progress * circumference);
            speedProgress.style.strokeDashoffset = offset;

            if (phaseIndex === 0) {
                // Ping
                var ping = 5 + Math.random() * 45;
                speedTestState.ping = Math.round(ping);
                speedPing.textContent = speedTestState.ping + ' ms';
            } else if (phaseIndex === 1) {
                // Download
                var download = 10 + Math.random() * 90;
                speedTestState.downloadSpeed = Math.round(download * 10) / 10;
                speedNumber.textContent = speedTestState.downloadSpeed;
                speedDownload.textContent = speedTestState.downloadSpeed + ' Mbps';
                speedProgress.style.stroke = '#28a745';
            } else if (phaseIndex === 2) {
                // Upload
                var upload = 5 + Math.random() * 45;
                speedTestState.uploadSpeed = Math.round(upload * 10) / 10;
                speedUpload.textContent = speedTestState.uploadSpeed + ' Mbps';
            } else if (phaseIndex === 3) {
                // Jitter
                var jitter = 1 + Math.random() * 9;
                speedTestState.jitter = Math.round(jitter * 10) / 10;
                speedJitter.textContent = speedTestState.jitter + ' ms';
            }

            if (elapsed >= phase.duration) {
                clearInterval(interval);
                phaseIndex++;
                setTimeout(runPhase, 200);
            }
        }, 50);
    }

    runPhase();
}

// ==================== FINISH TEST ====================
function speedTestFinish() {
    speedTestState.isRunning = false;
    speedNumber.className = 'speedtest-number';
    speedBtn.disabled = false;
    speedBtnText.textContent = 'Start Test';
    speedBtnIcon.className = 'fas fa-play';
    speedProgress.style.strokeDashoffset = '0';
    speedProgress.classList.add('complete');

    speedTestUpdateStatus('complete', 'Complete');
    speedTestShowToast('Speed test completed!', 'success');

    // Show details
    setTimeout(function() {
        speedTestShowDetails();
    }, 500);

    // Save to history
    speedTestSaveHistory();
}

// ==================== SHOW DETAILS ====================
function speedTestShowDetails() {
    var now = new Date();
    var dateStr = now.toLocaleDateString() + ' ' + now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    
    document.getElementById('speedtestDate').textContent = dateStr;
    document.getElementById('speedtestDownloadDetail').textContent = speedTestState.downloadSpeed + ' Mbps';
    document.getElementById('speedtestUploadDetail').textContent = speedTestState.uploadSpeed + ' Mbps';
    document.getElementById('speedtestPingDetail').textContent = speedTestState.ping + ' ms';
    document.getElementById('speedtestJitterDetail').textContent = speedTestState.jitter + ' ms';
    
    var rating = speedTestGetRating(speedTestState.downloadSpeed);
    document.getElementById('speedtestRating').textContent = rating.emoji + ' ' + rating.label;

    detailsSection.style.display = 'block';
}

// ==================== TOGGLE DETAILS ====================
function speedTestToggleDetails() {
    if (detailsContent.style.display === 'none') {
        detailsContent.style.display = 'block';
        speedArrow.classList.add('rotated');
    } else {
        detailsContent.style.display = 'none';
        speedArrow.classList.remove('rotated');
    }
}

// ==================== GET RATING ====================
function speedTestGetRating(speed) {
    if (speed > 80) return { emoji: '🚀', label: 'Excellent' };
    if (speed > 50) return { emoji: '⚡', label: 'Very Fast' };
    if (speed > 25) return { emoji: '👍', label: 'Good' };
    if (speed > 10) return { emoji: '📶', label: 'Moderate' };
    return { emoji: '🐢', label: 'Slow' };
}

// ==================== HISTORY ====================
function speedTestLoadHistory() {
    try {
        var data = localStorage.getItem('speedTestHistory');
        if (data) {
            speedTestState.history = JSON.parse(data);
        }
    } catch (e) {
        speedTestState.history = [];
    }
    speedTestRenderHistory();
}

function speedTestSaveHistory() {
    var entry = {
        date: new Date().toISOString(),
        download: speedTestState.downloadSpeed,
        upload: speedTestState.uploadSpeed,
        ping: speedTestState.ping,
        jitter: speedTestState.jitter
    };

    speedTestState.history.unshift(entry);
    if (speedTestState.history.length > 20) {
        speedTestState.history = speedTestState.history.slice(0, 20);
    }

    localStorage.setItem('speedTestHistory', JSON.stringify(speedTestState.history));
    speedTestRenderHistory();
}

function speedTestRenderHistory() {
    if (speedTestState.history.length === 0) {
        historyList.innerHTML = `
            <div class="empty-history">
                <i class="fas fa-inbox"></i>
                <p>No tests performed yet</p>
            </div>
        `;
        return;
    }

    var html = '';
    speedTestState.history.forEach(function(item) {
        var date = new Date(item.date);
        var dateStr = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        var rating = speedTestGetRating(item.download);
        html += `
            <div class="history-item">
                <span class="h-date">${dateStr}</span>
                <span class="h-download">⬇ ${item.download} Mbps</span>
                <span class="h-upload">⬆ ${item.upload} Mbps</span>
                <span class="h-ping">📶 ${item.ping} ms</span>
                <span class="h-rating">${rating.emoji}</span>
            </div>
        `;
    });
    historyList.innerHTML = html;
}

function speedTestClearHistory() {
    if (confirm('Clear all test history?')) {
        speedTestState.history = [];
        localStorage.removeItem('speedTestHistory');
        speedTestRenderHistory();
        speedTestShowToast('History cleared!', 'info');
    }
}

// ==================== RESET ====================
function speedTestReset() {
    if (speedTestState.isRunning) return;
    
    speedNumber.textContent = '0';
    speedDownload.textContent = '0 Mbps';
    speedUpload.textContent = '0 Mbps';
    speedPing.textContent = '0 ms';
    speedJitter.textContent = '0 ms';
    speedProgress.style.strokeDashoffset = '339.292';
    speedProgress.classList.remove('complete');
    detailsSection.style.display = 'none';
    speedTestUpdateStatus('ready', 'Ready');
    speedTestShowToast('Reset complete', 'info');
}

// ==================== STATUS ====================
function speedTestUpdateStatus(state, text) {
    statusDot.className = 'status-dot ' + state;
    statusText.textContent = text;
}

// ==================== GET IP ====================
function speedTestGetIP() {
    fetch('https://api.ipify.org?format=json')
        .then(function(response) { return response.json(); })
        .then(function(data) {
            statusIP.textContent = data.ip || '-';
        })
        .catch(function() {
            statusIP.textContent = 'Unavailable';
        });
}

// ==================== TOAST ====================
function speedTestShowToast(message, type) {
    var toast = document.getElementById('speedtestToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'speedtestToast';
        toast.className = 'speedtest-toast';
        document.body.appendChild(toast);
    }

    toast.textContent = message;
    toast.className = 'speedtest-toast show ' + (type || 'info');

    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(function() {
        toast.className = 'speedtest-toast';
    }, 3000);
}

// ==================== EXPOSE GLOBALLY ====================
window.speedTestStart = speedTestStart;
window.speedTestReset = speedTestReset;
window.speedTestClearHistory = speedTestClearHistory;
window.speedTestToggleDetails = speedTestToggleDetails;