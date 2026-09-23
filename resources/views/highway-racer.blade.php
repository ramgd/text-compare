@extends('layouts.app')

@section('content')
<div id="racer-wrapper">
    <div id="gameContainer">
        <canvas id="gameCanvas"></canvas>
        
        <!-- HUD -->
        <div id="hud">
            <div class="hud-row">
                <div class="hud-item">
                    <span class="hud-label">🏁 Level</span>
                    <span class="hud-value" id="levelDisplay">Easy</span>
                </div>
                <div class="hud-item">
                    <span class="hud-label">❤️ Lives</span>
                    <span class="hud-value" id="livesDisplay">❤️❤️❤️</span>
                </div>
                <div class="hud-item">
                    <span class="hud-label">⭐ Score</span>
                    <span class="hud-value" id="scoreDisplay">0</span>
                </div>
                <div class="hud-item">
                    <span class="hud-label">📏 Distance</span>
                    <span class="hud-value" id="distanceDisplay">0m</span>
                </div>
                <div class="hud-item">
                    <span class="hud-label">🚀 Speed</span>
                    <span class="hud-value" id="speedDisplay">0 km/h</span>
                </div>
                <div class="hud-item">
                    <span class="hud-label">🔥 Combo</span>
                    <span class="hud-value" id="comboDisplay">x1</span>
                </div>
            </div>
        </div>

        <!-- Mobile Controls -->
        <div id="mobileControls">
            <button class="ctrl-btn" id="btnLeft">◀</button>
            <button class="ctrl-btn" id="btnRight">▶</button>
        </div>

        <!-- Level Selection -->
        <div id="levelSelect" class="overlay-screen">
            <div class="screen-content">
                <h1>🏎️ Highway Racer</h1>
                <p class="subtitle">Choose your difficulty</p>
                <div class="level-buttons">
                    <button class="level-btn easy" onclick="window.startGame('easy')">
                        🌞 Easy
                        <small>Countryside Highway</small>
                    </button>
                    <button class="level-btn medium" onclick="window.startGame('medium')">
                        🌆 Medium
                        <small>City Expressway</small>
                    </button>
                    <button class="level-btn hard" onclick="window.startGame('hard')">
                        🌙 Hard
                        <small>Night Highway</small>
                    </button>
                </div>
                <div class="high-scores">
                    <h3>🏆 High Scores</h3>
                    <div id="highScoreDisplay"></div>
                </div>
            </div>
        </div>

        <!-- Countdown -->
        <div id="countdown" class="overlay-screen hidden">
            <div class="screen-content">
                <h2 id="countdownNumber">3</h2>
            </div>
        </div>

        <!-- Game Over -->
        <div id="gameOver" class="overlay-screen hidden">
            <div class="screen-content">
                <h2>💥 Game Over</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span>Score</span>
                        <span id="finalScore">0</span>
                    </div>
                    <div class="stat-item">
                        <span>Distance</span>
                        <span id="finalDistance">0m</span>
                    </div>
                    <div class="stat-item">
                        <span>Top Speed</span>
                        <span id="finalSpeed">0 km/h</span>
                    </div>
                    <div class="stat-item">
                        <span>Grade</span>
                        <span id="finalGrade">F</span>
                    </div>
                </div>
                <button class="btn-play" onclick="window.showLevelSelect()">🔄 Play Again</button>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   HIGHWAY RACER - COMPLETE STYLES
   ============================================ */

#racer-wrapper {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
    min-height: calc(100vh - 200px);
    display: flex;
    justify-content: center;
    align-items: center;
}

#gameContainer {
    position: relative;
    width: 100%;
    max-width: 600px;
    aspect-ratio: 9/16;
    background: #1a1a2e;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    border: 3px solid #2d2d44;
}

#gameCanvas {
    width: 100%;
    height: 100%;
    display: block;
    background: #2d5a27;
}

/* HUD */
#hud {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    padding: 8px 12px;
    background: linear-gradient(180deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
    pointer-events: none;
    z-index: 10;
}

.hud-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 2px;
}

.hud-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #fff;
    font-size: 10px;
    text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    min-width: 40px;
}

.hud-label {
    font-size: 8px;
    opacity: 0.6;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.hud-value {
    font-size: 14px;
    font-weight: 700;
    color: #ffd700;
}

/* Mobile Controls */
#mobileControls {
    position: absolute;
    bottom: 30px;
    left: 0;
    right: 0;
    display: none;
    justify-content: space-between;
    padding: 0 20px;
    z-index: 20;
    pointer-events: none;
}

#mobileControls .ctrl-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.3);
    background: rgba(255,255,255,0.1);
    color: #fff;
    font-size: 24px;
    cursor: pointer;
    backdrop-filter: blur(5px);
    transition: all 0.15s;
    user-select: none;
    touch-action: manipulation;
    pointer-events: auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

#mobileControls .ctrl-btn:active {
    transform: scale(0.9);
    background: rgba(255,255,255,0.25);
}

/* Overlay Screens */
.overlay-screen {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 30;
    background: rgba(0,0,0,0.85);
    backdrop-filter: blur(10px);
}

.overlay-screen.hidden {
    display: none;
}

.screen-content {
    text-align: center;
    color: #fff;
    padding: 30px;
    animation: fadeIn 0.5s ease;
    max-width: 90%;
}

.screen-content h1 {
    font-size: 42px;
    margin: 0 0 10px;
    background: linear-gradient(135deg, #ffd700, #f7971e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.screen-content .subtitle {
    font-size: 16px;
    color: #aaa;
    margin-bottom: 25px;
}

.level-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin: 20px 0;
}

