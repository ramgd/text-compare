@extends('layouts.app')

@section('content')
<div class="tractor-game-wrapper">
    <div class="game-container">
        <!-- Header -->
        <div class="game-header">
            <h1>🚜 Tractor Farming Game</h1>
            <p class="subtitle">Drive your tractor and plow the fields! 🌾</p>
        </div>

        <!-- Controls Info -->
        <div class="controls-info">
            <span>⬆⬇⬅➡ <strong>WASD</strong> or <strong>Arrow Keys</strong> to move</span>
            <span>🎯 Plow all fields to complete the level!</span>
        </div>

        <!-- Game Stats -->
        <div class="game-stats">
            <div class="stat-box">
                <span class="stat-label">🌾 Plowed</span>
                <span class="stat-value" id="plowedCount">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">📦 Total Fields</span>
                <span class="stat-value" id="totalFields">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">⏱️ Time</span>
                <span class="stat-value" id="gameTimer">00:00</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">⭐ Score</span>
                <span class="stat-value" id="gameScore">0</span>
            </div>
            <div class="stat-box">
                <span class="stat-label">🏆 Best Score</span>
                <span class="stat-value" id="bestScore">-</span>
            </div>
        </div>

        <!-- Game Controls -->
        <div class="game-controls">
            <button onclick="window.tractorStartNewGame()" class="btn-new-game">🔄 New Game</button>
            <button onclick="window.tractorResetGame()" class="btn-reset-game">🔃 Reset</button>
            <div class="difficulty-selector">
                <span>Level:</span>
                <select id="difficulty" onchange="window.tractorChangeDifficulty()">
                    <option value="easy">Easy (6x6)</option>
                    <option value="medium" selected>Medium (8x8)</option>
                    <option value="hard">Hard (10x10)</option>
                </select>
            </div>
        </div>

        <!-- Game Board -->
        <div class="game-board-wrapper">
            <div class="game-board" id="gameBoard">
                <!-- Grid will be rendered here -->
            </div>
        </div>

        <!-- Tractor Position Indicator -->
        <div class="tractor-info">
            <span>🚜 Position: <strong id="tractorPos">(0, 0)</strong></span>
            <span>🌾 Level: <strong id="currentLevel">1</strong></span>
            <span>⌨️ <strong>WASD</strong> or <strong>Arrows</strong> to move</span>
        </div>

        <!-- Victory Modal -->
        <div class="modal-overlay" id="victoryModal" style="display:none;">
            <div class="modal-content">
                <div class="modal-icon">🌾</div>
                <h2>Level Complete! 🎉</h2>
                <p id="victoryMessage">You plowed all the fields!</p>
                <div class="modal-stats">
                    <div class="modal-stat">
                        <span>Fields Plowed</span>
                        <span id="finalPlowed">0</span>
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
                <button onclick="window.tractorNextLevel()" class="btn-play-again">🚜 Next Level</button>
                <button onclick="window.tractorCloseModal()" class="btn-close-modal">Close</button>
            </div>
        </div>

        <!-- Score History -->
        <div class="score-history">
            <div class="history-header">
                <h3>📊 Farming History</h3>
                <button onclick="window.tractorClearHistory()" class="btn-clear-history">🗑️ Clear History</button>
            </div>
            <div id="scoreHistoryList" class="history-list">
                <p style="color: #666; text-align: center; padding: 20px;">No farming history yet. Start playing!</p>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<!-- <div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tractor-game.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/tractor-game.js') }}"></script>
@endpush -->