@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>🏍️ Bike Racer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }

        .bike-racer-wrapper {
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            min-height: calc(100vh - 200px);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #gameWrapper {
            background: linear-gradient(180deg, #2c3e50, #34495e);
            border-radius: 20px;
            padding: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.8);
            width: 100%;
            position: relative;
        }

        #gameContainer {
            position: relative;
            width: 100%;
            aspect-ratio: 400 / 700;
            background: #2c3e50;
            border-radius: 12px;
            overflow: hidden;
            touch-action: none;
        }

        canvas {
            display: block;
            width: 100% !important;
            height: 100% !important;
            background: #2c3e50;
            touch-action: none;
            cursor: none;
        }

        /* UI Overlay */
        #uiOverlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        #scoreDisplay {
            position: absolute;
            top: 15px;
            left: 15px;
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
            background: rgba(0,0,0,0.4);
            padding: 8px 15px;
            border-radius: 20px;
            backdrop-filter: blur(5px);
            pointer-events: none;
            z-index: 10;
        }

        #highScoreDisplay {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #f1c40f;
            font-size: 16px;
            font-weight: bold;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
            background: rgba(0,0,0,0.4);
            padding: 8px 15px;
            border-radius: 20px;
            backdrop-filter: blur(5px);
            pointer-events: none;
            z-index: 10;
        }

        #speedDisplay {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            color: #4fc3f7;
            font-size: 14px;
            font-weight: bold;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
            background: rgba(0,0,0,0.4);
            padding: 5px 15px;
            border-radius: 20px;
            backdrop-filter: blur(5px);
            pointer-events: none;
            z-index: 10;
        }

        #livesDisplay {
            position: absolute;
            bottom: 80px;
            right: 15px;
            color: #ff6b6b;
            font-size: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
            pointer-events: none;
            z-index: 10;
        }

        /* Speed Control */
        #speedControl {
            position: absolute;
            top: 70px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 10;
            pointer-events: all;
        }

        #speedControl button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.2s;
            pointer-events: all;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.2);
        }

        #speedControl button:hover {
            background: rgba(255,255,255,0.4);
            transform: scale(1.1);
        }

        #speedControl button:active {
            transform: scale(0.9);
        }

        /* Mobile Controls */
        #mobileControls {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            display: none;
            justify-content: space-between;
            padding: 0 15px;
            pointer-events: none;
            z-index: 20;
        }

        .control-btn {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            font-size: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: all;
            touch-action: none;
            transition: all 0.1s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .control-btn:active {
            transform: scale(0.9);
            background: rgba(255,255,255,0.4);
        }

        #leftBtn {
            margin-right: auto;
        }

        #rightBtn {
            margin-left: auto;
        }

        /* Game Over Overlay */
        #gameOverlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 30;
            backdrop-filter: blur(5px);
            pointer-events: all;
        }

        #gameOverlay.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        #gameOverlay h1 {
            color: #ff6b6b;
            font-size: 42px;
            text-shadow: 0 4px 20px rgba(255,0,0,0.3);
            margin-bottom: 10px;
        }

        #gameOverlay .final-score {
            color: white;
            font-size: 28px;
            margin: 10px 0;
        }

        #gameOverlay .high-score {
            color: #f1c40f;
            font-size: 20px;
            margin-bottom: 20px;
        }

        #gameOverlay button {
            padding: 15px 40px;
            font-size: 20px;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, #f1c40f, #f39c12);
            color: #2c3e50;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(241, 196, 15, 0.4);
            pointer-events: all;
            margin: 5px;
        }

        #gameOverlay button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 30px rgba(241, 196, 15, 0.6);
        }

        #gameOverlay button:active {
            transform: scale(0.95);
        }

        /* Start Screen */
        #startScreen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 25;
            backdrop-filter: blur(3px);
            pointer-events: all;
        }

        #startScreen h1 {
            color: #f1c40f;
            font-size: 48px;
            text-shadow: 0 4px 20px rgba(0,0,0,0.5);
            margin-bottom: 10px;
        }

        #startScreen .subtitle {
            color: #bdc3c7;
            font-size: 18px;
            margin-bottom: 20px;
        }

        #startScreen .controls-info {
            color: #95a5a6;
            font-size: 14px;
            margin: 10px 0;
            text-align: center;
            line-height: 1.8;
        }

        #startScreen button {
            padding: 18px 50px;
            font-size: 22px;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(46, 204, 113, 0.4);
            pointer-events: all;
            margin-top: 20px;
        }

        #startScreen button:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 30px rgba(46, 204, 113, 0.6);
        }

        #startScreen button:active {
            transform: scale(0.95);
        }

        /* History Section */
        #historySection {
            margin-top: 15px;
            padding: 15px;
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            max-height: 150px;
            overflow-y: auto;
        }

        #historySection h4 {
            color: #f1c40f;
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .history-item {
            display: flex;
            justify-content: space-between;
            padding: 4px 10px;
            background: rgba(255,255,255,0.05);
            border-radius: 4px;
            margin-bottom: 3px;
            font-size: 12px;
            color: #bdc3c7;
        }

        .history-item .h-score {
            color: #f1c40f;
            font-weight: bold;
        }

        #historySection::-webkit-scrollbar {
            width: 3px;
        }
        #historySection::-webkit-scrollbar-thumb {
            background: #f1c40f;
            border-radius: 2px;
        }

        @media (max-width: 480px) {
            #gameWrapper {
                padding: 8px;
                border-radius: 12px;
            }
            
            #scoreDisplay {
                font-size: 14px;
                padding: 5px 12px;
                top: 10px;
                left: 10px;
            }
            
            #highScoreDisplay {
                font-size: 12px;
                padding: 5px 12px;
                top: 10px;
                right: 10px;
            }
            
            #mobileControls {
                display: flex !important;
            }
            
            .control-btn {
                width: 55px;
                height: 55px;
                font-size: 24px;
            }

            #startScreen h1 {
                font-size: 32px;
            }

            #gameOverlay h1 {
                font-size: 30px;
            }

            #gameOverlay .final-score {
                font-size: 22px;
            }

            #speedControl button {
                width: 32px;
                height: 32px;
                font-size: 16px;
            }
        }

        @media (pointer: coarse) {
            #mobileControls {
                display: flex !important;
            }
        }
    </style>
