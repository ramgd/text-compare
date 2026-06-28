@extends('layouts.app')

@section('content')
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
<!-- <div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/memory-game.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/memory-game.js') }}"></script>
@endpush -->