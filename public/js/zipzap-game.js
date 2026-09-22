// ============================================
// ZIP ZAP GAME - ISOLATED (FIXED)
// LinkedIn Style Number Connection Game
// ============================================

(function() {
    'use strict';

    // ==================== GAME STATE ====================
    var gameState = {
        grid: [],
        rows: 5,
        cols: 5,
        numbers: [],
        currentNumber: 1,
        maxNumber: 0,
        connected: 0,
        score: 0,
        missed: 0,
        level: 1,
        streak: 0,
        isPlaying: false,
        isGameOver: false,
        timerStarted: false,
        timerInterval: null,
        seconds: 0,
        difficulty: 'medium',
        connectedPositions: [],
        lastConnected: null
    };

    var difficultyConfig = {
        easy: { rows: 4, cols: 4 },
        medium: { rows: 5, cols: 5 },
        hard: { rows: 6, cols: 6 }
    };

    // ==================== SCORE HISTORY ====================
    function getScoreHistory() {
        try {
            return JSON.parse(localStorage.getItem('zipzapHistory')) || [];
        } catch {
            return [];
        }
    }

    function saveScoreToHistory(score, connected, missed, time, difficulty, level) {
        var history = getScoreHistory();
        history.push({
            score: score,
            connected: connected,
            missed: missed,
            time: time,
            difficulty: difficulty,
            level: level,
            date: new Date().toISOString()
        });
        if (history.length > 50) {
            history = history.slice(-50);
        }
        localStorage.setItem('zipzapHistory', JSON.stringify(history));
        renderScoreHistory();
    }

    function renderScoreHistory() {
        var history = getScoreHistory();
        var list = document.getElementById('scoreHistoryList');
        
        if (history.length === 0) {
            list.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No games played yet. Start playing!</p>';
            return;
        }

        history.sort(function(a, b) { return b.score - a.score; });
        
        var html = '';
        history.forEach(function(item, index) {
            var date = new Date(item.date);
            var dateStr = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
            var difficultyLabels = {
                easy: '🟢 Easy',
                medium: '🟡 Medium',
                hard: '🔴 Hard'
            };
            html += `
                <div class="history-item">
                    <span>#${index + 1} ${difficultyLabels[item.difficulty] || item.difficulty}</span>
                    <span>⭐ ${item.score} pts</span>
                    <span>✅ ${item.connected} connected</span>
                    <span>⏱️ ${item.time}</span>
                    <span class="history-date">${dateStr}</span>
                </div>
            `;
        });
        list.innerHTML = html;
    }

// Add this function if not already present
function clearHistory() {
    if (confirm('Are you sure you want to clear all history?')) {
        localStorage.removeItem('zipzapHistory');
        renderScoreHistory();
        showToast('History cleared!', 'info');
    }
}

// And add this to the exports section at the bottom:
window.zipzapClearHistory = clearHistory;

    // ==================== TOAST SYSTEM ====================
    /* showToast() lives in js/common.js - every tool had a byte-for-byte
       equivalent copy of it. common.js is loaded first on every page. */


    // ==================== BEST SCORE ====================
    function getBestScore() {
        var history = getScoreHistory();
        if (history.length === 0) return null;
        var scores = history.map(function(item) { return item.score; });
        return Math.max.apply(null, scores);
    }

    function updateBestScore() {
        var best = getBestScore();
        document.getElementById('bestScoreDisplay').textContent = best !== null ? best : '-';
    }

    // ==================== GAME FUNCTIONS ====================
    function startNewGame() {
        clearInterval(gameState.timerInterval);
        gameState.timerInterval = null;
        gameState.seconds = 0;
        gameState.score = 0;
        gameState.connected = 0;
        gameState.missed = 0;
        gameState.streak = 0;
        gameState.level = 1;
        gameState.isGameOver = false;
        gameState.timerStarted = false;
        gameState.currentNumber = 1;
        gameState.connectedPositions = [];
        gameState.lastConnected = null;

        document.getElementById('timerDisplay').textContent = '00:00';
        document.getElementById('scoreDisplay').textContent = '0';
        document.getElementById('connectedDisplay').textContent = '0';
        document.getElementById('missedDisplay').textContent = '0';
        document.getElementById('streakCount').textContent = '0';
        document.getElementById('levelDisplay').textContent = '1';
        document.getElementById('victoryModal').style.display = 'none';
        updateBestScore();

        var config = difficultyConfig[gameState.difficulty];
        gameState.rows = config.rows;
        gameState.cols = config.cols;
        gameState.maxNumber = gameState.rows * gameState.cols;

        generateNumbers();
        renderGrid();
        updateNextNumber();
        showToast('🎯 Connect numbers in sequence from 1 to ' + gameState.maxNumber + '!', 'info');
    }

    function generateNumbers() {
        gameState.numbers = [];
        var total = gameState.rows * gameState.cols;
        
        var numbers = [];
        for (var i = 1; i <= total; i++) {
            numbers.push(i);
        }
        
        for (var i = numbers.length - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var temp = numbers[i];
            numbers[i] = numbers[j];
            numbers[j] = temp;
        }
        
        var idx = 0;
        for (var r = 0; r < gameState.rows; r++) {
            gameState.numbers[r] = [];
            for (var c = 0; c < gameState.cols; c++) {
                gameState.numbers[r][c] = {
                    value: numbers[idx],
                    row: r,
                    col: c,
                    connected: false,
                    active: false,
                    wrong: false
                };
                idx++;
            }
        }
    }

    function renderGrid() {
        var board = document.getElementById('gameBoard');
        board.style.gridTemplateColumns = 'repeat(' + gameState.cols + ', 1fr)';
        board.innerHTML = '';

        var overlay = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        overlay.setAttribute('class', 'connection-overlay');
        overlay.setAttribute('viewBox', '0 0 100 100');
        overlay.setAttribute('preserveAspectRatio', 'none');
        board.appendChild(overlay);

        for (var r = 0; r < gameState.rows; r++) {
            for (var c = 0; c < gameState.cols; c++) {
                var cell = document.createElement('div');
                cell.className = 'cell';
                cell.dataset.row = r;
                cell.dataset.col = c;
                
                var num = gameState.numbers[r][c];
                
                if (num.connected) {
                    cell.classList.add('completed');
                }
                if (num.active) {
                    cell.classList.add('active');
                }
                if (num.wrong) {
                    cell.classList.add('wrong');
                }
                
                var numberSpan = document.createElement('span');
                numberSpan.className = 'number';
                numberSpan.textContent = num.value;
                cell.appendChild(numberSpan);
                
                cell.addEventListener('click', function() {
                    handleCellClick(parseInt(this.dataset.row), parseInt(this.dataset.col));
                });
                
                cell.addEventListener('touchstart', function(e) {
                    e.preventDefault();
                    handleCellClick(parseInt(this.dataset.row), parseInt(this.dataset.col));
                });
                
                board.appendChild(cell);
            }
        }
        
        drawConnections();
    }

    function drawConnections() {
        var overlay = document.querySelector('.connection-overlay');
        if (!overlay) return;
        
        while (overlay.firstChild) {
            overlay.removeChild(overlay.firstChild);
        }
        
        if (gameState.connectedPositions.length < 2) return;
        
        var cellSize = 100 / gameState.cols;
        
        for (var i = 0; i < gameState.connectedPositions.length - 1; i++) {
            var from = gameState.connectedPositions[i];
            var to = gameState.connectedPositions[i + 1];
            
            var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('class', 'connection-line visible');
            line.setAttribute('x1', (from.col * cellSize + cellSize / 2) + '%');
            line.setAttribute('y1', (from.row * cellSize + cellSize / 2) + '%');
            line.setAttribute('x2', (to.col * cellSize + cellSize / 2) + '%');
            line.setAttribute('y2', (to.row * cellSize + cellSize / 2) + '%');
            line.setAttribute('stroke', '#ffd700');
            line.setAttribute('stroke-width', '3');
            line.setAttribute('stroke-linecap', 'round');
            line.setAttribute('stroke-dasharray', '0');
            line.setAttribute('filter', 'url(#glow)');
            overlay.appendChild(line);
        }
        
        var defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
        var filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
        filter.setAttribute('id', 'glow');
        filter.setAttribute('x', '-20%');
        filter.setAttribute('y', '-20%');
        filter.setAttribute('width', '140%');
        filter.setAttribute('height', '140%');
        
        var blur = document.createElementNS('http://www.w3.org/2000/svg', 'feGaussianBlur');
        blur.setAttribute('stdDeviation', '2');
        blur.setAttribute('result', 'blur');
        filter.appendChild(blur);
        
        var merge = document.createElementNS('http://www.w3.org/2000/svg', 'feMerge');
        var mergeNode1 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        mergeNode1.setAttribute('in', 'blur');
        merge.appendChild(mergeNode1);
        var mergeNode2 = document.createElementNS('http://www.w3.org/2000/svg', 'feMergeNode');
        mergeNode2.setAttribute('in', 'SourceGraphic');
        merge.appendChild(mergeNode2);
        filter.appendChild(merge);
        
        defs.appendChild(filter);
        overlay.insertBefore(defs, overlay.firstChild);
    }

    function handleCellClick(row, col) {
        if (gameState.isGameOver) return;
        if (gameState.isPlaying) return;
        
        var num = gameState.numbers[row][col];
        
        if (num.connected) {
            showToast('⚠️ This number is already connected!', 'error');
            return;
        }
        
        if (!gameState.timerStarted) {
            gameState.timerStarted = true;
            gameState.timerInterval = setInterval(updateTimer, 1000);
        }
        
        if (num.value === gameState.currentNumber) {
            num.connected = true;
            gameState.connected++;
            gameState.streak++;
            gameState.score += 10 + (gameState.streak * 2);
            gameState.currentNumber++;
            gameState.connectedPositions.push({row: row, col: col});
            gameState.lastConnected = {row: row, col: col};
            
            document.getElementById('connectedDisplay').textContent = gameState.connected;
            document.getElementById('scoreDisplay').textContent = gameState.score;
            document.getElementById('streakCount').textContent = gameState.streak;
            
            highlightCell(row, col, true);
            updateNextNumber();
            
            showToast('✅ Connected ' + (num.value) + '! +' + (10 + (gameState.streak * 2)) + ' pts', 'success');
            
            if (gameState.connected === gameState.maxNumber) {
                gameState.isGameOver = true;
                clearInterval(gameState.timerInterval);
                setTimeout(showVictory, 500);
            }
        } else {
            gameState.missed++;
            gameState.streak = 0;
            document.getElementById('missedDisplay').textContent = gameState.missed;
            document.getElementById('streakCount').textContent = '0';
            
            highlightCell(row, col, false);
            showToast('❌ Wrong! Looking for number ' + gameState.currentNumber, 'error');
        }
    }

    function highlightCell(row, col, isCorrect) {
        var cells = document.querySelectorAll('.cell');
        var targetIndex = row * gameState.cols + col;
        
        cells.forEach(function(cell, index) {
            if (index === targetIndex) {
                if (isCorrect) {
                    cell.classList.add('active');
                    setTimeout(function() {
                        cell.classList.remove('active');
                        cell.classList.add('completed');
                        renderGrid();
                    }, 400);
                } else {
                    cell.classList.add('wrong');
                    setTimeout(function() {
                        cell.classList.remove('wrong');
                    }, 500);
                }
            }
        });
    }

    function updateNextNumber() {
        document.getElementById('nextNumberDisplay').textContent = gameState.currentNumber;
        document.getElementById('progressText').textContent = 
            gameState.connected + ' / ' + gameState.maxNumber + ' connected';
    }

    function updateTimer() {
        gameState.seconds++;
        var minutes = Math.floor(gameState.seconds / 60);
        var secs = gameState.seconds % 60;
        document.getElementById('timerDisplay').textContent = 
            String(minutes).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    }

    function showVictory() {
        var timeStr = document.getElementById('timerDisplay').textContent;
        var finalScore = gameState.score;
        
        if (gameState.missed === 0) {
            var bonus = 50;
            finalScore += bonus;
            gameState.score = finalScore;
            document.getElementById('scoreDisplay').textContent = finalScore;
        }
        
        document.getElementById('finalScore').textContent = finalScore;
        document.getElementById('finalConnected').textContent = gameState.connected;
        document.getElementById('finalTime').textContent = timeStr;

        saveScoreToHistory(finalScore, gameState.connected, gameState.missed, timeStr, gameState.difficulty, gameState.level);
        updateBestScore();

        var message = '';
        if (gameState.missed === 0) {
            message = '🌟 Perfect! You connected all numbers without a single miss! 🏆';
        } else if (gameState.missed <= 2) {
            message = '🎯 Excellent! Almost perfect! You\'re a Zip Zap master!';
        } else if (gameState.missed <= 5) {
            message = '👏 Great job! Keep practicing to improve your speed!';
        } else {
            message = '💪 Nice try! Focus and try again to beat your score!';
        }
        document.getElementById('victoryMessage').textContent = message;
        document.getElementById('victoryModal').style.display = 'flex';
    }

    function nextLevel() {
        gameState.level++;
        document.getElementById('levelDisplay').textContent = gameState.level;
        gameState.connected = 0;
        gameState.missed = 0;
        gameState.streak = 0;
        gameState.isGameOver = false;
        gameState.timerStarted = false;
        gameState.seconds = 0;
        gameState.currentNumber = 1;
        gameState.connectedPositions = [];
        gameState.lastConnected = null;

        document.getElementById('timerDisplay').textContent = '00:00';
        document.getElementById('connectedDisplay').textContent = '0';
        document.getElementById('missedDisplay').textContent = '0';
        document.getElementById('streakCount').textContent = '0';
        document.getElementById('victoryModal').style.display = 'none';

        generateNumbers();
        renderGrid();
        updateNextNumber();
        showToast('🚀 Level ' + gameState.level + ' started!', 'success');
    }

    function resetGame() {
        clearInterval(gameState.timerInterval);
        gameState.timerInterval = null;
        gameState.timerStarted = false;
        gameState.seconds = 0;
        gameState.score = 0;
        gameState.connected = 0;
        gameState.missed = 0;
        gameState.streak = 0;
        gameState.level = 1;
        gameState.isGameOver = false;
        gameState.currentNumber = 1;
        gameState.connectedPositions = [];
        gameState.lastConnected = null;

        document.getElementById('timerDisplay').textContent = '00:00';
        document.getElementById('scoreDisplay').textContent = '0';
        document.getElementById('connectedDisplay').textContent = '0';
        document.getElementById('missedDisplay').textContent = '0';
        document.getElementById('streakCount').textContent = '0';
        document.getElementById('levelDisplay').textContent = '1';
        document.getElementById('victoryModal').style.display = 'none';

        var config = difficultyConfig[gameState.difficulty];
        gameState.rows = config.rows;
        gameState.cols = config.cols;
        gameState.maxNumber = gameState.rows * gameState.cols;

        generateNumbers();
        renderGrid();
        updateNextNumber();
        showToast('Game reset!', 'info');
    }

    function changeDifficulty() {
        var select = document.getElementById('difficulty');
        gameState.difficulty = select.value;
        startNewGame();
        showToast('Size changed to ' + select.options[select.selectedIndex].text, 'info');
    }

    function closeModal() {
        document.getElementById('victoryModal').style.display = 'none';
    }

    // ==================== INITIALIZATION ====================
    document.addEventListener('DOMContentLoaded', function() {
        renderScoreHistory();
        updateBestScore();
        startNewGame();
    });

    // ==================== EXPOSE GLOBALLY ====================
    window.zipzapStartNewGame = startNewGame;
    window.zipzapResetGame = resetGame;
    window.zipzapChangeDifficulty = changeDifficulty;
    window.zipzapCloseModal = closeModal;
    window.zipzapNextLevel = nextLevel;

})();