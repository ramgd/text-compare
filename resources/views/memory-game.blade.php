@extends('layouts.app')

@section('content')
<style>
/* ============================================
   MEMORY CARD MATCH - COMPLETE STYLES
   ============================================ */

.memory-game-wrapper {
    padding: 20px;
    max-width: 900px;
    margin: 0 auto;
    min-height: calc(100vh - 200px);
}

.memory-game-wrapper .game-container {
    background: linear-gradient(145deg, #1a1a2e, #16213e);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    color: #fff;
}

.memory-game-wrapper .game-header {
    text-align: center;
    margin-bottom: 20px;
}

.memory-game-wrapper .game-header h1 {
    font-size: 36px;
    font-weight: 700;
    margin: 0;
    background: linear-gradient(135deg, #f7971e, #ffd200);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.memory-game-wrapper .game-header .subtitle {
    color: #aaa;
    font-size: 16px;
    margin: 5px 0 0 0;
}

/* Stats */
.memory-game-wrapper .game-stats {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 10px;
    margin-bottom: 20px;
}

.memory-game-wrapper .stat-box {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px;
    padding: 12px;
    text-align: center;
    transition: all 0.3s;
}

.memory-game-wrapper .stat-box:hover {
    background: rgba(255,255,255,0.08);
    transform: translateY(-2px);
}

.memory-game-wrapper .stat-label {
    display: block;
    font-size: 12px;
    color: #999;
    margin-bottom: 3px;
}

.memory-game-wrapper .stat-value {
    display: block;
    font-size: 22px;
    font-weight: 700;
    color: #ffd700;
}

/* Controls */
.memory-game-wrapper .game-controls {
    display: flex;
    gap: 15px;
    align-items: center;
    justify-content: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.memory-game-wrapper .game-controls button {
    padding: 10px 25px;
    border: none;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.memory-game-wrapper .btn-new-game {
    background: linear-gradient(135deg, #00b894, #00cec9);
    color: #fff;
}

.memory-game-wrapper .btn-new-game:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 206, 201, 0.4);
}

.memory-game-wrapper .btn-reset-game {
    background: linear-gradient(135deg, #6c5ce7, #a29bfe);
    color: #fff;
}

.memory-game-wrapper .btn-reset-game:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(108, 92, 231, 0.4);
}

.memory-game-wrapper .difficulty-selector {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #ccc;
    font-size: 14px;
}

.memory-game-wrapper .difficulty-selector select {
    padding: 8px 15px;
    border-radius: 8px;
    border: 1px solid #333;
    background: rgba(255,255,255,0.05);
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    outline: none;
}

.memory-game-wrapper .difficulty-selector select option {
    background: #1a1a2e;
    color: #fff;
}

/* Game Board */
.memory-game-wrapper .game-board {
    display: grid;
    gap: 10px;
    max-width: 700px;
    margin: 0 auto;
    padding: 10px;
}

/* Cards */
.memory-game-wrapper .card {
    aspect-ratio: 1;
    background: linear-gradient(145deg, #2d2d44, #3d3d5c);
    border-radius: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    min-height: 60px;
    perspective: 600px;
}

.memory-game-wrapper .card:hover:not(.matched):not(.flipped) {
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.3);
}

.memory-game-wrapper .card.flipped {
    background: linear-gradient(145deg, #2d2d44, #3d3d5c);
}

.memory-game-wrapper .card.matched {
    background: linear-gradient(145deg, #00b894, #00cec9);
    cursor: default;
    animation: matchPulse 0.6s ease;
}

.memory-game-wrapper .card .card-back,
.memory-game-wrapper .card .card-front {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    font-size: 36px;
    user-select: none;
}

.memory-game-wrapper .card .card-back {
    transform: translate(-50%, -50%) rotateY(0deg);
    font-size: 28px;
    color: #6c5ce7;
}

.memory-game-wrapper .card .card-front {
    transform: translate(-50%, -50%) rotateY(180deg);
}

.memory-game-wrapper .card.flipped .card-back {
    transform: translate(-50%, -50%) rotateY(-180deg);
}

.memory-game-wrapper .card.flipped .card-front {
    transform: translate(-50%, -50%) rotateY(0deg);
}

.memory-game-wrapper .card > div {
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes matchPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); box-shadow: 0 0 30px rgba(0, 206, 201, 0.5); }
    100% { transform: scale(1); }
}

/* Victory Modal */
.memory-game-wrapper .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.memory-game-wrapper .modal-content {
    background: linear-gradient(145deg, #1a1a2e, #16213e);
    border-radius: 20px;
    padding: 40px;
    max-width: 450px;
    width: 90%;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.1);
    animation: modalIn 0.5s ease;
}

@keyframes modalIn {
    from {
        opacity: 0;
        transform: scale(0.8) translateY(50px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.memory-game-wrapper .modal-icon {
    font-size: 80px;
    margin-bottom: 10px;
}

.memory-game-wrapper .modal-content h2 {
    font-size: 32px;
    margin: 10px 0;
    color: #ffd700;
}

.memory-game-wrapper .modal-content p {
    color: #aaa;
    margin-bottom: 20px;
}

.memory-game-wrapper .modal-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin: 20px 0;
}

.memory-game-wrapper .modal-stat {
    background: rgba(255,255,255,0.05);
    padding: 15px;
    border-radius: 10px;
}

.memory-game-wrapper .modal-stat span:first-child {
    display: block;
    font-size: 12px;
    color: #888;
}

.memory-game-wrapper .modal-stat span:last-child {
    display: block;
    font-size: 24px;
    font-weight: 700;
    color: #ffd700;
}

.memory-game-wrapper .btn-play-again {
    padding: 12px 35px;
    background: linear-gradient(135deg, #00b894, #00cec9);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 18px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-right: 10px;
}

.memory-game-wrapper .btn-play-again:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0, 206, 201, 0.4);
}

.memory-game-wrapper .btn-close-modal {
    padding: 12px 35px;
    background: rgba(255,255,255,0.1);
    border: 1px solid #333;
    border-radius: 10px;
    color: #ccc;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s;
}

.memory-game-wrapper .btn-close-modal:hover {
    background: rgba(255,255,255,0.2);
}

/* Score History */
.memory-game-wrapper .score-history {
    margin-top: 25px;
    padding: 20px;
    background: rgba(255,255,255,0.03);
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.05);
}

.memory-game-wrapper .history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.memory-game-wrapper .history-header h3 {
    margin: 0;
    color: #ffd700;
    font-size: 18px;
}

.memory-game-wrapper .btn-clear-history {
    padding: 6px 15px;
    background: rgba(255,255,255,0.05);
    border: 1px solid #333;
    border-radius: 6px;
    color: #ff6b6b;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s;
}

.memory-game-wrapper .btn-clear-history:hover {
    background: rgba(255,107,107,0.1);
    border-color: #ff6b6b;
}

.memory-game-wrapper .history-list {
    max-height: 150px;
    overflow-y: auto;
}

.memory-game-wrapper .history-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: rgba(255,255,255,0.03);
    border-radius: 6px;
    margin-bottom: 4px;
    font-size: 13px;
}

.memory-game-wrapper .history-item .history-date {
    color: #888;
    font-size: 11px;
}

.memory-game-wrapper .history-item .history-score {
    color: #ffd700;
    font-weight: 600;
}

/* Scrollbar */
.memory-game-wrapper .history-list::-webkit-scrollbar {
    width: 4px;
}

.memory-game-wrapper .history-list::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.05);
    border-radius: 2px;
}