</head>
<body>

<div class="bike-racer-wrapper">
    <div id="gameWrapper">
        <div id="gameContainer">
            <canvas id="gameCanvas"></canvas>
            
            <!-- UI Overlay -->
            <div id="uiOverlay">
                <div id="scoreDisplay">🏆 <span id="score">0</span></div>
                <div id="highScoreDisplay">⭐ <span id="highScore">0</span></div>
                <div id="speedDisplay">⚡ Speed: <span id="speed">1</span>x</div>
                <div id="livesDisplay">❤️ <span id="lives">3</span></div>
                
                <!-- Speed Control -->
                <div id="speedControl">
                    <button id="speedUpBtn" title="Increase Speed">⬆</button>
                    <button id="speedDownBtn" title="Decrease Speed">⬇</button>
                </div>
            </div>

            <!-- Mobile Controls -->
            <div id="mobileControls">
                <button class="control-btn" id="leftBtn">◀</button>
                <button class="control-btn" id="rightBtn">▶</button>
            </div>

            <!-- Start Screen -->
            <div id="startScreen">
                <h1>🏍️ BIKE RACER</h1>
                <div class="subtitle">Endless Runner</div>
                <div class="controls-info">
                    🎮 Arrow Keys / A D to move<br>
                    📱 Swipe or tap buttons on mobile<br>
                    ⬆⬇ Speed buttons to control pace<br>
                    🪙 Collect coins for bonus points
                </div>
                <button id="startBtn">🚀 START RACE</button>
            </div>

            <!-- Game Overlay -->
            <div id="gameOverlay">
                <h2>💥 GAME OVER</h2>
                <div class="final-score">Score: <span id="finalScore">0</span></div>
                <div class="high-score">🏆 Best: <span id="finalHighScore">0</span></div>
                <button id="restartBtn">🔄 Play Again</button>
            </div>
        </div>

        <!-- History Section -->
        <div id="historySection">
            <h4>📊 Recent Games</h4>
            <div id="historyList"></div>
        </div>
    </div>
</div>

<script>
// ============================================================
// 🏍️ BIKE RACER - Full Game Implementation
// ============================================================