.level-btn {
    padding: 15px 30px;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.level-btn:hover {
    transform: scale(1.03);
}

.level-btn small {
    font-size: 12px;
    font-weight: 400;
    opacity: 0.7;
}

.level-btn.easy {
    background: linear-gradient(135deg, #4CAF50, #8BC34A);
    color: #fff;
}
.level-btn.medium {
    background: linear-gradient(135deg, #f7971e, #ffd200);
    color: #1a1a2e;
}
.level-btn.hard {
    background: linear-gradient(135deg, #dc3545, #ee5a24);
    color: #fff;
}

.btn-play {
    padding: 12px 40px;
    border: none;
    border-radius: 12px;
    font-size: 18px;
    font-weight: 700;
    background: linear-gradient(135deg, #ffd700, #f7971e);
    color: #1a1a2e;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 15px;
}

.btn-play:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 25px rgba(255, 210, 0, 0.3);
}

/* High Scores */
.high-scores {
    margin-top: 20px;
    padding: 15px;
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
}

.high-scores h3 {
    margin: 0 0 10px;
    color: #ffd700;
    font-size: 16px;
}

#highScoreDisplay {
    display: flex;
    justify-content: center;
    gap: 20px;
    font-size: 14px;
    color: #aaa;
}

#highScoreDisplay span {
    display: flex;
    flex-direction: column;
    align-items: center;
}

#highScoreDisplay .score-value {
    color: #ffd700;
    font-weight: 700;
    font-size: 18px;
}

/* Countdown */
#countdownNumber {
    font-size: 80px;
    color: #ffd700;
    animation: countdownPulse 0.8s ease;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 20px 0;
}

.stat-item {
    background: rgba(255,255,255,0.05);
    padding: 12px;
    border-radius: 8px;
}

.stat-item span:first-child {
    display: block;
    font-size: 12px;
    color: #888;
}

.stat-item span:last-child {
    display: block;
    font-size: 20px;
    font-weight: 700;
    color: #ffd700;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

@keyframes countdownPulse {
    0% { transform: scale(2); opacity: 0; }
    50% { transform: scale(1); opacity: 1; }
    100% { transform: scale(1); opacity: 1; }
}

/* Responsive */
@media (max-width: 768px) {
    #mobileControls {
        display: flex;
    }

    .hud-item {
        font-size: 8px;
        min-width: 30px;
    }

    .hud-value {
        font-size: 12px;
    }

    .screen-content h1 {
        font-size: 32px;
    }

    .level-btn {
        padding: 12px 20px;
        font-size: 16px;
    }

    #countdownNumber {
        font-size: 60px;
    }

    #highScoreDisplay {
        flex-direction: column;
        gap: 8px;
    }

    .ctrl-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    #racer-wrapper {
        padding: 10px;
    }

    .hud-row {
        gap: 0;
    }

    .hud-item {
        font-size: 7px;
        min-width: 25px;
    }

    .hud-value {
        font-size: 10px;
    }

    .screen-content h1 {
        font-size: 24px;
    }

    .stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }

    .stat-item span:last-child {
        font-size: 16px;
    }

    .ctrl-btn {
        width: 44px;
        height: 44px;
        font-size: 18px;
    }

    #mobileControls {
        padding: 0 10px;
        bottom: 20px;
    }
}

@media (pointer: coarse) {
    .ctrl-btn {
        width: 65px;
        height: 65px;
        font-size: 28px;
    }
}
</style>

<script>
// ============================================
// HIGHWAY RACER - COMPLETE FIXED JS
// ============================================

