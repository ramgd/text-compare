@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>🎯 Arrow Puzzle Maze</title>
    <style>
        /* ============================================
           ARROW PUZZLE MAZE GAME - COMPLETE CSS
           ============================================ */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .game-arrow-wrapper {
            padding: 15px;
            max-width: 600px;
            margin: 0 auto;
            min-height: calc(100vh - 200px);
        }

        .game-arrow-container {
            background: linear-gradient(145deg, #0f0c29, #1a1a2e);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            color: #fff;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.06);
        }

        .game-arrow-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(108, 99, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        /* ==================== HEADER ==================== */
        .game-arrow-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 15px;
            flex-wrap: wrap;
            gap: 10px;
            position: relative;
            z-index: 2;
        }

        .game-arrow-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .game-arrow-title h2 {
            font-size: 20px;
            font-weight: 700;
            background: linear-gradient(135deg, #6C63FF, #FF6B6B);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }

        .game-arrow-level {
            font-size: 14px;
            color: #aaa;
            background: rgba(255,255,255,0.08);
            padding: 4px 14px;
            border-radius: 20px;
        }

        .game-arrow-stats {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .game-arrow-stat {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            color: #ccc;
        }

        .game-arrow-stat i {
            font-size: 14px;
        }

        .game-arrow-stat .fa-star {
            color: #f1c40f;
        }
        .game-arrow-stat .fa-heart {
            color: #ff6b6b;
        }
        .game-arrow-stat .fa-clock {
            color: #4fc3f7;
        }
        .game-arrow-stat .fa-shoe-prints {
            color: #81c784;
        }

        .game-arrow-stat .stat-value {
            font-weight: 700;
            color: #fff;
            min-width: 20px;
        }

        .game-arrow-actions {
            display: flex;
            gap: 5px;
        }

        .game-arrow-btn-icon {
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            color: #fff;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.06);
            font-size: 14px;
        }

        .game-arrow-btn-icon:hover {
            background: rgba(255,255,255,0.12);
            transform: scale(1.05);
        }

        .game-arrow-btn-icon:active {
            transform: scale(0.9);
        }

        /* ==================== CANVAS ==================== */
        .game-arrow-canvas-wrapper {
            position: relative;
            background: rgba(0,0,0,0.3);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.06);
            overflow: hidden;
            aspect-ratio: 1;
            margin-bottom: 15px;
            z-index: 2;
        }

        #arrowCanvas {
            width: 100%;
            height: 100%;
            display: block;
            touch-action: none;
            cursor: pointer;
        }

        /* ==================== OVERLAY ==================== */
        .game-arrow-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(10px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10;
            border-radius: 16px;
            animation: fadeIn 0.4s ease;
        }

        .game-arrow-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .game-arrow-overlay-content {
            text-align: center;
            color: #fff;
            padding: 30px;
            max-width: 350px;
            width: 90%;
        }

        .game-arrow-overlay-content h2 {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .game-arrow-overlay-content .subtitle {
            color: #aaa;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .game-arrow-overlay-stats {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .game-arrow-overlay-stat {
            background: rgba(255,255,255,0.06);
            padding: 10px 16px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.06);
            min-width: 70px;
        }

        .game-arrow-overlay-stat span:first-child {
            display: block;
            font-size: 11px;
            color: #888;
        }

        .game-arrow-overlay-stat span:last-child {
            display: block;
            font-size: 22px;
            font-weight: 700;
            color: #ffd700;
        }

        .game-arrow-overlay-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin: 4px;
        }

        .game-arrow-overlay-btn.primary {
            background: linear-gradient(135deg, #6C63FF, #5a52d5);
            color: #fff;
        }

        .game-arrow-overlay-btn.primary:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(108, 99, 255, 0.4);
        }

        .game-arrow-overlay-btn.secondary {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .game-arrow-overlay-btn.secondary:hover {
            background: rgba(255,255,255,0.15);
        }

        /* ==================== MOBILE CONTROLS ==================== */
        .game-arrow-mobile-controls {
            display: none;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            z-index: 2;
            position: relative;
        }

        .game-arrow-mobile-controls .ctrl-btn {
            width: 55px;
            height: 55px;
            border: none;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            font-size: 22px;
            cursor: pointer;
            transition: all 0.15s;
            touch-action: none;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .game-arrow-mobile-controls .ctrl-btn:active {
            transform: scale(0.85);
            background: rgba(255,255,255,0.2);
        }

        .game-arrow-mobile-controls .ctrl-row {
            display: flex;
            gap: 8px;
        }

        /* ==================== LEVEL SELECTOR ==================== */
        .game-arrow-level-selector {
            margin-top: 12px;
            padding: 12px;
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
            max-height: 160px;
            overflow-y: auto;
            z-index: 2;
            position: relative;
        }

        .game-arrow-level-selector::-webkit-scrollbar {
            width: 4px;
        }
        .game-arrow-level-selector::-webkit-scrollbar-thumb {
            background: #6C63FF;
            border-radius: 2px;
        }
        .game-arrow-level-selector::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: 2px;
        }

        .game-arrow-level-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(44px, 1fr));
            gap: 6px;
        }

        .game-arrow-level-btn {
            padding: 6px;
            border: none;
            border-radius: 8px;
            background: rgba(255,255,255,0.05);
            color: #aaa;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.05);
            text-align: center;
        }

        .game-arrow-level-btn:hover {
            background: rgba(255,255,255,0.1);
            transform: scale(1.05);
        }

        .game-arrow-level-btn.completed {
            background: rgba(46, 204, 113, 0.3);
            border-color: #2ecc71;
            color: #2ecc71;
        }

        .game-arrow-level-btn.current {
            border-color: #6C63FF;
            color: #6C63FF;
            box-shadow: 0 0 20px rgba(108, 99, 255, 0.15);
            background: rgba(108, 99, 255, 0.1);
        }

        .game-arrow-level-btn.locked {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* ==================== TOAST ==================== */
        .game-arrow-toast {
            visibility: hidden;
            min-width: 200px;
            background: rgba(0,0,0,0.9);
            color: #fff;
            text-align: center;
            border-radius: 10px;
            padding: 10px 20px;
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            z-index: 9999;
            opacity: 0;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(10px);
            font-weight: 500;
            font-size: 14px;
            pointer-events: none;
        }

        .game-arrow-toast.show {
            visibility: visible;
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .game-arrow-toast.success { border-color: #2ecc71; }
        .game-arrow-toast.error { border-color: #ff6b6b; }
        .game-arrow-toast.info { border-color: #6C63FF; }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 768px) {
            .game-arrow-wrapper {
                padding: 10px;
            }

            .game-arrow-container {
                padding: 12px;
            }

            .game-arrow-header {
                flex-direction: column;
                align-items: stretch;
                gap: 8px;
                padding: 10px 12px;
            }

            .game-arrow-title h2 {
                font-size: 17px;
            }

            .game-arrow-stats {
                justify-content: space-around;
            }

            .game-arrow-stat {
                font-size: 12px;
            }

            .game-arrow-mobile-controls {
                display: flex !important;
            }

            .game-arrow-level-grid {
                grid-template-columns: repeat(auto-fill, minmax(38px, 1fr));
                gap: 4px;
            }

            .game-arrow-level-btn {
                font-size: 11px;
                padding: 4px;
            }

            .game-arrow-overlay-content h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 480px) {
            .game-arrow-wrapper {
                padding: 6px;
            }

            .game-arrow-container {
                padding: 8px;
                border-radius: 12px;
            }

            .game-arrow-title h2 {
                font-size: 15px;
            }

            .game-arrow-stat {
                font-size: 10px;
                gap: 3px;
            }

            .game-arrow-stat i {
                font-size: 12px;
            }

            .game-arrow-btn-icon {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            .game-arrow-mobile-controls .ctrl-btn {
                width: 48px;
                height: 48px;
                font-size: 18px;
            }

            .game-arrow-overlay-content {
                padding: 20px;
            }

            .game-arrow-overlay-content h2 {
                font-size: 20px;
            }

            .game-arrow-overlay-stat span:last-child {
                font-size: 18px;
            }

            .game-arrow-overlay-btn {
                padding: 10px 20px;
                font-size: 14px;
            }

            .game-arrow-level-grid {
                grid-template-columns: repeat(auto-fill, minmax(32px, 1fr));
                gap: 3px;
            }

            .game-arrow-level-btn {
                font-size: 10px;
                padding: 3px;
            }
        }

        @media (pointer: coarse) {
            .game-arrow-mobile-controls {
                display: flex !important;
            }
        }

        @media (min-width: 769px) {
            .game-arrow-mobile-controls {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="game-arrow-wrapper">
    <div class="game-arrow-container">
        <!-- Header -->
        <div class="game-arrow-header">
            <div class="game-arrow-title">
                <h2>🎯 Arrow Maze</h2>
                <span class="game-arrow-level" id="levelDisplay">Level 1</span>
            </div>
            <div class="game-arrow-stats">
                <div class="game-arrow-stat">
                    <i class="fas fa-star"></i>
                    <span class="stat-value" id="starDisplay">0</span>
                </div>
                <div class="game-arrow-stat">
                    <i class="fas fa-shoe-prints"></i>
                    <span class="stat-value" id="moveDisplay">0</span>
                </div>
                <div class="game-arrow-stat">
                    <i class="fas fa-clock"></i>
                    <span class="stat-value" id="timerDisplay">00:00</span>
                </div>
                <div class="game-arrow-stat">
                    <i class="fas fa-heart"></i>
                    <span class="stat-value" id="livesDisplay">3</span>
                </div>
            </div>
            <div class="game-arrow-actions">
                <button class="game-arrow-btn-icon" onclick="togglePause()" title="Pause">
                    <i class="fas fa-pause"></i>
                </button>
                <button class="game-arrow-btn-icon" onclick="restartLevel()" title="Restart">
                    <i class="fas fa-redo"></i>
                </button>
                <button class="game-arrow-btn-icon" onclick="showHint()" title="Hint">
                    <i class="fas fa-lightbulb"></i>
                </button>
            </div>
        </div>

        <!-- Canvas -->
        <div class="game-arrow-canvas-wrapper">
            <canvas id="arrowCanvas"></canvas>

            <!-- Overlay -->
            <div class="game-arrow-overlay" id="gameOverlay">
                <div class="game-arrow-overlay-content">
                    <h2 id="overlayTitle">🎉 Level Complete!</h2>
                    <p class="subtitle" id="overlaySubtitle">Amazing! You solved the puzzle!</p>
                    <div class="game-arrow-overlay-stats">
                        <div class="game-arrow-overlay-stat">
                            <span>Stars</span>
                            <span id="overlayStars">0</span>
                        </div>
                        <div class="game-arrow-overlay-stat">
                            <span>Moves</span>
                            <span id="overlayMoves">0</span>
                        </div>
                        <div class="game-arrow-overlay-stat">
                            <span>Time</span>
                            <span id="overlayTime">00:00</span>
                        </div>
                    </div>
                    <div>
                        <button class="game-arrow-overlay-btn primary" onclick="nextLevel()">Next Level ➜</button>
                        <button class="game-arrow-overlay-btn secondary" onclick="closeOverlay()">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Controls -->
        <div class="game-arrow-mobile-controls">
            <button class="ctrl-btn" id="arrowUp" onmousedown="movePlayer('up')" ontouchstart="movePlayer('up')">
                <i class="fas fa-arrow-up"></i>
            </button>
            <div class="ctrl-row">
                <button class="ctrl-btn" id="arrowLeft" onmousedown="movePlayer('left')" ontouchstart="movePlayer('left')">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button class="ctrl-btn" id="arrowDown" onmousedown="movePlayer('down')" ontouchstart="movePlayer('down')">
                    <i class="fas fa-arrow-down"></i>
                </button>
                <button class="ctrl-btn" id="arrowRight" onmousedown="movePlayer('right')" ontouchstart="movePlayer('right')">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- Level Selector -->
        <div class="game-arrow-level-selector" id="levelSelector">
            <div class="game-arrow-level-grid" id="levelGrid"></div>
        </div>
    </div>
</div>

<!-- Toast -->
<div class="game-arrow-toast" id="toast"></div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script>
// ============================================================
// 🎯 ARROW PUZZLE MAZE GAME - COMPLETE ENGINE
// ============================================================

(function() {
    'use strict';

    // ==================== STATE ====================
    const state = {
        currentLevel: 1,
        maxLevel: 50,
        lives: 3,
        moves: 0,
        stars: 0,
        time: 0,
        isPaused: false,
        isComplete: false,
        isAnimating: false,
        timerInterval: null,
        grid: [],
        gridSize: 8,
        playerPos: { row: 0, col: 0 },
        startPos: { row: 0, col: 0 },
        finishPos: { row: 0, col: 0 },
        completedLevels: [],
        hintPath: [],
        hintIndex: 0,
        showingHint: false
    };

    // ==================== DOM REFS ====================
    const canvas = document.getElementById('arrowCanvas');
    const ctx = canvas.getContext('2d');
    const overlay = document.getElementById('gameOverlay');
    const toast = document.getElementById('toast');

    // ==================== LEVEL DATA ====================
    function generateLevel(levelNumber) {
        const size = Math.min(4 + Math.floor(levelNumber / 10), 12);
        const grid = [];
        for (let r = 0; r < size; r++) {
            grid[r] = [];
            for (let c = 0; c < size; c++) {
                grid[r][c] = { type: 'empty', direction: null };
            }
        }

        // Start (top-left) and Finish (bottom-right)
        const start = { row: 0, col: 0 };
        const finish = { row: size - 1, col: size - 1 };
        grid[start.row][start.col] = { type: 'start', direction: null };
        grid[finish.row][finish.col] = { type: 'finish', direction: null };

        // Generate path
        generatePath(grid, start, finish, size, levelNumber);

        // Add walls
        const wallCount = Math.floor(levelNumber / 8);
        for (let i = 0; i < wallCount; i++) {
            const r = Math.floor(Math.random() * size);
            const c = Math.floor(Math.random() * size);
            if (grid[r][c].type === 'empty') {
                grid[r][c] = { type: 'wall', direction: null };
            }
        }

        return { grid, size, start, finish };
    }

    function generatePath(grid, start, finish, size, levelNumber) {
        const dirs = ['up', 'down', 'left', 'right'];
        let current = { ...start };
        const maxSteps = size * (2 + Math.floor(levelNumber / 15));
        let steps = 0;
        const visited = new Set();

        while ((current.row !== finish.row || current.col !== finish.col) && steps < maxSteps) {
            const dir = dirs[Math.floor(Math.random() * dirs.length)];
            let next = { ...current };
            switch(dir) {
                case 'up': next.row--; break;
                case 'down': next.row++; break;
                case 'left': next.col--; break;
                case 'right': next.col++; break;
            }

            const key = `${next.row},${next.col}`;
            if (next.row >= 0 && next.row < size && next.col >= 0 && next.col < size && !visited.has(key)) {
                visited.add(key);
                grid[current.row][current.col] = { type: 'arrow', direction: dir };
                current = { ...next };
                steps++;
            }
        }

        // Ensure finish is reachable
        if (current.row !== finish.row || current.col !== finish.col) {
            const dirs2 = [];
            if (finish.row < current.row) dirs2.push('up');
            else if (finish.row > current.row) dirs2.push('down');
            if (finish.col < current.col) dirs2.push('left');
            else if (finish.col > current.col) dirs2.push('right');
            if (dirs2.length > 0) {
                const lastDir = dirs2[Math.floor(Math.random() * dirs2.length)];
                grid[current.row][current.col] = { type: 'arrow', direction: lastDir };
            }
        }
    }

    // ==================== LOAD LEVEL ====================
    function loadLevel(levelNumber) {
        const data = generateLevel(levelNumber);
        state.grid = data.grid;
        state.gridSize = data.size;
        state.startPos = data.start;
        state.finishPos = data.finish;
        state.playerPos = { ...data.start };
        state.moves = 0;
        state.stars = 0;
        state.isComplete = false;
        state.isAnimating = false;
        state.hintPath = [];
        state.hintIndex = 0;
        state.showingHint = false;

        document.getElementById('levelDisplay').textContent = `Level ${levelNumber}`;
        updateUI();
        resizeCanvas();
        renderGrid();
        renderPlayer();
        renderArrows();

        // Reset timer
        if (state.timerInterval) {
            clearInterval(state.timerInterval);
            state.timerInterval = null;
        }
        state.time = 0;
        updateTimerDisplay();

        // Reset lives if level 1
        if (levelNumber === 1) {
            state.lives = 3;
            updateUI();
        }

        closeOverlay();
        hideToast();

        // Start timer on first move
        let timerStarted = false;

        // Override move to start timer
        const originalMove = movePlayer;
        window.movePlayer = function(direction) {
            if (!timerStarted && !state.isPaused && !state.isComplete) {
                timerStarted = true;
                startTimer();
            }
            originalMove(direction);
        };
    }

    // ==================== TIMER ====================
    function startTimer() {
        if (state.timerInterval) return;
        state.timerInterval = setInterval(() => {
            if (!state.isPaused && !state.isComplete) {
                state.time++;
                updateTimerDisplay();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const mins = Math.floor(state.time / 60);
        const secs = state.time % 60;
        document.getElementById('timerDisplay').textContent =
            String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    }

    // ==================== PLAYER MOVEMENT ====================
    function movePlayer(direction) {
        if (state.isPaused || state.isComplete || state.isAnimating) return;

        const pos = state.playerPos;
        const dir = direction;
        let newRow = pos.row;
        let newCol = pos.col;

        switch(dir) {
            case 'up': newRow--; break;
            case 'down': newRow++; break;
            case 'left': newCol--; break;
            case 'right': newCol++; break;
        }

        // Check bounds
        if (newRow < 0 || newRow >= state.gridSize || newCol < 0 || newCol >= state.gridSize) {
            showToast('🚧 Out of bounds!', 'error');
            return;
        }

        const cell = state.grid[newRow][newCol];

        // Check if wall
        if (cell.type === 'wall') {
            showToast('🧱 Wall! Try another way.', 'error');
            return;
        }

        // Check arrow direction
        if (cell.type === 'arrow' && cell.direction !== dir) {
            showToast('❌ Wrong direction! Follow the arrow.', 'error');
            state.lives--;
            updateUI();
            if (state.lives <= 0) {
                gameOver();
                return;
            }
            // Reset to start
            state.playerPos = { ...state.startPos };
            renderGrid();
            renderPlayer();
            renderArrows();
            return;
        }

        // Move player
        state.playerPos = { row: newRow, col: newCol };
        state.moves++;
        updateUI();

        // Render
        renderGrid();
        renderPlayer();
        renderArrows();

        // Check finish
        if (newRow === state.finishPos.row && newCol === state.finishPos.col) {
            levelComplete();
            return;
        }

        // Auto-move if arrow
        if (cell.type === 'arrow' && cell.direction) {
            setTimeout(() => {
                state.isAnimating = false;
                movePlayer(cell.direction);
            }, 200);
        }
    }

    // ==================== LEVEL COMPLETE ====================
    function levelComplete() {
        state.isComplete = true;
        if (state.timerInterval) {
            clearInterval(state.timerInterval);
            state.timerInterval = null;
        }

        // Calculate stars
        const perfectMoves = state.gridSize * 2;
        const starCount = state.moves <= perfectMoves ? 3 : state.moves <= perfectMoves * 1.5 ? 2 : 1;
        state.stars = starCount;

        // Save progress
        if (!state.completedLevels.includes(state.currentLevel)) {
            state.completedLevels.push(state.currentLevel);
            localStorage.setItem('arrowMazeCompleted', JSON.stringify(state.completedLevels));
        }

        // Check if all levels completed
        const totalLevels = 50;
        if (state.completedLevels.length >= totalLevels) {
            document.getElementById('overlayTitle').textContent = '🏆 All Levels Complete!';
            document.getElementById('overlaySubtitle').textContent = 'You are the Arrow Maze Master! 🌟';
        } else {
            document.getElementById('overlayTitle').textContent = '🎉 Level Complete!';
            document.getElementById('overlaySubtitle').textContent = `You solved Level ${state.currentLevel}!`;
        }

        document.getElementById('overlayStars').textContent = '⭐'.repeat(starCount) + '☆'.repeat(3 - starCount);
        document.getElementById('overlayMoves').textContent = state.moves;
        document.getElementById('overlayTime').textContent = document.getElementById('timerDisplay').textContent;

        overlay.classList.add('active');
        renderLevelSelector();
        showToast('🎉 Level Complete!', 'success');
    }

    // ==================== GAME OVER ====================
    function gameOver() {
        state.isComplete = true;
        if (state.timerInterval) {
            clearInterval(state.timerInterval);
            state.timerInterval = null;
        }

        document.getElementById('overlayTitle').textContent = '💥 Game Over';
        document.getElementById('overlaySubtitle').textContent = 'You ran out of lives!';
        document.getElementById('overlayStars').textContent = '😢';
        document.getElementById('overlayMoves').textContent = state.moves;
        document.getElementById('overlayTime').textContent = document.getElementById('timerDisplay').textContent;

        overlay.classList.add('active');
        showToast('💥 Game Over!', 'error');
    }

    // ==================== RENDER ====================
    function resizeCanvas() {
        const rect = canvas.parentElement.getBoundingClientRect();
        const size = Math.min(rect.width, rect.height);
        canvas.width = size;
        canvas.height = size;
        renderGrid();
        renderPlayer();
        renderArrows();
    }

    function renderGrid() {
        const w = canvas.width / state.gridSize;
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Background
        ctx.fillStyle = 'rgba(255,255,255,0.03)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        for (let r = 0; r < state.gridSize; r++) {
            for (let c = 0; c < state.gridSize; c++) {
                const cell = state.grid[r][c];
                const x = c * w;
                const y = r * w;

                // Cell background
                if (cell.type === 'wall') {
                    ctx.fillStyle = 'rgba(255,255,255,0.08)';
                    ctx.fillRect(x, y, w, w);
                    ctx.strokeStyle = 'rgba(255,255,255,0.05)';
                    ctx.lineWidth = 1;
                    ctx.strokeRect(x, y, w, w);
                    // Wall pattern
                    ctx.fillStyle = 'rgba(100,100,120,0.3)';
                    ctx.fillRect(x + w * 0.2, y + w * 0.2, w * 0.6, w * 0.6);
                    continue;
                }

                if (cell.type === 'start') {
                    ctx.fillStyle = 'rgba(46, 204, 113, 0.2)';
                    ctx.fillRect(x, y, w, w);
                    ctx.fillStyle = '#2ecc71';
                    ctx.font = `${w * 0.5}px sans-serif`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('🏁', x + w/2, y + w/2);
                    continue;
                }

                if (cell.type === 'finish') {
                    ctx.fillStyle = 'rgba(241, 196, 15, 0.2)';
                    ctx.fillRect(x, y, w, w);
                    ctx.fillStyle = '#f1c40f';
                    ctx.font = `${w * 0.5}px sans-serif`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('⭐', x + w/2, y + w/2);
                    continue;
                }

                if (cell.type === 'empty') {
                    ctx.fillStyle = 'rgba(255,255,255,0.02)';
                    ctx.fillRect(x, y, w, w);
                    ctx.strokeStyle = 'rgba(255,255,255,0.03)';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(x, y, w, w);
                }

                if (cell.type === 'arrow') {
                    ctx.fillStyle = 'rgba(108, 99, 255, 0.08)';
                    ctx.fillRect(x, y, w, w);
                    ctx.strokeStyle = 'rgba(108, 99, 255, 0.15)';
                    ctx.lineWidth = 0.5;
                    ctx.strokeRect(x, y, w, w);
                }
            }
        }
    }

    function renderArrows() {
        const w = canvas.width / state.gridSize;

        for (let r = 0; r < state.gridSize; r++) {
            for (let c = 0; c < state.gridSize; c++) {
                const cell = state.grid[r][c];
                if (cell.type === 'arrow' && cell.direction) {
                    const x = c * w + w/2;
                    const y = r * w + w/2;
                    const size = w * 0.3;

                    ctx.save();
                    ctx.translate(x, y);

                    // Glow
                    const grad = ctx.createRadialGradient(0, 0, 0, 0, 0, w * 0.5);
                    grad.addColorStop(0, 'rgba(108, 99, 255, 0.1)');
                    grad.addColorStop(1, 'rgba(108, 99, 255, 0)');
                    ctx.fillStyle = grad;
                    ctx.arc(0, 0, w * 0.5, 0, Math.PI * 2);
                    ctx.fill();

                    // Arrow
                    ctx.fillStyle = '#6C63FF';
                    ctx.shadowColor = '#6C63FF';
                    ctx.shadowBlur = 15;
                    ctx.font = `${size}px sans-serif`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    const dirMap = {
                        'up': '⬆',
                        'down': '⬇',
                        'left': '⬅',
                        'right': '➡'
                    };
                    ctx.fillText(dirMap[cell.direction] || '➡', 0, 2);

                    ctx.shadowBlur = 0;
                    ctx.restore();
                }
            }
        }
    }

    function renderPlayer() {
        const w = canvas.width / state.gridSize;
        const pos = state.playerPos;
        const x = pos.col * w + w/2;
        const y = pos.row * w + w/2;
        const radius = w * 0.3;

        // Glow
        const grad = ctx.createRadialGradient(x, y, 0, x, y, radius * 2);
        grad.addColorStop(0, 'rgba(255, 107, 107, 0.3)');
        grad.addColorStop(1, 'rgba(255, 107, 107, 0)');
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(x, y, radius * 2, 0, Math.PI * 2);
        ctx.fill();

        // Player circle
        ctx.shadowColor = '#FF6B6B';
        ctx.shadowBlur = 20;
        ctx.fillStyle = '#FF6B6B';
        ctx.beginPath();
        ctx.arc(x, y, radius, 0, Math.PI * 2);
        ctx.fill();

        ctx.shadowBlur = 0;
        ctx.fillStyle = '#fff';
        ctx.font = `${radius * 1.2}px sans-serif`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('🚗', x, y + 2);
    }

    // ==================== UI ====================
    function updateUI() {
        document.getElementById('starDisplay').textContent = state.stars;
        document.getElementById('moveDisplay').textContent = state.moves;
        document.getElementById('livesDisplay').textContent = state.lives;
        document.getElementById('levelDisplay').textContent = `Level ${state.currentLevel}`;
    }

    // ==================== LEVEL SELECTOR ====================
    function renderLevelSelector() {
        const grid = document.getElementById('levelGrid');
        grid.innerHTML = '';
        const total = state.maxLevel;

        for (let i = 1; i <= total; i++) {
            const btn = document.createElement('button');
            btn.className = 'game-arrow-level-btn';
            btn.textContent = i;

            if (state.completedLevels.includes(i)) {
                btn.classList.add('completed');
            }
            if (i === state.currentLevel) {
                btn.classList.add('current');
            }
            if (i > state.currentLevel + 1 && !state.completedLevels.includes(i - 1)) {
                btn.classList.add('locked');
            }

            btn.onclick = () => {
                if (i === state.currentLevel || state.completedLevels.includes(i) || i === state.currentLevel + 1) {
                    state.currentLevel = i;
                    loadLevel(i);
                    renderLevelSelector();
                } else {
                    showToast('🔒 Complete previous level first!', 'error');
                }
            };

            grid.appendChild(btn);
        }
    }

    // ==================== ACTIONS ====================
    function nextLevel() {
        if (state.currentLevel < state.maxLevel) {
            state.currentLevel++;
            loadLevel(state.currentLevel);
            renderLevelSelector();
        } else {
            showToast('🎉 You completed all levels!', 'success');
            overlay.classList.remove('active');
        }
    }

    function restartLevel() {
        loadLevel(state.currentLevel);
        renderLevelSelector();
        showToast('🔄 Level restarted', 'info');
    }

    function togglePause() {
        state.isPaused = !state.isPaused;
        document.querySelector('.game-arrow-btn-icon i').className =
            state.isPaused ? 'fas fa-play' : 'fas fa-pause';
        showToast(state.isPaused ? '⏸️ Paused' : '▶️ Resumed', 'info');
    }

    function closeOverlay() {
        overlay.classList.remove('active');
    }

    function showHint() {
        if (state.isComplete) return;
        if (state.hintPath.length === 0) {
            // Find path from current position to finish
            state.hintPath = findPath(state.playerPos, state.finishPos);
            state.hintIndex = 0;
        }

        if (state.hintIndex < state.hintPath.length) {
            const next = state.hintPath[state.hintIndex];
            const dir = getDirection(state.playerPos, next);
            state.hintIndex++;
            showToast(`💡 Hint: Move ${dir}`, 'info');
            // Highlight the next cell
            highlightCell(next.row, next.col);
        } else {
            showToast('💡 No more hints available', 'info');
            state.hintPath = [];
        }
    }

    function findPath(start, finish) {
        const queue = [{ row: start.row, col: start.col, path: [] }];
        const visited = new Set();
        visited.add(`${start.row},${start.col}`);

        while (queue.length > 0) {
            const current = queue.shift();
            if (current.row === finish.row && current.col === finish.col) {
                return current.path;
            }

            const dirs = [
                { row: -1, col: 0 }, { row: 1, col: 0 },
                { row: 0, col: -1 }, { row: 0, col: 1 }
            ];

            for (const d of dirs) {
                const nr = current.row + d.row;
                const nc = current.col + d.col;
                const key = `${nr},${nc}`;

                if (nr >= 0 && nr < state.gridSize && nc >= 0 && nc < state.gridSize &&
                    !visited.has(key) && state.grid[nr][nc].type !== 'wall') {
                    visited.add(key);
                    const newPath = [...current.path, { row: nr, col: nc }];
                    queue.push({ row: nr, col: nc, path: newPath });
                }
            }
        }
        return [];
    }

    function getDirection(from, to) {
        if (to.row < from.row) return 'up';
        if (to.row > from.row) return 'down';
        if (to.col < from.col) return 'left';
        if (to.col > from.col) return 'right';
        return '';
    }

    function highlightCell(row, col) {
        const w = canvas.width / state.gridSize;
        const x = col * w;
        const y = row * w;

        ctx.fillStyle = 'rgba(108, 99, 255, 0.3)';
        ctx.shadowColor = '#6C63FF';
        ctx.shadowBlur = 30;
        ctx.fillRect(x, y, w, w);
        ctx.shadowBlur = 0;

        setTimeout(() => {
            renderGrid();
            renderArrows();
            renderPlayer();
        }, 500);
    }

    // ==================== TOAST ====================
    function showToast(message, type) {
        toast.textContent = message;
        toast.className = 'game-arrow-toast show ' + (type || 'info');
        clearTimeout(toast._timeout);
        toast._timeout = setTimeout(() => {
            toast.className = 'game-arrow-toast';
        }, 2500);
    }

    function hideToast() {
        toast.className = 'game-arrow-toast';
    }

    // ==================== KEYBOARD CONTROLS ====================
    document.addEventListener('keydown', (e) => {
        const keyMap = {
            'ArrowUp': 'up', 'ArrowDown': 'down',
            'ArrowLeft': 'left', 'ArrowRight': 'right',
            'w': 'up', 'W': 'up',
            's': 'down', 'S': 'down',
            'a': 'left', 'A': 'left',
            'd': 'right', 'D': 'right'
        };
        if (keyMap[e.key]) {
            e.preventDefault();
            movePlayer(keyMap[e.key]);
        }
        if (e.key === 'r' || e.key === 'R') restartLevel();
        if (e.key === 'p' || e.key === 'P') togglePause();
    });

    // ==================== CANVAS CLICK ====================
    canvas.addEventListener('click', (e) => {
        const rect = canvas.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width * canvas.width;
        const y = (e.clientY - rect.top) / rect.height * canvas.height;
        const w = canvas.width / state.gridSize;
        const col = Math.floor(x / w);
        const row = Math.floor(y / w);

        if (row >= 0 && row < state.gridSize && col >= 0 && col < state.gridSize) {
            const cell = state.grid[row][col];
            if (cell.type === 'arrow' && cell.direction) {
                const dirMap = { 'up': 'up', 'down': 'down', 'left': 'left', 'right': 'right' };
                const dir = dirMap[cell.direction];
                if (dir) movePlayer(dir);
            }
        }
    });

    // ==================== CANVAS TOUCH SWIPE ====================
    let touchStartX = 0;
    let touchStartY = 0;

    canvas.addEventListener('touchstart', (e) => {
        const touch = e.touches[0];
        touchStartX = touch.clientX;
        touchStartY = touch.clientY;
    }, { passive: true });

    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        if (!touchStartX || !touchStartY) return;
        const touch = e.touches[0];
        const dx = touch.clientX - touchStartX;
        const dy = touch.clientY - touchStartY;
        const absDx = Math.abs(dx);
        const absDy = Math.abs(dy);

        if (Math.max(absDx, absDy) > 30) {
            if (absDx > absDy) {
                movePlayer(dx > 0 ? 'right' : 'left');
            } else {
                movePlayer(dy > 0 ? 'down' : 'up');
            }
            touchStartX = touch.clientX;
            touchStartY = touch.clientY;
        }
    }, { passive: false });

    canvas.addEventListener('touchend', () => {
        touchStartX = 0;
        touchStartY = 0;
    }, { passive: true });

    // ==================== INIT ====================
    function init() {
        // Load completed levels from localStorage
        try {
            const saved = localStorage.getItem('arrowMazeCompleted');
            if (saved) {
                state.completedLevels = JSON.parse(saved);
            }
        } catch (e) {
            state.completedLevels = [];
        }

        // Find first uncompleted level
        let startLevel = 1;
        for (let i = 1; i <= state.maxLevel; i++) {
            if (!state.completedLevels.includes(i)) {
                startLevel = i;
                break;
            }
        }
        state.currentLevel = startLevel;

        loadLevel(state.currentLevel);
        renderLevelSelector();

        // Resize on window change
        window.addEventListener('resize', resizeCanvas);

        console.log('🎯 Arrow Maze Game loaded!');
        console.log(`📊 Level ${state.currentLevel} of ${state.maxLevel}`);
        console.log(`✅ Completed: ${state.completedLevels.length} levels`);
    }

    // ==================== EXPOSE ====================
    window.movePlayer = movePlayer;
    window.togglePause = togglePause;
    window.restartLevel = restartLevel;
    window.nextLevel = nextLevel;
    window.closeOverlay = closeOverlay;
    window.showHint = showHint;
    window.renderLevelSelector = renderLevelSelector;

    // Start
    init();

})();
</script>
</body>
</html>
@endsection