class BikeRacer {
    constructor() {
        this.canvas = document.getElementById('gameCanvas');
        this.ctx = this.canvas.getContext('2d');
        
        // Game dimensions
        this.width = 400;
        this.height = 700;
        this.canvas.width = this.width;
        this.canvas.height = this.height;
        
        // Game state
        this.state = 'menu'; // menu, playing, gameover
        this.score = 0;
        this.highScore = parseInt(localStorage.getItem('bikeRacerHighScore')) || 0;
        this.lives = 3;
        this.speed = 1;
        this.baseSpeed = 3;
        this.distance = 0;
        this.combo = 0;
        this.gameTime = 0;
        this.difficultyTimer = 0;
        this.maxSpeed = 5;
        this.minSpeed = 1;
        
        // Player
        this.player = {
            x: this.width / 2 - 20,
            y: this.height - 150,
            width: 40,
            height: 70,
            speed: 6,
            targetX: this.width / 2 - 20,
            color: '#e74c3c'
        };
        
        // Game objects
        this.obstacles = [];
        this.coins = [];
        this.roadMarkings = [];
        this.particles = [];
        this.powerups = [];
        
        // Road offset for scrolling
        this.roadOffset = 0;
        
        // Controls
        this.keys = {};
        this.touchStartX = 0;
        this.isTouching = false;
        this.mobileLeft = false;
        this.mobileRight = false;
        
        // History
        this.history = JSON.parse(localStorage.getItem('bikeRacerHistory') || '[]');
        
        // Init
        this.initRoadMarkings();
        this.loadHighScore();
        this.setupEventListeners();
        this.renderHistory();
        this.gameLoop();
        
        // UI references
        this.scoreEl = document.getElementById('score');
        this.highScoreEl = document.getElementById('highScore');
        this.speedEl = document.getElementById('speed');
        this.livesEl = document.getElementById('lives');
        this.finalScoreEl = document.getElementById('finalScore');
        this.finalHighScoreEl = document.getElementById('finalHighScore');
        
        // Update UI
        this.updateUI();
        this.highScoreEl.textContent = this.highScore;
    }

    // ============================================================
    // INITIALIZATION
    // ============================================================

    initRoadMarkings() {
        this.roadMarkings = [];
        for (let i = 0; i < 20; i++) {
            this.roadMarkings.push({
                y: i * 40,
                width: 8,
                height: 25,
                x: this.width / 2 - 4
            });
        }
    }

    loadHighScore() {
        const saved = localStorage.getItem('bikeRacerHighScore');
        if (saved) {
            this.highScore = parseInt(saved);
        }
    }

    saveHighScore() {
        localStorage.setItem('bikeRacerHighScore', this.highScore.toString());
    }

    saveHistory() {
        localStorage.setItem('bikeRacerHistory', JSON.stringify(this.history));
    }

    // ============================================================
    // EVENT LISTENERS
    // ============================================================

    setupEventListeners() {
        // Keyboard
        document.addEventListener('keydown', (e) => {
            this.keys[e.key] = true;
            if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                e.preventDefault();
            }
        });
        document.addEventListener('keyup', (e) => {
            this.keys[e.key] = false;
        });