.memory-game-wrapper .history-list::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.2);
    border-radius: 2px;
}

/* Toast */
.memory-game-wrapper .toast {
    visibility: hidden;
    min-width: 250px;
    background: #333;
    color: #fff;
    text-align: center;
    border-radius: 8px;
    padding: 12px 20px;
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    z-index: 9999;
    opacity: 0;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 14px;
}

.memory-game-wrapper .toast.show {
    visibility: visible;
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.memory-game-wrapper .toast.success {
    background: #28a745;
}
.memory-game-wrapper .toast.error {
    background: #dc3545;
}
.memory-game-wrapper .toast.info {
    background: #17a2b8;
}

/* Responsive */
@media (max-width: 768px) {
    .memory-game-wrapper .game-stats {
        grid-template-columns: repeat(3, 1fr);
    }

    .memory-game-wrapper .game-controls {
        flex-direction: column;
        align-items: stretch;
    }

    .memory-game-wrapper .game-controls button {
        width: 100%;
    }

    .memory-game-wrapper .difficulty-selector {
        justify-content: center;
    }

    .memory-game-wrapper .card .card-back,
    .memory-game-wrapper .card .card-front {
        font-size: 28px;
    }

    .memory-game-wrapper .card .card-back {
        font-size: 20px;
    }

    .memory-game-wrapper .modal-stats {
        grid-template-columns: 1fr;
    }

    .memory-game-wrapper .game-header h1 {
        font-size: 28px;
    }

    .memory-game-wrapper .history-item {
        flex-wrap: wrap;
        gap: 5px;
    }
}

@media (max-width: 480px) {
    .memory-game-wrapper {
        padding: 10px;
    }

    .memory-game-wrapper .game-container {
        padding: 15px;
    }

    .memory-game-wrapper .game-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .memory-game-wrapper .stat-value {
        font-size: 18px;
    }

    .memory-game-wrapper .card .card-back,
    .memory-game-wrapper .card .card-front {
        font-size: 20px;
    }

    .memory-game-wrapper .card .card-back {
        font-size: 16px;
    }

    .memory-game-wrapper .card {
        min-height: 40px;
    }
}
</style>

<div class="memory-game-wrapper">
    <div class="game-container">
        <!-- Header -->
        <div class="game-header">
            <h1>🧠 Memory Card Match</h1>
            <p class="subtitle">Find the matching pairs! Test your memory skills.</p>
        </div>

        <!-- Game Stats -->
        <div class="game-stats">
            <div class="stat-box">
                <span class="stat-label">🎯 Matches</span>
                <span class="stat-value" id="matches">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">👆 Moves</span>
                <span class="stat-value" id="moves">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">⏱️ Time</span>
                <span class="stat-value" id="timer">00:00</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">⭐ Score</span>
                <span class="stat-value" id="score">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">🏆 Best Score</span>
                <span class="stat-value" id="bestScore">-</span>
            </div>
        </div>

        <!-- Game Controls -->
        <div class="game-controls">
            <button onclick="startNewGame()" class="btn-new-game">🔄 New Game</button>
            <button onclick="resetGame()" class="btn-reset-game">🔃 Reset</button>
            <div class="difficulty-selector">
                <span>Difficulty:</span>
                <select id="difficulty" onchange="changeDifficulty()">
                    <option value="easy">Easy (4x3)</option>
                    <option value="medium" selected>Medium (4x4)</option>
                    <option value="hard">Hard (6x6)</option>
                </select>
            </div>
        </div>

        <!-- Game Board -->
        <div class="game-board" id="gameBoard">
            <!-- Cards will be rendered here -->
        </div>

        <!-- Victory Modal -->
        <div class="modal-overlay" id="victoryModal" style="display:none;">
            <div class="modal-content">
                <div class="modal-icon">🏆</div>
                <h2>You Win!</h2>
                <p id="victoryMessage">Congratulations! You completed the game!</p>
                <div class="modal-stats">
                    <div class="modal-stat">
                        <span>Moves</span>
                        <span id="finalMoves">0</span>
                    </div>
                    <div class="modal-stat">
                        <span>Time</span>
                        <span id="finalTime">00:00</span>
                    </div>
                    <div class="modal-stat">
                        <span>Score</span>
                        <span id="finalScore">0</span>
                    </div>
                </div>
                <button onclick="startNewGame()" class="btn-play-again">🎮 Play Again</button>
                <button onclick="closeModal()" class="btn-close-modal">Close</button>
            </div>
        </div>

        <!-- Score History -->
        <div class="score-history">
            <div class="history-header">
                <h3>📊 Score History</h3>
                <button onclick="clearHistory()" class="btn-clear-history">🗑️ Clear History</button>
            </div>
            <div id="scoreHistoryList" class="history-list">
                <p style="color: #666; text-align: center; padding: 20px;">No games played yet. Start playing to save your scores!</p>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="toast"></div>

<script>
// ============================================
// MEMORY CARD MATCH - COMPLETE GAME JS
// ============================================

// ==================== GAME STATE ====================
var gameState = {
    cards: [],
    flippedCards: [],
    matchedPairs: 0,
    totalPairs: 0,
    moves: 0,
    score: 0,
    isLocked: false,
    timerStarted: false,
    timerInterval: null,
    seconds: 0,
    difficulty: 'medium'
};

var emojis = [
    '🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼',
    '🐨', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐔',
    '🐧', '🐦', '🐤', '🦄', '🐴', '🦋', '🐛', '🐝',
    '🐞', '🦀', '🐳', '🐬', '🐟', '🐠', '🐡', '🦈'
];

var difficultyConfig = {
    easy: { rows: 4, cols: 3, pairs: 6 },
    medium: { rows: 4, cols: 4, pairs: 8 },
    hard: { rows: 6, cols: 6, pairs: 18 }
};

// ==================== SCORE HISTORY ====================
function getScoreHistory() {
    try {
        return JSON.parse(localStorage.getItem('memoryGameHistory')) || [];
    } catch {
        return [];
    }
}

function saveScoreToHistory(score, moves, time, difficulty) {
    var history = getScoreHistory();
    history.push({
        score: score,
        moves: moves,
        time: time,
        difficulty: difficulty,
        date: new Date().toISOString()
    });
    if (history.length > 50) {
        history = history.slice(-50);
    }
    localStorage.setItem('memoryGameHistory', JSON.stringify(history));
    renderScoreHistory();
}

function renderScoreHistory() {
    var history = getScoreHistory();
    var list = document.getElementById('scoreHistoryList');
    
    if (history.length === 0) {
        list.innerHTML = '<p style="color: #666; text-align: center; padding: 20px;">No games played yet. Start playing to save your scores!</p>';
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
                <span>👆 ${item.moves} moves</span>
                <span>⏱️ ${item.time}</span>
                <span class="history-date">${dateStr}</span>
            </div>
        `;
    });
    list.innerHTML = html;
}

function clearHistory() {
    if (confirm('Are you sure you want to clear all score history?')) {
        localStorage.removeItem('memoryGameHistory');
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

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    renderScoreHistory();
    updateBestScore();
    startNewGame();
});

// ==================== GAME FUNCTIONS ====================
function startNewGame() {
    clearInterval(gameState.timerInterval);
    gameState.timerInterval = null;
    gameState.seconds = 0;
    gameState.moves = 0;
    gameState.matchedPairs = 0;
    gameState.score = 0;
    gameState.flippedCards = [];
    gameState.isLocked = false;
    gameState.timerStarted = false;

    document.getElementById('moves').textContent = '0';
    document.getElementById('timer').textContent = '00:00';
    document.getElementById('matches').textContent = '0';
    document.getElementById('score').textContent = '0';
    document.getElementById('victoryModal').style.display = 'none';
    updateBestScore();

    var config = difficultyConfig[gameState.difficulty];
    gameState.totalPairs = config.pairs;

    generateCards(config.pairs);
    renderCards(config.rows, config.cols);
}

function generateCards(pairs) {
    var selectedEmojis = [];
    var shuffledEmojis = [...emojis];
    for (var i = 0; i < pairs; i++) {
        var randomIndex = Math.floor(Math.random() * shuffledEmojis.length);
        selectedEmojis.push(shuffledEmojis[randomIndex]);
        shuffledEmojis.splice(randomIndex, 1);
    }

    var cards = [];
    selectedEmojis.forEach(function(emoji) {
        cards.push({ id: emoji + 'a', emoji: emoji, matched: false, flipped: false });
        cards.push({ id: emoji + 'b', emoji: emoji, matched: false, flipped: false });
    });

    for (var i = cards.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = cards[i];
        cards[i] = cards[j];
        cards[j] = temp;
    }

    gameState.cards = cards;
}

function renderCards(rows, cols) {
    var board = document.getElementById('gameBoard');
    board.style.gridTemplateColumns = 'repeat(' + cols + ', 1fr)';
    board.innerHTML = '';

    gameState.cards.forEach(function(card, index) {
        var cardDiv = document.createElement('div');
        cardDiv.className = 'card';
        cardDiv.dataset.index = index;
        cardDiv.innerHTML = `
            <div class="card-back">❓</div>
            <div class="card-front">${card.emoji}</div>
        `;
        cardDiv.addEventListener('click', function() {
            flipCard(index);
        });
        board.appendChild(cardDiv);
    });
}

function flipCard(index) {
    if (gameState.isLocked) return;
    if (!gameState.cards[index]) return;
    if (gameState.cards[index].flipped) return;
    if (gameState.cards[index].matched) return;
    if (gameState.flippedCards.length >= 2) return;

    if (!gameState.timerStarted) {
        gameState.timerStarted = true;
        gameState.timerInterval = setInterval(updateTimer, 1000);
    }

    var cardElement = document.querySelectorAll('.card')[index];
    gameState.cards[index].flipped = true;
    cardElement.classList.add('flipped');

    gameState.flippedCards.push(index);
    gameState.moves++;
    document.getElementById('moves').textContent = gameState.moves;

    if (gameState.flippedCards.length === 2) {
        gameState.isLocked = true;
        setTimeout(checkMatch, 600);
    }
}

function checkMatch() {
    var index1 = gameState.flippedCards[0];
    var index2 = gameState.flippedCards[1];
    var card1 = gameState.cards[index1];
    var card2 = gameState.cards[index2];
    var cardElements = document.querySelectorAll('.card');

    if (card1.emoji === card2.emoji) {
        card1.matched = true;
        card2.matched = true;
        cardElements[index1].classList.add('matched');
        cardElements[index2].classList.add('matched');

        gameState.matchedPairs++;
        gameState.score += 10 + Math.floor(30 / (gameState.moves / 10));
        document.getElementById('matches').textContent = gameState.matchedPairs;
        document.getElementById('score').textContent = gameState.score;

        if (gameState.matchedPairs === gameState.totalPairs) {
            clearInterval(gameState.timerInterval);
            setTimeout(showVictory, 500);
        }
    } else {
        card1.flipped = false;
        card2.flipped = false;
        cardElements[index1].classList.remove('flipped');
        cardElements[index2].classList.remove('flipped');
    }

    gameState.flippedCards = [];
    gameState.isLocked = false;
}

function updateTimer() {
    gameState.seconds++;
    var minutes = Math.floor(gameState.seconds / 60);
    var secs = gameState.seconds % 60;
    document.getElementById('timer').textContent = 
        String(minutes).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
}

function showVictory() {
    var timeStr = document.getElementById('timer').textContent;
    var finalScore = gameState.score;
    
    document.getElementById('finalMoves').textContent = gameState.moves;
    document.getElementById('finalTime').textContent = timeStr;
    document.getElementById('finalScore').textContent = finalScore;

    saveScoreToHistory(finalScore, gameState.moves, timeStr, gameState.difficulty);
    updateBestScore();

    var message = '';
    if (gameState.moves <= gameState.totalPairs * 1.2) {
        message = '🌟 Amazing! You have a photographic memory!';
    } else if (gameState.moves <= gameState.totalPairs * 1.8) {
        message = '🎯 Great job! Your memory is impressive!';
    } else if (gameState.moves <= gameState.totalPairs * 2.5) {
        message = '👏 Good work! Keep practicing!';
    } else {
        message = '💪 Nice try! Play again to improve!';
    }
    document.getElementById('victoryMessage').textContent = message;
    document.getElementById('victoryModal').style.display = 'flex';
}

function resetGame() {
    clearInterval(gameState.timerInterval);
    gameState.timerInterval = null;
    gameState.timerStarted = false;
    gameState.seconds = 0;
    gameState.moves = 0;
    gameState.matchedPairs = 0;
    gameState.score = 0;
    gameState.flippedCards = [];
    gameState.isLocked = false;

    document.getElementById('moves').textContent = '0';
    document.getElementById('timer').textContent = '00:00';
    document.getElementById('matches').textContent = '0';
    document.getElementById('score').textContent = '0';
    document.getElementById('victoryModal').style.display = 'none';

    gameState.cards.forEach(function(card) {
        card.flipped = false;
        card.matched = false;
    });

    var cardElements = document.querySelectorAll('.card');
    cardElements.forEach(function(element) {
        element.classList.remove('flipped');
        element.classList.remove('matched');
    });

    var cards = gameState.cards;
    for (var i = cards.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = cards[i];
        cards[i] = cards[j];
        cards[j] = temp;
    }
    gameState.cards = cards;

    var config = difficultyConfig[gameState.difficulty];
    renderCards(config.rows, config.cols);
    showToast('Game reset!', 'info');
}

function changeDifficulty() {
    var select = document.getElementById('difficulty');
    gameState.difficulty = select.value;
    startNewGame();
    showToast('Difficulty changed to ' + select.options[select.selectedIndex].text, 'info');
}

function closeModal() {
    document.getElementById('victoryModal').style.display = 'none';
}
</script>

@include('partials.tool-content')

@endsection