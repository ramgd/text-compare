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