        // Touch / Mouse for canvas
        this.canvas.addEventListener('touchstart', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            const rect = this.canvas.getBoundingClientRect();
            const x = (touch.clientX - rect.left) / rect.width * this.width;
            this.touchStartX = x;
            this.isTouching = true;
        }, { passive: false });

        this.canvas.addEventListener('touchmove', (e) => {
            e.preventDefault();
            const touch = e.touches[0];
            const rect = this.canvas.getBoundingClientRect();
            const x = (touch.clientX - rect.left) / rect.width * this.width;
            const dx = x - this.touchStartX;
            if (Math.abs(dx) > 10) {
                if (dx > 0) {
                    this.mobileRight = true;
                    this.mobileLeft = false;
                } else {
                    this.mobileLeft = true;
                    this.mobileRight = false;
                }
            }
            this.touchStartX = x;
        }, { passive: false });

        this.canvas.addEventListener('touchend', (e) => {
            e.preventDefault();
            this.isTouching = false;
            this.mobileLeft = false;
            this.mobileRight = false;
        }, { passive: false });

        // Mouse controls for desktop
        this.canvas.addEventListener('mousemove', (e) => {
            if (this.state === 'playing') {
                const rect = this.canvas.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width * this.width;
                this.player.targetX = x - this.player.width / 2;
            }
        });

        // Mobile control buttons
        document.getElementById('leftBtn').addEventListener('touchstart', (e) => {
            e.preventDefault();
            this.mobileLeft = true;
        }, { passive: false });
        document.getElementById('leftBtn').addEventListener('touchend', (e) => {
            e.preventDefault();
            this.mobileLeft = false;
        }, { passive: false });
        document.getElementById('rightBtn').addEventListener('touchstart', (e) => {
            e.preventDefault();
            this.mobileRight = true;
        }, { passive: false });
        document.getElementById('rightBtn').addEventListener('touchend', (e) => {
            e.preventDefault();
            this.mobileRight = false;
        }, { passive: false });

        // Speed control buttons
        document.getElementById('speedUpBtn').addEventListener('click', () => {
            this.increaseSpeed();
        });
        document.getElementById('speedDownBtn').addEventListener('click', () => {
            this.decreaseSpeed();
        });

        // Start button
        document.getElementById('startBtn').addEventListener('click', () => {
            this.startGame();
        });

        // Restart button
        document.getElementById('restartBtn').addEventListener('click', () => {
            this.startGame();
        });

        // Prevent context menu on canvas
        this.canvas.addEventListener('contextmenu', (e) => e.preventDefault());
    }

    // ============================================================
    // SPEED CONTROL
    // ============================================================

    increaseSpeed() {
        if (this.state === 'playing') {
            this.speed = Math.min(this.speed + 0.2, this.maxSpeed);
            this.baseSpeed = 3 + (this.speed - 1) * 0.5;
            this.updateUI();
        }
    }

    decreaseSpeed() {
        if (this.state === 'playing') {
            this.speed = Math.max(this.speed - 0.2, this.minSpeed);
            this.baseSpeed = 3 + (this.speed - 1) * 0.5;
            this.updateUI();
        }
    }

    // ============================================================
    // GAME MANAGEMENT
    // ============================================================

    startGame() {
        this.state = 'playing';
        this.score = 0;
        this.lives = 3;
        this.speed = 1;
        this.baseSpeed = 3;
        this.distance = 0;
        this.combo = 0;
        this.gameTime = 0;
        this.difficultyTimer = 0;
        this.obstacles = [];
        this.coins = [];
        this.particles = [];
        this.powerups = [];
        this.player.x = this.width / 2 - 20;
        this.player.targetX = this.width / 2 - 20;
        this.roadOffset = 0;
        
        document.getElementById('startScreen').style.display = 'none';
        document.getElementById('gameOverlay').classList.remove('active');
        
        this.updateUI();
    }

    gameOver() {
        this.state = 'gameover';
        
        // Save to history
        this.history.push({
            date: new Date().toISOString(),
            score: Math.floor(this.score),
            speed: this.speed,
            distance: Math.floor(this.distance)
        });
        if (this.history.length > 20) {
            this.history = this.history.slice(-20);
        }
        this.saveHistory();
        this.renderHistory();
        
        if (this.score > this.highScore) {
            this.highScore = this.score;
            this.saveHighScore();
        }
        document.getElementById('finalScore').textContent = Math.floor(this.score);
        document.getElementById('finalHighScore').textContent = this.highScore;
        document.getElementById('gameOverlay').classList.add('active');
        
        if (navigator.vibrate) {
            navigator.vibrate(200);
        }
    }

    // ============================================================
    // HISTORY RENDERING
    // ============================================================

    renderHistory() {
        const list = document.getElementById('historyList');
        if (this.history.length === 0) {
            list.innerHTML = '<div style="color:#666; font-size:12px; text-align:center;">No games played yet</div>';
            return;
        }
        let html = '';
        const recent = this.history.slice(-5).reverse();
        recent.forEach(item => {
            const date = new Date(item.date);
            const dateStr = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'});
            html += `
                <div class="history-item">
                    <span>${dateStr}</span>
                    <span class="h-score">${item.score} pts</span>
                    <span>⚡${item.speed.toFixed(1)}x</span>
                </div>
            `;
        });
        list.innerHTML = html;
    }

    // ============================================================
    // UPDATE LOGIC
    // ============================================================

    update() {
        if (this.state !== 'playing') return;

        this.gameTime++;
        this.difficultyTimer++;

        // Increase difficulty
        if (this.difficultyTimer > 300) {
            this.difficultyTimer = 0;
            if (this.speed < this.maxSpeed) {
                this.speed = Math.min(this.speed + 0.05, this.maxSpeed);
                this.baseSpeed = 3 + (this.speed - 1) * 0.5;
            }
        }

        // Update distance
        this.distance += this.baseSpeed * 0.1;
        if (this.distance % 10 < 0.5) {
            this.score += 1;
        }

        // Update road offset
        this.roadOffset = (this.roadOffset + this.baseSpeed) % 40;

        // Update player position
        if (this.keys['ArrowLeft'] || this.keys['a'] || this.keys['A'] || this.mobileLeft) {
            this.player.targetX = this.player.x - this.player.speed;
        }
        if (this.keys['ArrowRight'] || this.keys['d'] || this.keys['D'] || this.mobileRight) {
            this.player.targetX = this.player.x + this.player.speed;
        }

        this.player.x += (this.player.targetX - this.player.x) * 0.2;
        this.player.x = Math.max(0, Math.min(this.width - this.player.width, this.player.x));

        // Spawn obstacles
        this.spawnObstacles();
        this.spawnCoins();
        this.spawnPowerups();

        // Update obstacles
        for (let i = this.obstacles.length - 1; i >= 0; i--) {
            const obs = this.obstacles[i];
            obs.y += this.baseSpeed;
            if (obs.y > this.height + 50) {
                this.obstacles.splice(i, 1);
                continue;
            }
            if (this.checkCollision(this.player, obs)) {
                this.lives--;
                this.updateUI();
                this.createExplosion(obs.x + obs.width/2, obs.y + obs.height/2);
                this.obstacles.splice(i, 1);
                if (this.lives <= 0) {
                    this.gameOver();
                    return;
                }
                continue;
            }
        }

        // Update coins
        for (let i = this.coins.length - 1; i >= 0; i--) {
            const coin = this.coins[i];
            coin.y += this.baseSpeed;
            coin.angle += 0.05;
            if (coin.y > this.height + 30) {
                this.coins.splice(i, 1);
                continue;
            }
            if (this.checkCollision(this.player, coin)) {
                this.combo++;
                const bonus = this.combo >= 3 ? 5 : 0;
                const points = 10 + bonus;
                this.score += points;
                this.coins.splice(i, 1);
                this.createParticles(coin.x + coin.size/2, coin.y + coin.size/2, '#f1c40f', 10);
                this.updateUI();
                continue;
            }
        }

        // Update powerups
        for (let i = this.powerups.length - 1; i >= 0; i--) {
            const powerup = this.powerups[i];
            powerup.y += this.baseSpeed;
            if (powerup.y > this.height + 30) {
                this.powerups.splice(i, 1);
                continue;
            }
            if (this.checkCollision(this.player, powerup)) {
                this.applyPowerup(powerup.type);
                this.powerups.splice(i, 1);
                continue;
            }
        }

        // Update particles
        for (let i = this.particles.length - 1; i >= 0; i--) {
            const p = this.particles[i];
            p.x += p.vx;
            p.y += p.vy;
            p.life -= 0.02;
            if (p.life <= 0) {
                this.particles.splice(i, 1);
            }
        }

        this.updateUI();
    }

    // ============================================================
    // SPAWNING
    // ============================================================

    spawnObstacles() {
        const spawnRate = Math.max(30, 80 - this.speed * 10);
        if (Math.random() < 1 / spawnRate) {
            const lane = Math.floor(Math.random() * 3);
            const x = lane * 140 + 20;
            const colors = ['#3498db', '#2ecc71', '#f39c12', '#e67e22', '#9b59b6', '#1abc9c', '#e74c3c'];
            const color = colors[Math.floor(Math.random() * colors.length)];
            const types = ['car', 'truck', 'suv'];
            const type = types[Math.floor(Math.random() * types.length)];
            const width = type === 'truck' ? 50 : 35;
            const height = type === 'truck' ? 70 : 50;
            this.obstacles.push({
                x: x,
                y: -height,
                width: width,
                height: height,
                color: color,
                type: type,
                speed: this.baseSpeed
            });
        }
    }

    spawnCoins() {
        if (Math.random() < 0.02) {
            const lane = Math.floor(Math.random() * 3);
            const x = lane * 140 + 40;
            this.coins.push({
                x: x,
                y: -20,
                size: 16,
                angle: 0,
                color: '#f1c40f'
            });
        }
    }

    spawnPowerups() {
        if (Math.random() < 0.005) {
            const lane = Math.floor(Math.random() * 3);
            const x = lane * 140 + 30;
            const types = ['shield', 'speed'];
            const type = types[Math.floor(Math.random() * types.length)];
            this.powerups.push({
                x: x,
                y: -30,
                width: 30,
                height: 30,
                type: type,
                color: type === 'shield' ? '#3498db' : '#e74c3c'
            });
        }
    }

    applyPowerup(type) {
        if (type === 'shield') {
            this.createParticles(this.player.x + this.player.width/2, this.player.y + this.player.height/2, '#3498db', 20);
        } else if (type === 'speed') {
            this.speed = Math.min(this.speed + 1, this.maxSpeed);
            this.baseSpeed = 3 + (this.speed - 1) * 0.5;
            this.createParticles(this.player.x + this.player.width/2, this.player.y + this.player.height/2, '#e74c3c', 20);
        }
    }

    // ============================================================
    // COLLISION DETECTION
    // ============================================================

    checkCollision(rect1, rect2) {
        return rect1.x < rect2.x + rect2.width &&
               rect1.x + rect1.width > rect2.x &&
               rect1.y < rect2.y + rect2.height &&
               rect1.y + rect1.height > rect2.y;
    }

    // ============================================================
    // PARTICLES / EFFECTS
    // ============================================================

    createExplosion(x, y) {
        const colors = ['#ff6b6b', '#ffd93d', '#ff8a5c', '#ff4757'];
        for (let i = 0; i < 25; i++) {
            const angle = Math.random() * Math.PI * 2;
            const speed = Math.random() * 5 + 2;
            this.particles.push({
                x: x,
                y: y,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed,
                life: 1,
                color: colors[Math.floor(Math.random() * colors.length)],
                size: Math.random() * 6 + 2
            });
        }
    }

    createParticles(x, y, color, count = 15) {
        for (let i = 0; i < count; i++) {
            const angle = Math.random() * Math.PI * 2;
            const speed = Math.random() * 3 + 1;
            this.particles.push({
                x: x,
                y: y,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed - 1,
                life: 1,
                color: color,
                size: Math.random() * 4 + 2
            });
        }
    }

    // ============================================================
    // UI UPDATE
    // ============================================================

    updateUI() {
        this.scoreEl.textContent = Math.floor(this.score);
        this.highScoreEl.textContent = this.highScore;
        this.speedEl.textContent = this.speed.toFixed(1);
        this.livesEl.textContent = this.lives;
    }

    // ============================================================
    // RENDERING - Improved Bike Design
    // ============================================================

    render() {
        const ctx = this.ctx;
        ctx.clearRect(0, 0, this.width, this.height);

        // Background - Sky
        const gradient = ctx.createLinearGradient(0, 0, 0, this.height);
        gradient.addColorStop(0, '#1a3a4a');
        gradient.addColorStop(0.3, '#2d5f7a');
        gradient.addColorStop(0.7, '#4a7a5a');
        gradient.addColorStop(1, '#3a6a4a');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, this.width, this.height);

        // Road
        ctx.fillStyle = '#34495e';
        ctx.fillRect(40, 0, this.width - 80, this.height);

        // Road edges
        ctx.fillStyle = '#2c3e50';
        ctx.fillRect(40, 0, 10, this.height);
        ctx.fillRect(this.width - 50, 0, 10, this.height);

        // Lane markings
        ctx.strokeStyle = 'rgba(255,255,255,0.6)';
        ctx.lineWidth = 3;
        ctx.setLineDash([20, 20]);
        ctx.lineDashOffset = -this.roadOffset;
        ctx.beginPath();
        ctx.moveTo(this.width / 2, 0);
        ctx.lineTo(this.width / 2, this.height);
        ctx.stroke();
        ctx.setLineDash([]);

        // Road side lines
        ctx.strokeStyle = 'rgba(255,255,255,0.3)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(50, 0);
        ctx.lineTo(50, this.height);
        ctx.moveTo(this.width - 50, 0);
        ctx.lineTo(this.width - 50, this.height);
        ctx.stroke();

        // Grass details
        for (let i = 0; i < 10; i++) {
            const x = (i % 2 === 0) ? 10 : this.width - 30;
            const y = (i * 70 + this.roadOffset * 1.5) % this.height;
            ctx.fillStyle = '#4a8a5a';
            ctx.fillRect(x, y, 15, 30);
        }

        // Draw coins
        for (const coin of this.coins) {
            const x = coin.x + coin.size / 2;
            const y = coin.y + coin.size / 2;
            const s = coin.size;
            ctx.save();
            ctx.translate(x, y);
            ctx.rotate(coin.angle);
            // Glow
            const glow = ctx.createRadialGradient(0, 0, 0, 0, 0, s);
            glow.addColorStop(0, 'rgba(241, 196, 15, 0.3)');
            glow.addColorStop(1, 'rgba(241, 196, 15, 0)');
            ctx.fillStyle = glow;
            ctx.arc(0, 0, s * 2, 0, Math.PI * 2);
            ctx.fill();
            // Coin body
            ctx.beginPath();
            ctx.arc(0, 0, s * 0.7, 0, Math.PI * 2);
            ctx.fillStyle = '#f1c40f';
            ctx.fill();
            ctx.strokeStyle = '#d4ac0d';
            ctx.lineWidth = 2;
            ctx.stroke();
            ctx.fillStyle = '#d4ac0d';
            ctx.font = `${s * 0.7}px sans-serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText('★', 0, 1);
            ctx.restore();
        }

        // Draw obstacles
        for (const obs of this.obstacles) {
            ctx.fillStyle = obs.color;
            ctx.shadowColor = 'rgba(0,0,0,0.3)';
            ctx.shadowBlur = 10;
            const radius = 5;
            ctx.beginPath();
            ctx.roundRect(obs.x, obs.y, obs.width, obs.height, radius);
            ctx.fill();
            ctx.shadowBlur = 0;
            ctx.fillStyle = 'rgba(0,0,0,0.3)';
            ctx.fillRect(obs.x + 5, obs.y + 5, obs.width - 10, obs.height / 3);
            ctx.fillStyle = '#f1c40f';
            ctx.fillRect(obs.x + 5, obs.y + obs.height - 8, 8, 5);
            ctx.fillRect(obs.x + obs.width - 13, obs.y + obs.height - 8, 8, 5);
        }

        // Draw powerups
        for (const powerup of this.powerups) {
            const pulse = Math.sin(this.gameTime * 0.05) * 0.2 + 0.8;
            ctx.save();
            ctx.translate(powerup.x + 15, powerup.y + 15);
            ctx.scale(pulse, pulse);
            const glow = ctx.createRadialGradient(0, 0, 0, 0, 0, 25);
            glow.addColorStop(0, powerup.color + '40');
            glow.addColorStop(1, powerup.color + '00');
            ctx.fillStyle = glow;
            ctx.arc(0, 0, 25, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = powerup.color;
            ctx.shadowColor = powerup.color + '80';
            ctx.shadowBlur = 20;
            ctx.beginPath();
            ctx.arc(0, 0, 15, 0, Math.PI * 2);
            ctx.fill();
            ctx.shadowBlur = 0;
            ctx.fillStyle = 'white';
            ctx.font = '18px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(powerup.type === 'shield' ? '🛡️' : '⚡', 0, 1);
            ctx.restore();
        }

        // Draw particles
        for (const p of this.particles) {
            ctx.globalAlpha = p.life;
            ctx.fillStyle = p.color;
            ctx.shadowBlur = 10;
            ctx.shadowColor = p.color;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.size * p.life, 0, Math.PI * 2);
            ctx.fill();
            ctx.shadowBlur = 0;
            ctx.globalAlpha = 1;
        }

        // Draw player - Improved Bike
        const p = this.player;
        ctx.save();
        ctx.translate(p.x + p.width / 2, p.y + p.height / 2);
        
        // Shadow
        ctx.shadowColor = 'rgba(0,0,0,0.5)';
        ctx.shadowBlur = 20;
        ctx.shadowOffsetY = 5;
        
        // Bike body - Main frame
        ctx.shadowColor = 'rgba(0,0,0,0.3)';
        ctx.shadowBlur = 15;
        
        // Body
        const bodyGrad = ctx.createLinearGradient(-p.width/2, -p.height/2, p.width/2, p.height/2);
        bodyGrad.addColorStop(0, '#ff6b6b');
        bodyGrad.addColorStop(0.5, '#e74c3c');
        bodyGrad.addColorStop(1, '#c0392b');
        ctx.fillStyle = bodyGrad;
        ctx.beginPath();
        ctx.roundRect(-p.width/2, -p.height/2, p.width, p.height, 8);
        ctx.fill();
        
        // Tank/Seat area
        ctx.shadowBlur = 0;
        ctx.fillStyle = 'rgba(0,0,0,0.2)';
        ctx.beginPath();
        ctx.roundRect(-12, -p.height/2 + 5, 24, 30, 4);
        ctx.fill();
        
        // Windshield
        ctx.fillStyle = 'rgba(135, 206, 250, 0.4)';
        ctx.shadowBlur = 5;
        ctx.shadowColor = 'rgba(135, 206, 250, 0.2)';
        ctx.beginPath();
        ctx.roundRect(-10, -p.height/2 + 8, 20, 14, 3);
        ctx.fill();
        ctx.shadowBlur = 0;
        
        // Headlight
        const headlightGrad = ctx.createRadialGradient(0, -p.height/2 - 2, 2, 0, -p.height/2 - 2, 10);
        headlightGrad.addColorStop(0, '#fff9c4');
        headlightGrad.addColorStop(0.3, '#f1c40f');
        headlightGrad.addColorStop(1, 'rgba(241, 196, 15, 0)');
        ctx.fillStyle = headlightGrad;
        ctx.shadowColor = '#f1c40f';
        ctx.shadowBlur = 25;
        ctx.beginPath();
        ctx.arc(0, -p.height/2 - 2, 10, 0, Math.PI * 2);
        ctx.fill();
        ctx.shadowBlur = 0;
        
        // Handle bars
        ctx.strokeStyle = '#2c3e50';
        ctx.lineWidth = 4;
        ctx.lineCap = 'round';
        ctx.beginPath();
        ctx.moveTo(-14, -p.height/2 + 12);
        ctx.lineTo(-20, -p.height/2 - 6);
        ctx.moveTo(14, -p.height/2 + 12);
        ctx.lineTo(20, -p.height/2 - 6);
        ctx.stroke();
        
        // Handle grips
        ctx.fillStyle = '#2c3e50';
        ctx.shadowBlur = 5;
        ctx.shadowColor = 'rgba(0,0,0,0.3)';
        ctx.beginPath();
        ctx.arc(-20, -p.height/2 - 6, 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.beginPath();
        ctx.arc(20, -p.height/2 - 6, 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.shadowBlur = 0;
        
        // Exhaust pipe
        ctx.fillStyle = '#7f8c8d';
        ctx.shadowBlur = 5;
        ctx.shadowColor = 'rgba(0,0,0,0.3)';
        ctx.fillRect(-6, p.height/2 - 12, 12, 8);
        ctx.shadowBlur = 0;
        
        // Wheels
        ctx.shadowBlur = 10;
        ctx.shadowColor = 'rgba(0,0,0,0.4)';
        ctx.shadowOffsetY = 3;
        
        // Front wheel
        ctx.fillStyle = '#2c3e50';
        ctx.beginPath();
        ctx.arc(-14, p.height/2 - 8, 10, 0, Math.PI * 2);
        ctx.fill();
        ctx.beginPath();
        ctx.arc(14, p.height/2 - 8, 10, 0, Math.PI * 2);
        ctx.fill();
        
        // Rear wheel (slightly larger)
        ctx.beginPath();
        ctx.arc(-14, p.height/2 - 4, 11, 0, Math.PI * 2);
        ctx.fill();
        ctx.beginPath();
        ctx.arc(14, p.height/2 - 4, 11, 0, Math.PI * 2);
        ctx.fill();
        
        ctx.shadowBlur = 0;
        ctx.shadowOffsetY = 0;
        
        // Wheel rims
        ctx.strokeStyle = '#7f8c8d';
        ctx.lineWidth = 1.5;
        for (let i = 0; i < 4; i++) {
            const angle = (i / 4) * Math.PI * 2 + this.gameTime * 0.03;
            ctx.beginPath();
            ctx.moveTo(-14, p.height/2 - 8);
            ctx.lineTo(-14 + Math.cos(angle) * 8, p.height/2 - 8 + Math.sin(angle) * 8);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(14, p.height/2 - 8);
            ctx.lineTo(14 + Math.cos(angle) * 8, p.height/2 - 8 + Math.sin(angle) * 8);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(-14, p.height/2 - 4);
            ctx.lineTo(-14 + Math.cos(angle + 0.5) * 9, p.height/2 - 4 + Math.sin(angle + 0.5) * 9);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(14, p.height/2 - 4);
            ctx.lineTo(14 + Math.cos(angle + 0.5) * 9, p.height/2 - 4 + Math.sin(angle + 0.5) * 9);
            ctx.stroke();
        }
        
        // Tail light
        ctx.fillStyle = '#ff0000';
        ctx.shadowColor = '#ff0000';
        ctx.shadowBlur = 10;
        ctx.fillRect(-6, p.height/2 - 4, 4, 3);
        ctx.fillRect(2, p.height/2 - 4, 4, 3);
        ctx.shadowBlur = 0;
        
        ctx.restore();

        // Speed lines
        if (this.speed > 2) {
            ctx.globalAlpha = Math.min((this.speed - 2) / 3, 0.5);
            ctx.strokeStyle = 'white';
            ctx.lineWidth = 1;
            for (let i = 0; i < 10; i++) {
                const x = Math.random() * this.width;
                const y = Math.random() * this.height;
                const len = 20 + Math.random() * 30;
                ctx.beginPath();
                ctx.moveTo(x, y);
                ctx.lineTo(x, y + len);
                ctx.stroke();
            }
            ctx.globalAlpha = 1;
        }
    }

    // ============================================================
    // GAME LOOP
    // ============================================================

    gameLoop() {
        this.update();
        this.render();
        requestAnimationFrame(() => this.gameLoop());
    }
}

// ============================================================
// POLYFILL: roundRect
// ============================================================

if (!CanvasRenderingContext2D.prototype.roundRect) {
    CanvasRenderingContext2D.prototype.roundRect = function(x, y, w, h, radii) {
        const r = typeof radii === 'number' ? radii : (radii || 0);
        this.moveTo(x + r, y);
        this.lineTo(x + w - r, y);
        this.quadraticCurveTo(x + w, y, x + w, y + r);
        this.lineTo(x + w, y + h - r);
        this.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
        this.lineTo(x + r, y + h);
        this.quadraticCurveTo(x, y + h, x, y + h - r);
        this.lineTo(x, y + r);
        this.quadraticCurveTo(x, y, x + r, y);
        return this;
    };
}

// ============================================================
// START THE GAME
// ============================================================

window.addEventListener('load', () => {
    const game = new BikeRacer();
    window.game = game;
});
</script>

</body>
</html>
@include('partials.tool-content')

@endsection