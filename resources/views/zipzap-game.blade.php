@extends('layouts.app')

@section('content')
<div class="zipzap-wrapper">
    <div class="zipzap-container">
        <!-- Header -->
        <div class="game-header">
            <div class="header-left">
                <h1>⚡ Zip Zap</h1>
                <p class="subtitle">Connect the numbers in sequence. Tap fast, think smart!</p>
            </div>
            <div class="header-right">
                <span class="level-badge">🎯 Level <span id="levelDisplay">1</span></span>
                <span class="streak-badge">🔥 <span id="streakCount">0</span></span>
            </div>
        </div>

        <!-- Game Stats -->
        <div class="game-stats">
            <div class="stat-box">
                <span class="stat-label">⭐ Score</span>
                <span class="stat-value" id="scoreDisplay">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">✅ Connected</span>
                <span class="stat-value" id="connectedDisplay">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">❌ Missed</span>
                <span class="stat-value" id="missedDisplay">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">⏱️ Time</span>
                <span class="stat-value" id="timerDisplay">00:00</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">🏆 Best</span>
                <span class="stat-value" id="bestScoreDisplay">-</span>
            </div>
        </div>

        <!-- Game Board -->
        <div class="game-board-wrapper">
            <div class="game-board" id="gameBoard">
                <!-- Numbers will be rendered here -->
            </div>
        </div>

        <!-- Next Number Indicator -->
        <div class="next-number">
            <span>🔢 Next: <strong id="nextNumberDisplay">1</strong></span>
            <span class="progress-text" id="progressText">0 / 0</span>
        </div>

        <!-- Game Controls -->
        <div class="game-controls">
            <button onclick="window.zipzapStartNewGame()" class="btn-new-game">🔄 New Game</button>
            <button onclick="window.zipzapResetGame()" class="btn-reset-game">🔃 Reset</button>
            <div class="difficulty-selector">
                <span>Size:</span>
                <select id="difficulty" onchange="window.zipzapChangeDifficulty()">
                    <option value="easy">Easy (4x4)</option>
                    <option value="medium" selected>Medium (5x5)</option>
                    <option value="hard">Hard (6x6)</option>
                </select>
            </div>
        </div>

        <!-- Instructions -->
        <div class="instructions">
            <div class="instruction-item">
                <span class="instruction-icon">👆</span>
                <span>Tap numbers in order</span>
            </div>
            <div class="instruction-item">
                <span class="instruction-icon">🧠</span>
                <span>Connect without missing</span>
            </div>
            <div class="instruction-item">
                <span class="instruction-icon">⚡</span>
                <span>Complete to level up!</span>
            </div>
        </div>

        <!-- Victory Modal -->
        <div class="modal-overlay" id="victoryModal" style="display:none;">
            <div class="modal-content">
                <div class="modal-icon">🏆</div>
                <h2>Level Complete! 🎉</h2>
                <p id="victoryMessage">Amazing! You connected all numbers!</p>
                <div class="modal-stats">
                    <div class="modal-stat">
                        <span>Score</span>
                        <span id="finalScore">0</span>
                    </div>
                    <div class="modal-stat">
                        <span>Connected</span>
                        <span id="finalConnected">0</span>
                    </div>
                    <div class="modal-stat">
                        <span>Time</span>
                        <span id="finalTime">00:00</span>
                    </div>
                </div>
                <button onclick="window.zipzapNextLevel()" class="btn-play-again">🚀 Next Level</button>
                <button onclick="window.zipzapCloseModal()" class="btn-close-modal">Close</button>
            </div>
        </div>

        <!-- Score History -->
        <div class="score-history">
            <div class="history-header">
                <h3>📊 Game History</h3>
                <button onclick="window.zipzapClearHistory()" class="btn-clear-history">🗑️ Clear History</button>
            </div>
            <div id="scoreHistoryList" class="history-list">
                <p style="color: #666; text-align: center; padding: 20px;">No games played yet. Start playing!</p>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="toast"></div>
@endsection

<!-- @push('styles')
<link rel="stylesheet" href="{{ asset('css/zipzap-game.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/zipzap-game.js') }}"></script>
@endpush -->