(function() {
    'use strict';

    // ==================== DOM REFS ====================
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');
    const container = document.getElementById('gameContainer');

    // HUD
    const levelDisplay = document.getElementById('levelDisplay');
    const livesDisplay = document.getElementById('livesDisplay');
    const scoreDisplay = document.getElementById('scoreDisplay');
    const distanceDisplay = document.getElementById('distanceDisplay');
    const speedDisplay = document.getElementById('speedDisplay');
    const comboDisplay = document.getElementById('comboDisplay');

    // Screens
    const levelSelect = document.getElementById('levelSelect');
    const countdown = document.getElementById('countdown');
    const countdownNumber = document.getElementById('countdownNumber');
    const gameOver = document.getElementById('gameOver');
    const finalScore = document.getElementById('finalScore');
    const finalDistance = document.getElementById('finalDistance');
    const finalSpeed = document.getElementById('finalSpeed');
    const finalGrade = document.getElementById('finalGrade');
    const highScoreDisplay = document.getElementById('highScoreDisplay');

    // Mobile Controls
    const btnLeft = document.getElementById('btnLeft');
    const btnRight = document.getElementById('btnRight');

    // ==================== CANVAS SETUP ====================
    function resizeCanvas() {
        const rect = container.getBoundingClientRect();
        canvas.width = rect.width || 400;
        canvas.height = rect.height || 711;
        // Update car dimensions after resize
        if (window.game && window.game.car) {
            const carWidth = Math.min(36, canvas.width * 0.08);
            window.game.car.width = carWidth;
            window.game.car.height = carWidth * 1.6;
        }
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // ==================== AUDIO ====================
    let audioCtx = null;

    function initAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
    }

    function playTone(frequency, duration, type = 'sawtooth', volume = 0.1) {
        try {
            if (!audioCtx) initAudio();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = type;
            osc.frequency.value = frequency;
            gain.gain.value = volume;
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + duration);
        } catch (e) { /* silent fail */ }
    }

    function playCrashSound() {
        playTone(150, 0.3, 'sawtooth', 0.2);
        playTone(80, 0.4, 'square', 0.15);
        setTimeout(() => playTone(60, 0.5, 'sawtooth', 0.1), 100);
    }

    function playWhooshSound() {
        playTone(400, 0.08, 'sine', 0.05);
        setTimeout(() => playTone(600, 0.06, 'sine', 0.04), 50);
    }

    let engineOscillator = null;
    let engineGain = null;

    function startEngine() {
        try {
            if (!audioCtx) initAudio();
            engineOscillator = audioCtx.createOscillator();
            engineGain = audioCtx.createGain();
            engineOscillator.type = 'sawtooth';
            engineOscillator.frequency.value = 60;
            engineGain.gain.value = 0.03;
            engineOscillator.connect(engineGain);
            engineGain.connect(audioCtx.destination);
            engineOscillator.start();
        } catch (e) { /* silent fail */ }
    }

    function updateEngine(speed) {
        if (engineOscillator) {
            try {
                const freq = 60 + speed * 0.08;
                engineOscillator.frequency.setTargetAtTime(Math.min(freq, 300), audioCtx.currentTime, 0.1);
                engineGain.gain.setTargetAtTime(Math.min(0.03 + speed * 0.00005, 0.08), audioCtx.currentTime, 0.1);
            } catch (e) { /* silent fail */ }
        }
    }

    function stopEngine() {
        try {
            if (engineOscillator) {
                engineOscillator.stop();
                engineOscillator = null;
                engineGain = null;
            }
        } catch (e) { /* silent fail */ }
    }

    // ==================== LEVEL CONFIG ====================
    const LEVELS = {
        easy: {
            name: 'Easy',
            display: '🌞 Easy',
            theme: 'countryside',
            startSpeed: 80,
            maxSpeed: 180,
            rampRate: 0.15,
            spawnRate: 1.5,
            maxEnemies: 4,
            lanes: 4,
            colors: {
                road: '#2d5a27',
                line: '#4a7a4a',
                bg: '#87CEEB',
                sidewalk: '#8BC34A'
            }
        },
        medium: {
            name: 'Medium',
            display: '🌆 Medium',
            theme: 'city',
            startSpeed: 120,
            maxSpeed: 280,
            rampRate: 0.2,
            spawnRate: 1.0,
            maxEnemies: 6,
            lanes: 4,
            colors: {
                road: '#3a3a4a',
                line: '#5a5a6a',
                bg: '#e8a87a',
                sidewalk: '#4a4a5a'
            }
        },
        hard: {
            name: 'Hard',
            display: '🌙 Hard',
            theme: 'night',
            startSpeed: 150,
            maxSpeed: 400,
            rampRate: 0.3,
            spawnRate: 0.7,
            maxEnemies: 8,
            lanes: 4,
            colors: {
                road: '#1a1a2e',
                line: '#3a3a5e',
                bg: '#0a0a1e',
                sidewalk: '#2a2a3e'
            }
        }
    };

    // ==================== VEHICLE TYPES ====================
    const VEHICLE_TYPES = {
        compact: {
            label: 'Compact',
            width: 28,
            height: 48,
            speedModifier: 1.0,
            color: null,
            weight: 1
        },
        suv: {
            label: 'SUV',
            width: 34,
            height: 56,
            speedModifier: 0.85,
            color: null,
            weight: 1
        },
        truck: {
            label: 'Truck',
            width: 40,
            height: 72,
            speedModifier: 0.6,
            color: null,
            weight: 1
        },
        bus: {
            label: 'Bus',
            width: 48,
            height: 84,
            speedModifier: 0.45,
            color: null,
            weight: 1
        },
        motorbike: {
            label: 'Motorbike',
            width: 16,
            height: 32,
            speedModifier: 1.6,
            color: null,
            weight: 1
        }
    };

    // ==================== GAME STATE ====================
    const game = {
        state: 'menu',
        level: 'easy',
        // Defaults to the 'easy' config to match `level` above. renderLoop()
        // calls draw() while state === 'menu', i.e. before startGame() assigns
        // this, and draw() dereferences config.colors on every frame.
        config: LEVELS.easy,
        score: 0,
        lives: 3,
        maxLives: 3,
        distance: 0,
        currentSpeed: 80,
        baseSpeed: 80,
        maxSpeed: 180,
        rampRate: 0.15,
        combo: 1,
        comboTimer: 0,
        closeDodges: 0,
        topSpeed: 0,
        roadOffset: 0,
        timeElapsed: 0,
        car: {
            x: 0,
            y: 0,
            width: 36,
            height: 60,
            targetX: 0,
            speed: 0,
            tilt: 0
        },
        enemies: [],
        particles: [],
        debris: [],
        rain: [],
        engineRunning: false,
        frameCount: 0,
        spawnTimer: 0,
        invincibleTimer: 0,
        screenShake: 0,
        highScores: {
            easy: parseInt(localStorage.getItem('racer_high_easy')) || 0,
            medium: parseInt(localStorage.getItem('racer_high_medium')) || 0,
            hard: parseInt(localStorage.getItem('racer_high_hard')) || 0
        },
        roadLeft: 0,
        roadRight: 0,
        roadWidth: 0
    };

    // Make game accessible globally
    window.game = game;

    // ==================== INIT ====================
    function initGame(level) {
        game.level = level;
        game.config = LEVELS[level];
        game.baseSpeed = game.config.startSpeed;
        game.maxSpeed = game.config.maxSpeed;
        game.rampRate = game.config.rampRate;
        game.currentSpeed = game.baseSpeed;
        game.score = 0;
        game.lives = game.maxLives;
        game.distance = 0;
        game.combo = 1;
        game.comboTimer = 0;
        game.closeDodges = 0;
        game.topSpeed = 0;
        game.enemies = [];
        game.particles = [];
        game.debris = [];
        game.rain = [];
        game.frameCount = 0;
        game.spawnTimer = 0;
        game.invincibleTimer = 0;
        game.screenShake = 0;
        game.roadOffset = 0;
        game.timeElapsed = 0;
        game.engineRunning = false;

        // Update car size based on canvas
        const carWidth = Math.min(36, canvas.width * 0.08);
        game.car.width = carWidth;
        game.car.height = carWidth * 1.6;
        game.car.x = canvas.width / 2 - game.car.width / 2;
        game.car.y = canvas.height - game.car.height - 80;
        game.car.targetX = game.car.x;
        game.car.speed = 0;
        game.car.tilt = 0;

        // Calculate road boundaries
        game.roadWidth = canvas.width * 0.85;
        game.roadLeft = (canvas.width - game.roadWidth) / 2;
        game.roadRight = game.roadLeft + game.roadWidth;

        updateHUD();
        showCountdown();
    }

    // ==================== SCREENS ====================
    function showLevelSelect() {
        game.state = 'menu';
        levelSelect.classList.remove('hidden');
        countdown.classList.add('hidden');
        gameOver.classList.add('hidden');
        updateHighScores();
        stopEngine();
    }

    function showCountdown() {
        game.state = 'countdown';
        levelSelect.classList.add('hidden');
        countdown.classList.remove('hidden');
        let count = 3;
        countdownNumber.textContent = count;

        const interval = setInterval(() => {
            count--;
            if (count > 0) {
                countdownNumber.textContent = count;
                playTone(400, 0.1, 'sine', 0.1);
            } else {
                clearInterval(interval);
                countdownNumber.textContent = 'GO!';
                playTone(600, 0.2, 'sine', 0.15);
                setTimeout(() => {
                    countdown.classList.add('hidden');
                    startGameplay();
                }, 500);
            }
        }, 800);
    }

    function startGameplay() {
        game.state = 'playing';
        game.engineRunning = true;
        startEngine();
        gameLoop();
    }

    function gameOverHandler() {
        game.state = 'gameover';
        stopEngine();
        playCrashSound();

        const grade = calculateGrade();
        finalScore.textContent = Math.floor(game.score);
        finalDistance.textContent = Math.floor(game.distance) + 'm';
        finalSpeed.textContent = Math.floor(game.topSpeed) + ' km/h';
        finalGrade.textContent = grade;

        if (game.score > game.highScores[game.level]) {
            game.highScores[game.level] = Math.floor(game.score);
            localStorage.setItem('racer_high_' + game.level, game.highScores[game.level]);
            updateHighScores();
        }

        gameOver.classList.remove('hidden');
    }

    function calculateGrade() {
        const s = game.score;
        if (s > 5000) return 'S';
        if (s > 3000) return 'A';
        if (s > 2000) return 'B';
        if (s > 1000) return 'C';
        if (s > 500) return 'D';
        return 'F';
    }

    function updateHighScores() {
        highScoreDisplay.innerHTML = '';
        ['easy', 'medium', 'hard'].forEach(level => {
            const score = game.highScores[level] || 0;
            const label = level === 'easy' ? '🌞' : level === 'medium' ? '🌆' : '🌙';
            const div = document.createElement('span');
            div.innerHTML = `${label} <span class="score-value">${score}</span>`;
            highScoreDisplay.appendChild(div);
        });
    }

    // ==================== HUD ====================
    function updateHUD() {
        levelDisplay.textContent = game.config.display;
        livesDisplay.textContent = '❤️'.repeat(Math.max(0, game.lives)) + '🖤'.repeat(Math.max(0, game.maxLives - game.lives));
        scoreDisplay.textContent = Math.floor(game.score);
        distanceDisplay.textContent = Math.floor(game.distance) + 'm';
        speedDisplay.textContent = Math.floor(game.currentSpeed) + ' km/h';
        comboDisplay.textContent = 'x' + Math.floor(game.combo);
    }

    // ==================== SPAWN FUNCTIONS ====================
    function spawnEnemy() {
        let types = [];
        const level = game.level;
        
        if (level === 'easy') {
            types = ['compact', 'compact', 'compact', 'compact', 'suv', 'suv'];
        } else if (level === 'medium') {
            types = ['compact', 'compact', 'suv', 'suv', 'truck', 'motorbike', 'motorbike'];
        } else {
            types = ['compact', 'suv', 'suv', 'truck', 'truck', 'bus', 'motorbike', 'motorbike'];
        }
        
        const typeName = types[Math.floor(Math.random() * types.length)];
        const vehicleType = VEHICLE_TYPES[typeName];
        
        const laneWidth = game.roadWidth / game.config.lanes;
        const laneIndex = Math.random() * game.config.lanes;
        const laneCenter = game.roadLeft + laneIndex * laneWidth + laneWidth / 2;
        
        const maxOffset = laneWidth * 0.4;
        const randomOffset = (Math.random() - 0.5) * maxOffset * 2;
        
        let x = laneCenter + randomOffset;
        const halfWidth = vehicleType.width / 2;
        x = Math.max(game.roadLeft + halfWidth + 5, Math.min(game.roadRight - halfWidth - 5, x));
        
        const colors = ['#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c', '#e67e22', '#27ae60'];
        const color = colors[Math.floor(Math.random() * colors.length)];
        
        const speedMultiplier = 0.5 + Math.random() * 0.5;
        const baseEnemySpeed = 60 + Math.random() * 80;
        const enemySpeed = baseEnemySpeed * vehicleType.speedModifier * speedMultiplier;
        
        game.enemies.push({
            x: x,
            y: -vehicleType.height - 10,
            width: vehicleType.width,
            height: vehicleType.height,
            type: typeName,
            speed: enemySpeed,
            drift: (Math.random() - 0.5) * 15,
            driftTimer: Math.random() * 100,
            color: color,
            wobble: Math.random() * 100
        });
    }

    function spawnDebris() {
        const types = ['cone', 'barrel', 'tire'];
        const type = types[Math.floor(Math.random() * types.length)];
        const x = game.roadLeft + 10 + Math.random() * (game.roadWidth - 20);
        
        game.debris.push({
            x: x - 10,
            y: -30,
            width: type === 'cone' ? 16 : type === 'barrel' ? 24 : 20,
            height: type === 'cone' ? 20 : type === 'barrel' ? 30 : 20,
            type: type,
            color: type === 'cone' ? '#ff6b35' : type === 'barrel' ? '#c4a86a' : '#2a2a2a'
        });
    }

    function spawnParticles(x, y, count, color, speed) {
        for (let i = 0; i < count; i++) {
            const angle = Math.random() * Math.PI * 2;
            const spd = (Math.random() * 3 + 1) * (speed || 1);
            game.particles.push({
                x: x,
                y: y,
                vx: Math.cos(angle) * spd,
                vy: Math.sin(angle) * spd - 1,
                life: 0.5 + Math.random() * 0.5,
                maxLife: 0.5 + Math.random() * 0.5,
                color: color || '#ff6b6b',
                size: 2 + Math.random() * 4
            });
        }
    }

    // ==================== UPDATE ====================
    function update() {
        if (game.state !== 'playing') return;

        game.frameCount++;
        game.timeElapsed += 1/60;

        // Smooth speed ramp
        const speedIncrease = (game.maxSpeed - game.baseSpeed) / 60 * game.rampRate;
        game.currentSpeed = Math.min(game.currentSpeed + speedIncrease, game.maxSpeed);
        game.distance += game.currentSpeed * 0.008;

        // Combo timer
        if (game.comboTimer > 0) {
            game.comboTimer -= 1/60;
            if (game.comboTimer <= 0) {
                game.combo = 1;
            }
        }

        // Car movement - bounded by road edges
        const moveSpeed = 6 + game.currentSpeed * 0.01;
        const dx = game.car.targetX - game.car.x;
        game.car.x += dx * 0.15;
        game.car.speed = dx * 0.05;
        game.car.tilt += (game.car.speed * 0.5 - game.car.tilt) * 0.1;

        // Road edge collision - prevent going off road
        const margin = game.car.width / 2 + 5;
        const leftBound = game.roadLeft + margin;
        const rightBound = game.roadRight - margin;
        game.car.x = Math.max(leftBound, Math.min(rightBound, game.car.x));
        game.car.targetX = Math.max(leftBound, Math.min(rightBound, game.car.targetX));

        // Road scroll
        game.roadOffset = (game.roadOffset + game.currentSpeed * 0.04) % 60;

        // Invincibility timer
        if (game.invincibleTimer > 0) {
            game.invincibleTimer -= 1/60;
        }

        // Screen shake
        if (game.screenShake > 0) {
            game.screenShake *= 0.9;
            if (game.screenShake < 0.1) game.screenShake = 0;
        }

        // Spawn enemies
        game.spawnTimer -= 1/60;
        if (game.spawnTimer <= 0) {
            const spawnRate = game.config.spawnRate - (game.currentSpeed - game.baseSpeed) * 0.001;
            const rate = Math.max(0.3, spawnRate);
            
            const enemyCount = game.enemies.length;
            const maxEnemies = game.config.maxEnemies + Math.floor((game.currentSpeed - game.baseSpeed) / 50);
            
            if (enemyCount < maxEnemies) {
                if (Math.random() < 0.6) spawnEnemy();
                if (Math.random() < 0.15) spawnDebris();
            }
            
            game.spawnTimer = rate + Math.random() * 0.5;
        }

        // Build player collision box (pixel-perfect)
        const playerBox = {
            x: game.car.x + 3,
            y: game.car.y + 3,
            width: game.car.width - 6,
            height: game.car.height - 6
        };

        // Update enemies and check collisions
        for (let i = game.enemies.length - 1; i >= 0; i--) {
            const enemy = game.enemies[i];
            
            const speedFactor = enemy.speed / 150;
            const moveAmount = (game.currentSpeed * 0.6 + enemy.speed) * 0.02 * speedFactor;
            enemy.y += moveAmount;
            
            if (enemy.type === 'motorbike') {
                enemy.wobble += 0.05;
                enemy.x += Math.sin(enemy.wobble) * 0.8;
            } else {
                enemy.driftTimer += 0.02;
                enemy.x += Math.sin(enemy.driftTimer) * 0.2;
            }
            
            const halfW = enemy.width / 2;
            enemy.x = Math.max(game.roadLeft + halfW + 2, Math.min(game.roadRight - halfW - 2, enemy.x));

            if (enemy.y > canvas.height + 50) {
                game.enemies.splice(i, 1);
                continue;
            }

            // Build enemy collision box (pixel-perfect based on actual dimensions)
            const enemyBox = {
                x: enemy.x + 3,
                y: enemy.y + 3,
                width: enemy.width - 6,
                height: enemy.height - 6
            };

            // Pixel-perfect collision detection
            if (game.invincibleTimer <= 0) {
                if (playerBox.x < enemyBox.x + enemyBox.width &&
                    playerBox.x + playerBox.width > enemyBox.x &&
                    playerBox.y < enemyBox.y + enemyBox.height &&
                    playerBox.y + playerBox.height > enemyBox.y) {
                    
                    // Collision detected!
                    game.lives--;
                    game.invincibleTimer = 1.5;
                    game.screenShake = 15;
                    game.combo = 1;
                    
                    spawnParticles(enemy.x + enemy.width/2, enemy.y + enemy.height/2, 30, '#ff6b6b', 2);
                    spawnParticles(enemy.x + enemy.width/2, enemy.y + enemy.height/2, 20, '#ffd93d', 1.5);
                    playCrashSound();
                    game.enemies.splice(i, 1);

                    if (game.lives <= 0) {
                        gameOverHandler();
                        return;
                    }
                    updateHUD();
                    continue;
                }

                // Close dodge detection
                const centerX = playerBox.x + playerBox.width/2;
                const centerY = playerBox.y + playerBox.height/2;
                const eCenterX = enemyBox.x + enemyBox.width/2;
                const eCenterY = enemyBox.y + enemyBox.height/2;
                const dx2 = centerX - eCenterX;
                const dy2 = centerY - eCenterY;
                const dist = Math.sqrt(dx2*dx2 + dy2*dy2);
                
                if (dist < 70 && dist > 25 && enemy.y > game.car.y - 20) {
                    game.closeDodges++;
                    game.combo = Math.min(5, game.combo + 0.2);
                    game.comboTimer = 2;
                    game.score += Math.floor(5 * game.combo);
                    playWhooshSound();
                    spawnParticles(enemy.x + enemy.width/2, enemy.y + enemy.height, 5, '#4ecdc4', 0.5);
                    updateHUD();
                }
            }
        }

        // Update debris
        for (let i = game.debris.length - 1; i >= 0; i--) {
            const debris = game.debris[i];
            debris.y += game.currentSpeed * 0.02;

            if (debris.y > canvas.height + 30) {
                game.debris.splice(i, 1);
                continue;
            }

            if (game.invincibleTimer <= 0) {
                const debrisBox = {
                    x: debris.x,
                    y: debris.y,
                    width: debris.width,
                    height: debris.height
                };
                if (playerBox.x < debrisBox.x + debrisBox.width &&
                    playerBox.x + playerBox.width > debrisBox.x &&
                    playerBox.y < debrisBox.y + debrisBox.height &&
                    playerBox.y + playerBox.height > debrisBox.y) {
                    
                    game.lives--;
                    game.invincibleTimer = 1.5;
                    game.screenShake = 10;
                    game.combo = 1;
                    spawnParticles(debris.x + debris.width/2, debris.y + debris.height/2, 15, '#ffd93d', 1.5);
                    playCrashSound();
                    game.debris.splice(i, 1);

                    if (game.lives <= 0) {
                        gameOverHandler();
                        return;
                    }
                    updateHUD();
                }
            }
        }

        // Update particles
        for (let i = game.particles.length - 1; i >= 0; i--) {
            const p = game.particles[i];
            p.x += p.vx;
            p.y += p.vy;
            p.vy += 0.1;
            p.life -= 0.02;
            if (p.life <= 0) {
                game.particles.splice(i, 1);
            }
        }

        // Rain for hard level
        if (game.level === 'hard') {
            if (game.frameCount % 2 === 0) {
                game.rain.push({
                    x: Math.random() * canvas.width,
                    y: -5,
                    speed: 5 + Math.random() * 5,
                    length: 10 + Math.random() * 15,
                    opacity: 0.2 + Math.random() * 0.3
                });
            }
            for (let i = game.rain.length - 1; i >= 0; i--) {
                const r = game.rain[i];
                r.y += r.speed;
                if (r.y > canvas.height) {
                    game.rain.splice(i, 1);
                }
            }
        }

        // Score per second
        game.score += game.currentSpeed * 0.001;
        game.topSpeed = Math.max(game.topSpeed, game.currentSpeed);

        // Update engine sound
        if (game.frameCount % 5 === 0) {
            updateEngine(game.currentSpeed);
        }

        updateHUD();
    }

    // ==================== DRAW ====================
    function draw() {
        ctx.save();

        if (game.screenShake > 0.5) {
            const shakeX = (Math.random() - 0.5) * game.screenShake;
            const shakeY = (Math.random() - 0.5) * game.screenShake;
            ctx.translate(shakeX, shakeY);
        }

        const config = game.config;
        const colors = config.colors;

        // Background
        const bgGrad = ctx.createLinearGradient(0, 0, 0, canvas.height);
        if (game.level === 'hard') {
            bgGrad.addColorStop(0, '#0a0a1e');
            bgGrad.addColorStop(0.5, '#1a1a2e');
            bgGrad.addColorStop(1, '#0a0a1e');
        } else if (game.level === 'medium') {
            bgGrad.addColorStop(0, '#e8a87a');
            bgGrad.addColorStop(0.5, '#d4a06a');
            bgGrad.addColorStop(1, '#c4905a');
        } else {
            bgGrad.addColorStop(0, '#87CEEB');
            bgGrad.addColorStop(0.3, '#98D8C8');
            bgGrad.addColorStop(0.7, '#2d5a27');
            bgGrad.addColorStop(1, '#1a4a1a');
        }
        ctx.fillStyle = bgGrad;
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Road
        const roadWidth = canvas.width * 0.85;
        const roadX = (canvas.width - roadWidth) / 2;
        game.roadLeft = roadX;
        game.roadRight = roadX + roadWidth;
        game.roadWidth = roadWidth;
        
        ctx.fillStyle = colors.road;
        ctx.fillRect(roadX, 0, roadWidth, canvas.height);

        // Road edges
        ctx.strokeStyle = 'rgba(255,255,255,0.15)';
        ctx.lineWidth = 3;
        ctx.setLineDash([8, 12]);
        ctx.beginPath();
        ctx.moveTo(roadX, 0);
        ctx.lineTo(roadX, canvas.height);
        ctx.moveTo(roadX + roadWidth, 0);
        ctx.lineTo(roadX + roadWidth, canvas.height);
        ctx.stroke();
        ctx.setLineDash([]);

        // Lane markings
        const laneWidth = roadWidth / game.config.lanes;
        ctx.strokeStyle = 'rgba(255,255,255,0.3)';
        ctx.lineWidth = 2;
        ctx.setLineDash([15, 20]);
        for (let i = 1; i < game.config.lanes; i++) {
            const x = roadX + i * laneWidth;
            ctx.beginPath();
            ctx.moveTo(x, game.roadOffset % 35 - 35);
            ctx.lineTo(x, canvas.height + 35);
            ctx.stroke();
        }
        ctx.setLineDash([]);

        // Center line
        ctx.strokeStyle = 'rgba(255,255,255,0.4)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        const centerX = canvas.width / 2;
        ctx.moveTo(centerX, 0);
        ctx.lineTo(centerX, canvas.height);
        ctx.stroke();

        // Sidewalk/scenery
        const sceneryOffset = (game.roadOffset * 1.5) % 200;
        if (game.level === 'easy') {
            for (let i = 0; i < 6; i++) {
                const y = (i * 80 + sceneryOffset) % (canvas.height + 100) - 50;
                const x = (i % 2 === 0) ? 10 : canvas.width - 10;
                ctx.fillStyle = '#2d7a3a';
                ctx.beginPath();
                ctx.arc(x, y, 15, 0, Math.PI * 2);
                ctx.fill();
                ctx.fillStyle = '#4a6a3a';
                ctx.fillRect(x - 3, y + 10, 6, 15);
            }
        } else if (game.level === 'medium') {
            for (let i = 0; i < 8; i++) {
                const y = (i * 100 + sceneryOffset * 0.5) % (canvas.height + 100) - 50;
                const x = (i % 2 === 0) ? 5 : canvas.width - 5;
                const w = 15 + Math.random() * 10;
                const h = 30 + Math.random() * 40;
                ctx.fillStyle = `hsl(40, ${10 + Math.random() * 20}%, ${30 + Math.random() * 20}%)`;
                ctx.fillRect(x - w/2, y - h, w, h);
                ctx.fillStyle = 'rgba(255,200,50,0.3)';
                for (let wy = y - h + 5; wy < y - 5; wy += 10) {
                    for (let wx = x - w/2 + 3; wx < x + w/2 - 3; wx += 6) {
                        if (Math.random() > 0.3) {
                            ctx.fillRect(wx, wy, 3, 4);
                        }
                    }
                }
            }
        } else {
            const glow = ctx.createRadialGradient(canvas.width/2, canvas.height/2, 50, canvas.width/2, canvas.height/2, 300);
            glow.addColorStop(0, 'rgba(255,200,100,0.05)');
            glow.addColorStop(1, 'rgba(0,0,0,0)');
            ctx.fillStyle = glow;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        }

        // Rain (hard level)
        if (game.level === 'hard') {
            game.rain.forEach(r => {
                ctx.strokeStyle = `rgba(255,255,255,${r.opacity})`;
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(r.x, r.y);
                ctx.lineTo(r.x - 3, r.y + r.length);
                ctx.stroke();
            });
            for (let i = 0; i < 5; i++) {
                const px = roadX + 20 + Math.random() * (roadWidth - 40);
                const py = (i * 150 + game.roadOffset * 0.3) % (canvas.height + 50) - 25;
                const pw = 20 + Math.random() * 30;
                ctx.fillStyle = `rgba(200,220,255,${0.05 + Math.random() * 0.05})`;
                ctx.beginPath();
                ctx.ellipse(px, py, pw/2, 5, 0, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        // Draw debris
        game.debris.forEach(d => {
            ctx.fillStyle = d.color;
            ctx.shadowColor = 'rgba(0,0,0,0.2)';
            ctx.shadowBlur = 5;
            if (d.type === 'cone') {
                ctx.beginPath();
                ctx.moveTo(d.x + d.width/2, d.y);
                ctx.lineTo(d.x, d.y + d.height);
                ctx.lineTo(d.x + d.width, d.y + d.height);
                ctx.closePath();
                ctx.fill();
            } else {
                ctx.fillRect(d.x, d.y, d.width, d.height);
                if (d.type === 'barrel') {
                    ctx.fillStyle = 'rgba(0,0,0,0.2)';
                    ctx.fillRect(d.x + 2, d.y + 2, d.width - 4, 4);
                    ctx.fillRect(d.x + 2, d.y + d.height - 6, d.width - 4, 4);
                }
            }
            ctx.shadowBlur = 0;
        });

        // Draw enemies
        game.enemies.forEach(enemy => {
            drawVehicle(ctx, enemy.x, enemy.y, enemy.width, enemy.height, enemy.color, enemy.type);
        });

        // Draw player car
        if (game.invincibleTimer > 0 && Math.floor(game.invincibleTimer * 10) % 2 === 0) {
            ctx.globalAlpha = 0.5;
        }
        drawVehicle(ctx, game.car.x, game.car.y, game.car.width, game.car.height, '#e74c3c', 'player', game.car.tilt);
        ctx.globalAlpha = 1;

        // Car shadow
        ctx.shadowColor = 'rgba(0,0,0,0.3)';
        ctx.shadowBlur = 15;
        ctx.fillStyle = 'rgba(0,0,0,0.1)';
        ctx.beginPath();
        ctx.ellipse(game.car.x + game.car.width/2, game.car.y + game.car.height + 5, game.car.width/2 + 5, 8, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.shadowBlur = 0;

        // Particles
        game.particles.forEach(p => {
            ctx.globalAlpha = p.life / p.maxLife;
            ctx.fillStyle = p.color;
            ctx.shadowColor = p.color;
            ctx.shadowBlur = 5;
            ctx.fillRect(p.x - p.size/2, p.y - p.size/2, p.size, p.size);
        });
        ctx.globalAlpha = 1;
        ctx.shadowBlur = 0;

        // Speed blur lines
        if (game.currentSpeed > game.baseSpeed * 1.2) {
            const intensity = (game.currentSpeed - game.baseSpeed) / game.baseSpeed;
            ctx.strokeStyle = `rgba(255,255,255,${intensity * 0.03})`;
            ctx.lineWidth = 2;
            for (let i = 0; i < 15; i++) {
                const x = Math.random() * canvas.width;
                const y = Math.random() * canvas.height;
                ctx.beginPath();
                ctx.moveTo(x, y);
                ctx.lineTo(x + (Math.random() - 0.5) * 15, y + 30 + Math.random() * 40);
                ctx.stroke();
            }
        }

        ctx.restore();

        // Headlight glow (hard level)
        if (game.level === 'hard') {
            const glow2 = ctx.createRadialGradient(
                game.car.x + game.car.width/2, game.car.y + 10, 5,
                game.car.x + game.car.width/2, game.car.y + 10, 60
            );
            glow2.addColorStop(0, 'rgba(255,255,200,0.2)');
            glow2.addColorStop(1, 'rgba(255,255,200,0)');
            ctx.fillStyle = glow2;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        }
    }

    // ==================== VEHICLE DRAWING ====================
    function drawVehicle(ctx, x, y, w, h, color, type, tilt) {
        ctx.save();
        ctx.translate(x + w/2, y + h/2);

        if (tilt) {
            ctx.rotate(tilt * 0.02);
        }

        const halfW = w / 2;
        const halfH = h / 2;

        ctx.shadowColor = 'rgba(0,0,0,0.3)';
        ctx.shadowBlur = 10;
        ctx.shadowOffsetX = 2;
        ctx.shadowOffsetY = 4;

        const grad = ctx.createLinearGradient(-halfW, -halfH, halfW, halfH);
        if (type === 'player') {
            grad.addColorStop(0, '#ff6b6b');
            grad.addColorStop(0.5, '#e74c3c');
            grad.addColorStop(1, '#c0392b');
        } else {
            grad.addColorStop(0, color);
            grad.addColorStop(0.5, color);
            grad.addColorStop(1, darkenColor(color));
        }
        ctx.fillStyle = grad;

        const r = Math.min(4, w * 0.1);
        ctx.beginPath();
        ctx.moveTo(-halfW + r, -halfH);
        ctx.lineTo(halfW - r, -halfH);
        ctx.quadraticCurveTo(halfW, -halfH, halfW, -halfH + r);
        ctx.lineTo(halfW, halfH - r);
        ctx.quadraticCurveTo(halfW, halfH, halfW - r, halfH);
        ctx.lineTo(-halfW + r, halfH);
        ctx.quadraticCurveTo(-halfW, halfH, -halfW, halfH - r);
        ctx.lineTo(-halfW, -halfH + r);
        ctx.quadraticCurveTo(-halfW, -halfH, -halfW + r, -halfH);
        ctx.closePath();
        ctx.fill();

        ctx.shadowBlur = 0;

        // Vehicle-specific details
        if (type === 'player' || type === 'compact' || type === 'suv') {
            ctx.fillStyle = 'rgba(135, 206, 250, 0.4)';
            const winW = w * 0.5;
            const winH = h * 0.25;
            ctx.fillRect(-winW/2, -halfH + 2, winW, winH);
            ctx.fillRect(-winW/2, halfH - winH - 2, winW, winH);
            
            ctx.strokeStyle = 'rgba(255,255,255,0.1)';
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(0, -halfH + 2);
            ctx.lineTo(0, -halfH + 2 + winH);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(0, halfH - winH - 2);
            ctx.lineTo(0, halfH - 2);
            ctx.stroke();
        } else if (type === 'truck') {
            ctx.fillStyle = 'rgba(200,200,200,0.3)';
            ctx.fillRect(-halfW * 0.8, -halfH * 0.1, w * 0.6, h * 0.4);
            ctx.fillStyle = 'rgba(135, 206, 250, 0.3)';
            ctx.fillRect(-halfW * 0.6, -halfH * 0.5, w * 0.4, h * 0.25);
        } else if (type === 'bus') {
            ctx.fillStyle = 'rgba(135, 206, 250, 0.3)';
            for (let i = 0; i < 4; i++) {
                const wx = -halfW * 0.7 + i * (w * 0.2);
                ctx.fillRect(wx, -halfH * 0.3, w * 0.12, h * 0.6);
            }
            ctx.fillStyle = 'rgba(255,255,255,0.2)';
            ctx.fillRect(-halfW * 0.8, 0, w * 0.6, 3);
            ctx.fillRect(-halfW * 0.8, h * 0.2, w * 0.6, 3);
        } else if (type === 'motorbike') {
            ctx.fillStyle = color;
            ctx.fillRect(-2, -halfH * 0.7, 4, h * 0.8);
            ctx.fillStyle = 'rgba(0,0,0,0.5)';
            ctx.fillRect(-5, -halfH * 0.8, 10, 3);
            ctx.fillStyle = '#ffd700';
            ctx.beginPath();
            ctx.arc(0, -halfH * 0.85, 3, 0, Math.PI * 2);
            ctx.fill();
        }

        // Headlights
        ctx.fillStyle = '#ffd700';
        ctx.shadowColor = '#ffd700';
        ctx.shadowBlur = type === 'player' ? 10 : 3;
        ctx.fillRect(-halfW + 2, -halfH - 2, 6, 4);
        ctx.fillRect(halfW - 8, -halfH - 2, 6, 4);
        ctx.shadowBlur = 0;

        // Taillights
        ctx.fillStyle = '#ff0000';
        ctx.shadowColor = '#ff0000';
        ctx.shadowBlur = 3;
        ctx.fillRect(-halfW + 2, halfH - 2, 5, 3);
        ctx.fillRect(halfW - 7, halfH - 2, 5, 3);
        ctx.shadowBlur = 0;

        // Wheels
        ctx.fillStyle = '#1a1a2e';
        const wheelW = Math.max(3, w * 0.1);
        const wheelH = h * 0.15;
        ctx.fillRect(-halfW - 1, -halfH + 6, wheelW, wheelH);
        ctx.fillRect(-halfW - 1, halfH - 6 - wheelH, wheelW, wheelH);
        ctx.fillRect(halfW - wheelW, -halfH + 6, wheelW, wheelH);
        ctx.fillRect(halfW - wheelW, halfH - 6 - wheelH, wheelW, wheelH);

        ctx.restore();
    }

    function darkenColor(color) {
        let r = parseInt(color.slice(1,2), 16) * 17;
        let g = parseInt(color.slice(2,3), 16) * 17;
        let b = parseInt(color.slice(3,4), 16) * 17;
        r = Math.floor(r * 0.7);
        g = Math.floor(g * 0.7);
        b = Math.floor(b * 0.7);
        return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
    }

    // ==================== GAME LOOP ====================
    function gameLoop() {
        if (game.state === 'playing') {
            update();
            draw();
        }
        requestAnimationFrame(gameLoop);
    }

    // ==================== CONTROLS ====================
    document.addEventListener('keydown', function(e) {
        const key = e.key;
        if (['ArrowLeft', 'ArrowRight', 'a', 'A', 'd', 'D'].includes(key)) {
            e.preventDefault();
        }
        if (game.state === 'playing' || game.state === 'countdown') {
            const moveAmount = 15 + game.currentSpeed * 0.02;
            const leftBound = game.roadLeft + game.car.width / 2 + 5;
            const rightBound = game.roadRight - game.car.width / 2 - 5;
            
            switch(key) {
                case 'ArrowLeft': case 'a': case 'A':
                    game.car.targetX = Math.max(leftBound, game.car.targetX - moveAmount);
                    break;
                case 'ArrowRight': case 'd': case 'D':
                    game.car.targetX = Math.min(rightBound, game.car.targetX + moveAmount);
                    break;
            }
        }
    });

    // Mobile Controls
    let touchLeft = false;
    let touchRight = false;

    function setupTouchButton(el, direction) {
        if (!el) return;

        const move = () => {
            if (game.state === 'playing' || game.state === 'countdown') {
                const moveAmount = 15 + game.currentSpeed * 0.02;
                const leftBound = game.roadLeft + game.car.width / 2 + 5;
                const rightBound = game.roadRight - game.car.width / 2 - 5;
                
                if (direction === 'left') {
                    game.car.targetX = Math.max(leftBound, game.car.targetX - moveAmount);
                } else {
                    game.car.targetX = Math.min(rightBound, game.car.targetX + moveAmount);
                }
            }
        };

        el.addEventListener('touchstart', function(e) {
            e.preventDefault();
            touchLeft = direction === 'left';
            touchRight = direction === 'right';
            move();
        });

        el.addEventListener('touchmove', function(e) {
            e.preventDefault();
            move();
        });

        el.addEventListener('touchend', function(e) {
            e.preventDefault();
            touchLeft = false;
            touchRight = false;
        });

        el.addEventListener('touchcancel', function(e) {
            touchLeft = false;
            touchRight = false;
        });

        el.addEventListener('mousedown', function(e) {
            touchLeft = direction === 'left';
            touchRight = direction === 'right';
            move();
        });

        el.addEventListener('mouseup', function(e) {
            touchLeft = false;
            touchRight = false;
        });

        el.addEventListener('mouseleave', function(e) {
            touchLeft = false;
            touchRight = false;
        });
    }

    setupTouchButton(btnLeft, 'left');
    setupTouchButton(btnRight, 'right');

    // Auto-move with touch
    setInterval(() => {
        if (touchLeft) {
            const moveAmount = 15 + game.currentSpeed * 0.02;
            const leftBound = game.roadLeft + game.car.width / 2 + 5;
            game.car.targetX = Math.max(leftBound, game.car.targetX - moveAmount);
        }
        if (touchRight) {
            const moveAmount = 15 + game.currentSpeed * 0.02;
            const rightBound = game.roadRight - game.car.width / 2 - 5;
            game.car.targetX = Math.min(rightBound, game.car.targetX + moveAmount);
        }
    }, 16);

    // ==================== START GAME (GLOBAL) ====================
    window.startGame = function(level) {
        initAudio();
        initGame(level);
        updateHighScores();
    };

    window.showLevelSelect = function() {
        showLevelSelect();
    };

    // ==================== INIT ====================
    showLevelSelect();
    updateHighScores();

    // Start render loop
    function renderLoop() {
        if (game.state === 'menu' || game.state === 'gameover' || game.state === 'countdown') {
            draw();
        }
        requestAnimationFrame(renderLoop);
    }
    renderLoop();

})();
</script>
@include('partials.tool-content')

@endsection