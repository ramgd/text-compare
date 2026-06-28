// ============================================
// TRACTOR FARMING GAME - ISOLATED (FIXED)
// ============================================

(function() {
    'use strict';

    // ==================== GAME STATE ====================
    var gameState = {
        grid: [],
        rows: 8,
        cols: 8,
        tractorX: 0,
        tractorY: 0,
        plowedCount: 0,
        totalFields: 0,
        score: 0,
        level: 1,
        timerStarted: false,
        timerInterval: null,
        seconds: 0,
        difficulty: 'medium',
        isGameOver: false
    };

    var difficultyConfig = {
        easy: { rows: 6, cols: 6 },
        medium: { rows: 8, cols: 8 },
        hard: { rows: 10, cols: 10 }
    };

    // ==================== SCORE HISTORY ====================
    function getScoreHistory() {
        try {
            return JSON.parse(localStorage.getItem('tractorGameHistory')) || [];
        } catch {
            return [];
        }
    }

    function saveScoreToHistory(score, plowed, time, difficulty, level) {
        var history = getScoreHistory();
        history.push({
            score: score,
            plowed: plowed,
            time: time,
            difficulty: difficulty,
            level: level,
            date: new Date().toISOString()
        });
        if (history.length > 50) {
            history = history.slice(-50);
        }
        localStorage.setItem('tractorGameHistory', JSON.stringify(history));
        renderScoreHistory();
    }

    function renderScoreHistory() {
        var history = getScoreHistory();
        var list = document.getElementById('scoreHistoryList');
        
        if (history.length === 0) {
            list.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No farming history yet. Start playing!</p>';
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
                    <span>🌾 ${item.plowed} fields</span>
                    <span>⏱️ ${item.time}</span>
                    <span class="history-date">${dateStr}</span>
                </div>
            `;
        });
        list.innerHTML = html;
    }

    function clearHistory() {
        if (confirm('Are you sure you want to clear all farming history?')) {
            localStorage.removeItem('tractorGameHistory');
            renderScoreHistory();
            showToast('History cleared!', 'info');
        }
    }

    // ==================== TOAST SYSTEM ====================
    function showToast(message, type) {
        var toast = document.getElementById('toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast';
            toast.className = 'toast';
            document.body.appendChild(toast);
        }
        
        toast.textContent = message;
        toast.className = 'toast show ' + (type || 'info');
        
        clearTimeout(toast._timeout);
        toast._timeout = setTimeout(function() {
            toast.className = 'toast';
        }, 3000);
    }

    // ==================== BEST SCORE ====================
    function getBestScore() {
        var history = getScoreHistory();
        if (history.length === 0) return null;
        var scores = history.map(function(item) { return item.score; });
        return Math.max.apply(null, scores);
    }

    function updateBestScore() {
        var best = getBestScore();
        document.getElementById('bestScore').textContent = best !== null ? best : '-';
    }

    // ==================== GAME FUNCTIONS ====================
    function startNewGame() {
        clearInterval(gameState.timerInterval);
        gameState.timerInterval = null;
        gameState.seconds = 0;
        gameState.plowedCount = 0;
        gameState.score = 0;
        gameState.isGameOver = false;
        gameState.timerStarted = false;
        gameState.level = 1;

        document.getElementById('gameTimer').textContent = '00:00';
        document.getElementById('gameScore').textContent = '0';
        document.getElementById('plowedCount').textContent = '0';
        document.getElementById('currentLevel').textContent = '1';
        document.getElementById('victoryModal').style.display = 'none';
        updateBestScore();

        var config = difficultyConfig[gameState.difficulty];
        gameState.rows = config.rows;
        gameState.cols = config.cols;
        gameState.totalFields = config.rows * config.cols;
        document.getElementById('totalFields').textContent = gameState.totalFields;

        generateGrid();
        renderGrid();
    }

    function generateGrid() {
        gameState.grid = [];
        for (var r = 0; r < gameState.rows; r++) {
            gameState.grid[r] = [];
            for (var c = 0; c < gameState.cols; c++) {
                gameState.grid[r][c] = false;
            }
        }
        gameState.tractorX = Math.floor(gameState.cols / 2);
        gameState.tractorY = Math.floor(gameState.rows / 2);
    }

    function renderGrid() {
        var board = document.getElementById('gameBoard');
        board.style.gridTemplateColumns = 'repeat(' + gameState.cols + ', 1fr)';
        board.innerHTML = '';

        for (var r = 0; r < gameState.rows; r++) {
            for (var c = 0; c < gameState.cols; c++) {
                var cell = document.createElement('div');
                cell.className = 'cell';
                cell.dataset.row = r;
                cell.dataset.col = c;
                
                if (r === gameState.tractorY && c === gameState.tractorX) {
                    cell.classList.add('tractor');
                    if (gameState.grid[r][c]) {
                        cell.classList.add('plowed');
                    }
                } else if (gameState.grid[r][c]) {
                    cell.classList.add('field', 'plowed');
                } else {
                    cell.classList.add('field');
                }
                
                board.appendChild(cell);
            }
        }

        document.getElementById('tractorPos').textContent = 
            '(' + gameState.tractorX + ', ' + gameState.tractorY + ')';
    }

    function moveTractor(dx, dy) {
        if (gameState.isGameOver) return;

        var newX = gameState.tractorX + dx;
        var newY = gameState.tractorY + dy;

        if (newX < 0 || newX >= gameState.cols || newY < 0 || newY >= gameState.rows) {
            showToast('🚧 You reached the edge of the field!', 'error');
            return;
        }

        if (gameState.grid[newY][newX]) {
            showToast('⚠️ This field is already plowed!', 'error');
            return;
        }

        if (!gameState.timerStarted) {
            gameState.timerStarted = true;
            gameState.timerInterval = setInterval(updateTimer, 1000);
        }

        gameState.tractorX = newX;
        gameState.tractorY = newY;

        gameState.grid[newY][newX] = true;
        gameState.plowedCount++;
        gameState.score += 10;
        document.getElementById('plowedCount').textContent = gameState.plowedCount;
        document.getElementById('gameScore').textContent = gameState.score;
        
        showToast('🌾 Plowed! +10 points', 'success');

        renderGrid();

        if (gameState.plowedCount === gameState.totalFields) {
            clearInterval(gameState.timerInterval);
            gameState.isGameOver = true;
            setTimeout(showVictory, 300);
        }
    }

    function updateTimer() {
        gameState.seconds++;
        var minutes = Math.floor(gameState.seconds / 60);
        var secs = gameState.seconds % 60;
        document.getElementById('gameTimer').textContent = 
            String(minutes).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    }

    function showVictory() {
        var timeStr = document.getElementById('gameTimer').textContent;
        var finalScore = gameState.score;
        
        var bonus = Math.max(0, 100 - gameState.seconds);
        finalScore += bonus;
        gameState.score = finalScore;
        document.getElementById('gameScore').textContent = finalScore;

        document.getElementById('finalPlowed').textContent = gameState.plowedCount;
        document.getElementById('finalTime').textContent = timeStr;
        document.getElementById('finalScore').textContent = finalScore;

        saveScoreToHistory(finalScore, gameState.plowedCount, timeStr, gameState.difficulty, gameState.level);
        updateBestScore();

        var message = '';
        if (gameState.seconds <= 30) {
            message = '🌟 Amazing! You\'re a farming master! 🚜';
        } else if (gameState.seconds <= 60) {
            message = '🎯 Great job! You plowed efficiently!';
        } else {
            message = '👏 Good work! Try to be faster next time!';
        }
        document.getElementById('victoryMessage').textContent = message;
        document.getElementById('victoryModal').style.display = 'flex';
    }

    function nextLevel() {
        gameState.level++;
        document.getElementById('currentLevel').textContent = gameState.level;
        gameState.plowedCount = 0;
        gameState.score = 0;
        gameState.seconds = 0;
        gameState.timerStarted = false;
        gameState.isGameOver = false;
        gameState.timerInterval = null;

        document.getElementById('gameTimer').textContent = '00:00';
        document.getElementById('gameScore').textContent = '0';
        document.getElementById('plowedCount').textContent = '0';
        document.getElementById('victoryModal').style.display = 'none';

        var config = difficultyConfig[gameState.difficulty];
        var extraRows = Math.min(2, Math.floor(gameState.level / 3));
        var extraCols = Math.min(2, Math.floor(gameState.level / 3));
        gameState.rows = Math.min(config.rows + extraRows, 12);
        gameState.cols = Math.min(config.cols + extraCols, 12);
        gameState.totalFields = gameState.rows * gameState.cols;
        document.getElementById('totalFields').textContent = gameState.totalFields;

        generateGrid();
        renderGrid();
        showToast('🚜 Level ' + gameState.level + ' started!', 'success');
    }

    function resetGame() {
        clearInterval(gameState.timerInterval);
        gameState.timerInterval = null;
        gameState.timerStarted = false;
        gameState.seconds = 0;
        gameState.plowedCount = 0;
        gameState.score = 0;
        gameState.isGameOver = false;
        gameState.level = 1;

        document.getElementById('gameTimer').textContent = '00:00';
        document.getElementById('gameScore').textContent = '0';
        document.getElementById('plowedCount').textContent = '0';
        document.getElementById('currentLevel').textContent = '1';
        document.getElementById('victoryModal').style.display = 'none';

        var config = difficultyConfig[gameState.difficulty];
        gameState.rows = config.rows;
        gameState.cols = config.cols;
        gameState.totalFields = config.rows * config.cols;
        document.getElementById('totalFields').textContent = gameState.totalFields;

        generateGrid();
        renderGrid();
        showToast('Game reset!', 'info');
    }

    function changeDifficulty() {
        var select = document.getElementById('difficulty');
        gameState.difficulty = select.value;
        startNewGame();
        showToast('Level changed to ' + select.options[select.selectedIndex].text, 'info');
    }

    function closeModal() {
        document.getElementById('victoryModal').style.display = 'none';
    }

    // ==================== KEYBOARD CONTROLS ====================
    document.addEventListener('keydown', function(e) {
        var key = e.key;
        if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'w', 'W', 'a', 'A', 's', 'S', 'd', 'D'].includes(key)) {
            e.preventDefault();
        }

        switch(key) {
            case 'ArrowUp':
            case 'w':
            case 'W':
                moveTractor(0, -1);
                break;
            case 'ArrowDown':
            case 's':
            case 'S':
                moveTractor(0, 1);
                break;
            case 'ArrowLeft':
            case 'a':
            case 'A':
                moveTractor(-1, 0);
                break;
            case 'ArrowRight':
            case 'd':
            case 'D':
                moveTractor(1, 0);
                break;
            case 'r':
            case 'R':
                resetGame();
                break;
        }
    });

    // Close modal on outside click
    document.addEventListener('click', function(e) {
        var modal = document.getElementById('victoryModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // ==================== EXPOSE GLOBALLY ====================
    window.tractorStartNewGame = startNewGame;
    window.tractorResetGame = resetGame;
    window.tractorChangeDifficulty = changeDifficulty;
    window.tractorCloseModal = closeModal;
    window.tractorNextLevel = nextLevel;
    window.tractorClearHistory = clearHistory;

})();

console.log('Tractor Game loaded successfully!');