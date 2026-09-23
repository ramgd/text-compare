@extends('layouts.app')

@section('content')
<style>
/* ============================================
   MIND MAP GENERATOR - COMPLETE STYLES
   ============================================ */

/* -------- ROOT VARIABLES -------- */
:root {
    --primary: #6C63FF;
    --primary-dark: #5a52d5;
    --primary-light: #e8e6ff;
    --success: #28a745;
    --danger: #dc3545;
    --gray: #6c757d;
    --gray-bg: #f8f9fa;
    --gray-border: #e0e0e0;
    --text: #1a1a2e;
    --text-light: #6c757d;
    --shadow: 0 4px 20px rgba(0,0,0,0.08);
    --radius: 12px;
    --radius-sm: 8px;
    --transition: all 0.3s ease;
}

/* -------- WRAPPER -------- */
.mindmap-wrapper {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
    min-height: calc(100vh - 200px);
}

.mindmap-container {
    background: #ffffff;
    border-radius: var(--radius);
    padding: 24px;
    box-shadow: var(--shadow);
}

/* -------- HEADER -------- */
.mindmap-header {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f0f0f0;
}

.mindmap-header h1 {
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    margin: 0;
}

.mindmap-header .subtitle {
    color: var(--text-light);
    font-size: 15px;
    margin: 4px 0 0 0;
}

/* -------- LAYOUT -------- */
.mindmap-layout {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 24px;
    min-height: 600px;
}

/* -------- LEFT PANEL -------- */
.mindmap-left {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.input-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.input-section label {
    font-weight: 600;
    color: var(--text);
    font-size: 14px;
}

.input-section textarea {
    width: 100%;
    min-height: 280px;
    padding: 14px;
    border: 2px solid var(--gray-border);
    border-radius: var(--radius);
    resize: vertical;
    font-size: 14px;
    font-family: 'Courier New', monospace;
    background: #fafafa;
    color: var(--text);
    line-height: 1.7;
    transition: var(--transition);
}

.input-section textarea:focus {
    border-color: var(--primary);
    outline: none;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
}

.input-section textarea::placeholder {
    color: #bbb;
}

/* -------- CONTROLS -------- */
.mindmap-controls {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.mindmap-controls button {
    padding: 10px 20px;
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    font-weight: 500;
    font-size: 14px;
    transition: var(--transition);
    flex: 1;
    min-width: 80px;
}

.btn-generate {
    background: var(--primary);
    color: white;
}

.btn-generate:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
}

.btn-download {
    background: var(--success);
    color: white;
}

.btn-download:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-clear {
    background: var(--danger);
    color: white;
}

.btn-clear:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* -------- QUICK EXAMPLES -------- */
.quick-examples {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
    padding: 12px;
    background: var(--gray-bg);
    border-radius: var(--radius-sm);
}

.quick-examples label {
    font-weight: 500;
    color: var(--text);
    font-size: 13px;
    margin: 0;
}

.quick-examples button {
    padding: 4px 14px;
    border: 1px solid var(--gray-border);
    border-radius: 6px;
    background: #ffffff;
    cursor: pointer;
    font-size: 12px;
    color: var(--text-light);
    transition: var(--transition);
}

.quick-examples button:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: var(--primary-light);
}

/* -------- STYLE CONTROLS -------- */
.style-controls {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 14px;
    background: var(--gray-bg);
    border-radius: var(--radius-sm);
}

.style-controls label {
    font-weight: 500;
    color: var(--text);
    font-size: 13px;
    margin: 0;
}

.style-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    align-items: center;
}

.style-row span {
    font-size: 13px;
    color: var(--text-light);
    margin-right: 4px;
}

.theme-btn {
    padding: 4px 12px;
    border: 2px solid var(--gray-border);
    border-radius: 6px;
    background: #ffffff;
    cursor: pointer;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-light);
    transition: var(--transition);
}

.theme-btn:hover {
    border-color: var(--primary);
    color: var(--primary);
}

.theme-btn.active {
    border-color: var(--primary);
    background: var(--primary-light);
    color: var(--primary);
}

/* -------- RIGHT PANEL -------- */
.mindmap-right {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.mindmap-canvas-wrapper {
    position: relative;
    background: #fafafa;
    border: 2px solid var(--gray-border);
    border-radius: var(--radius);
    overflow: hidden;
    flex: 1;
    min-height: 500px;
}

#mindmapCanvas {
    width: 100%;
    height: 100%;
    display: block;
    cursor: grab;
}

#mindmapCanvas:active {
    cursor: grabbing;
}

/* -------- PLACEHOLDER -------- */
.mindmap-placeholder {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #bbb;
    pointer-events: none;
}

.mindmap-placeholder span {
    font-size: 48px;
    display: block;
}

.mindmap-placeholder p {
    margin: 12px 0 0 0;
    font-size: 16px;
    font-weight: 500;
}

.mindmap-placeholder small {
    font-size: 13px;
    color: #ccc;
}

/* -------- FOOTER -------- */
.mindmap-footer {
    display: flex;
    justify-content: space-between;
    padding: 8px 4px;
    font-size: 13px;
    color: var(--text-light);
}

/* -------- TOAST -------- */
.toast {
    visibility: hidden;
    min-width: 280px;
    background: #333;
    color: #fff;
    text-align: center;
    border-radius: var(--radius-sm);
    padding: 14px 20px;
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%) translateY(20px);
    z-index: 9999;
    opacity: 0;
    transition: all 0.4s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    font-weight: 500;
    font-size: 14px;
}

.toast.show {
    visibility: visible;
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.toast.success {
    background: var(--success);
}
.toast.error {
    background: var(--danger);
}
.toast.info {
    background: var(--primary);
}

/* -------- RESPONSIVE -------- */
@media (max-width: 1024px) {
    .mindmap-layout {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .mindmap-left {
        order: 2;
    }
    
    .mindmap-right {
        order: 1;
        min-height: 450px;
    }
    
    .mindmap-canvas-wrapper {
        min-height: 400px;
    }
}

@media (max-width: 768px) {
    .mindmap-wrapper {
        padding: 12px;
    }
    
    .mindmap-container {
        padding: 16px;
    }
    
    .mindmap-header h1 {
        font-size: 22px;
    }
    
    .mindmap-header .subtitle {
        font-size: 13px;
    }
    
    .input-section textarea {
        min-height: 200px;
        font-size: 13px;
    }
    
    .mindmap-controls {
        flex-direction: column;
    }
    
    .mindmap-controls button {
        width: 100%;
    }
    
    .quick-examples {
        flex-direction: column;
        align-items: stretch;
    }
    
    .quick-examples button {
        width: 100%;
        text-align: center;
    }
    
    .style-row {
        flex-wrap: wrap;
    }
    
    .theme-btn {
        flex: 1;
        min-width: 60px;
        text-align: center;
    }
    
    .mindmap-canvas-wrapper {
        min-height: 300px;
    }
    
    .mindmap-footer {
        flex-direction: column;
        align-items: center;
        gap: 4px;
        font-size: 12px;
    }
    
    .toast {
        min-width: 200px;
        font-size: 13px;
        padding: 12px 16px;
        left: 16px;
        right: 16px;
        transform: translateX(0) translateY(20px);
        width: auto;
    }
    
    .toast.show {
        transform: translateX(0) translateY(0);
    }
}

@media (max-width: 480px) {
    .mindmap-header h1 {
        font-size: 19px;
    }
    
    .mindmap-canvas-wrapper {
        min-height: 250px;
    }
    
    .input-section textarea {
        min-height: 150px;
        font-size: 12px;
    }
}
</style>

<div class="mindmap-wrapper">
    <div class="mindmap-container">
        <div class="mindmap-header">
            <h1>🧠 Mind Map Generator</h1>
            <p class="subtitle">Create beautiful mind maps from your ideas and notes</p>
        </div>

        <div class="mindmap-layout">
            <!-- Left Panel - Input -->
            <div class="mindmap-left">
                <div class="input-section">
                    <label>📝 Enter Your Ideas</label>
                    <textarea id="mindmapInput" placeholder="Enter your ideas here...&#10;Use indentation for hierarchy:&#10;Main Topic&#10;  Subtopic 1&#10;    Idea 1.1&#10;    Idea 1.2&#10;  Subtopic 2&#10;    Idea 2.1"></textarea>
                    
                    <div class="mindmap-controls">
                        <button onclick="generateMindMap()" class="btn-generate">🔄 Generate</button>
                        <button onclick="downloadMindMap()" class="btn-download">💾 Download PNG</button>
                        <button onclick="clearMindMap()" class="btn-clear">🗑️ Clear</button>
                    </div>

                    <div class="quick-examples">
                        <label>Quick Examples:</label>
                        <button onclick="loadExample('project')">📋 Project Plan</button>
                        <button onclick="loadExample('study')">📚 Study Notes</button>
                        <button onclick="loadExample('business')">💼 Business</button>
                        <button onclick="loadExample('brainstorm')">💡 Brainstorm</button>
                    </div>

                    <div class="style-controls">
                        <label>🎨 Style Settings</label>
                        <div class="style-row">
                            <span>Theme:</span>
                            <button onclick="setTheme('colorful')" class="theme-btn active" data-theme="colorful">🌈 Colorful</button>
                            <button onclick="setTheme('professional')" class="theme-btn" data-theme="professional">💼 Professional</button>
                            <button onclick="setTheme('dark')" class="theme-btn" data-theme="dark">🌙 Dark</button>
                            <button onclick="setTheme('pastel')" class="theme-btn" data-theme="pastel">🌸 Pastel</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Mind Map -->
            <div class="mindmap-right">
                <div class="mindmap-canvas-wrapper">
                    <canvas id="mindmapCanvas" width="800" height="600"></canvas>
                    <div class="mindmap-placeholder" id="mindmapPlaceholder">
                        <span>🧠</span>
                        <p>Enter your ideas and click Generate</p>
                        <small>Use indentation to create hierarchy</small>
                    </div>
                </div>
                <div class="mindmap-footer">
                    <span>💡 Tip: Use tabs or spaces for indentation</span>
                    <span>🔄 Drag to scroll | Zoom: <span id="zoomLevel">100%</span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script>
// ============================================
// MIND MAP GENERATOR - COMPLETE JS
// ============================================

// ==================== STATE ====================
var currentTheme = 'colorful';
var nodes = [];
var zoomLevel = 1;
var offsetX = 0;
var offsetY = 0;
var isDragging = false;
var dragStartX = 0;
var dragStartY = 0;
var dragOffsetX = 0;
var dragOffsetY = 0;

// ==================== THEMES ====================
var themes = {
    colorful: {
        background: '#fafafa',
        colors: ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#FF8A5C', '#6C5CE7'],
        textColor: '#1a1a2e',
        borderColor: '#e0e0e0',
        nodeBg: '#ffffff',
        nodeShadow: 'rgba(0,0,0,0.1)'
    },
    professional: {
        background: '#f5f7fa',
        colors: ['#2C3E50', '#3498DB', '#2ECC71', '#E74C3C', '#F39C12', '#9B59B6', '#1ABC9C', '#34495E'],
        textColor: '#2C3E50',
        borderColor: '#bdc3c7',
        nodeBg: '#ffffff',
        nodeShadow: 'rgba(44,62,80,0.1)'
    },
    dark: {
        background: '#1a1a2e',
        colors: ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#FF8A5C', '#6C5CE7'],
        textColor: '#ffffff',
        borderColor: '#2d2d44',
        nodeBg: '#2d2d44',
        nodeShadow: 'rgba(0,0,0,0.3)'
    },
    pastel: {
        background: '#fdf6f0',
        colors: ['#FFB5B5', '#B5E6C3', '#A8D8EA', '#FFD3B4', '#D4A5FF', '#FFB7B2', '#B5D8EB', '#F5B5D8'],
        textColor: '#4a4a4a',
        borderColor: '#f0e6d8',
        nodeBg: '#ffffff',
        nodeShadow: 'rgba(0,0,0,0.05)'
    }
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    setupCanvas();
    loadExample('project');
});

// ==================== CANVAS SETUP ====================
function setupCanvas() {
    var canvas = document.getElementById('mindmapCanvas');
    var container = canvas.parentElement;
    
    function resizeCanvas() {
        var rect = container.getBoundingClientRect();
        canvas.width = rect.width || 800;
        canvas.height = rect.height || 500;
        renderMindMap();
    }
    
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    canvas.addEventListener('mousedown', function(e) {
        isDragging = true;
        dragStartX = e.clientX;
        dragStartY = e.clientY;
        dragOffsetX = offsetX;
        dragOffsetY = offsetY;
        canvas.style.cursor = 'grabbing';
    });
    
    canvas.addEventListener('mousemove', function(e) {
        if (isDragging) {
            offsetX = dragOffsetX + (e.clientX - dragStartX);
            offsetY = dragOffsetY + (e.clientY - dragStartY);
            renderMindMap();
        }
    });
    
    canvas.addEventListener('mouseup', function() {
        isDragging = false;
        canvas.style.cursor = 'grab';
    });
    
    canvas.addEventListener('mouseleave', function() {
        isDragging = false;
        canvas.style.cursor = 'grab';
    });
    
    canvas.addEventListener('wheel', function(e) {
        e.preventDefault();
        var delta = e.deltaY > 0 ? -0.1 : 0.1;
        zoomLevel = Math.max(0.5, Math.min(2, zoomLevel + delta));
        document.getElementById('zoomLevel').textContent = Math.round(zoomLevel * 100) + '%';
        renderMindMap();
    });
}

// ==================== GENERATE MIND MAP ====================
function generateMindMap() {
    var input = document.getElementById('mindmapInput').value;
    if (!input.trim()) {
        showToast('Please enter some ideas first', 'error');
        return;
    }
    
    nodes = parseInput(input);
    if (nodes.length === 0) {
        showToast('Please enter valid ideas with hierarchy', 'error');
        return;
    }
    
    document.getElementById('mindmapPlaceholder').style.display = 'none';
    renderMindMap();
    showToast('Mind map generated successfully!', 'success');
}

// ==================== PARSE INPUT ====================
function parseInput(input) {
    var lines = input.split('\n');
    var nodes = [];
    var stack = [];
    var id = 0;
    
    lines.forEach(function(line) {
        var trimmed = line.trim();
        if (!trimmed) return;
        
        var indent = line.search(/\S|$/);
        var level = Math.floor(indent / 2);
        
        var node = {
            id: id++,
            text: trimmed,
            level: level,
            children: []
        };
        
        while (stack.length > level) {
            stack.pop();
        }
        
        if (stack.length === 0) {
            nodes.push(node);
        } else {
            var parent = stack[stack.length - 1];
            parent.children.push(node);
        }
        
        stack.push(node);
    });
    
    return nodes;
}

// ==================== RENDER MIND MAP ====================
function renderMindMap() {
    var canvas = document.getElementById('mindmapCanvas');
    var ctx = canvas.getContext('2d');
    var theme = themes[currentTheme];
    
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    ctx.fillStyle = theme.background;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    if (nodes.length === 0) {
        document.getElementById('mindmapPlaceholder').style.display = 'flex';
        return;
    }
    
    document.getElementById('mindmapPlaceholder').style.display = 'none';
    
    ctx.save();
    ctx.translate(offsetX, offsetY);
    ctx.scale(zoomLevel, zoomLevel);
    
    var centerX = canvas.width / 2 / zoomLevel;
    var centerY = canvas.height / 2 / zoomLevel;
    
    if (nodes.length === 1 && nodes[0].children.length > 0) {
        var mainNode = nodes[0];
        var mainRadius = drawNode(ctx, mainNode.text, centerX, centerY, theme.colors[0], true);
        
        var childCount = mainNode.children.length;
        var radius = Math.min(200, 100 + childCount * 20);
        var angleStep = (2 * Math.PI) / childCount;
        
        mainNode.children.forEach(function(child, index) {
            var angle = index * angleStep - Math.PI / 2;
            var x = centerX + radius * Math.cos(angle);
            var y = centerY + radius * Math.sin(angle);
            
            ctx.beginPath();
            ctx.moveTo(centerX + mainRadius, centerY);
            ctx.lineTo(x, y);
            ctx.strokeStyle = theme.colors[1];
            ctx.lineWidth = 2;
            ctx.stroke();
            
            var childRadius = drawNode(ctx, child.text, x, y, theme.colors[(index + 1) % theme.colors.length], false);
            
            if (child.children.length > 0) {
                drawChildren(ctx, child, x, y + childRadius + 20, theme.colors, (index + 2) % theme.colors.length);
            }
        });
    } else {
        var totalNodes = nodes.length;
        var spacing = Math.min(180, (canvas.width / zoomLevel - 100) / totalNodes);
        var startX = (canvas.width / zoomLevel - (totalNodes - 1) * spacing) / 2;
        
        nodes.forEach(function(node, index) {
            var x = startX + index * spacing;
            var y = 80;
            var colorIndex = index % theme.colors.length;
            
            var nodeRadius = drawNode(ctx, node.text, x, y, theme.colors[colorIndex], true);
            
            if (node.children.length > 0) {
                drawChildren(ctx, node, x, y + nodeRadius + 30, theme.colors, (colorIndex + 1) % theme.colors.length);
            }
        });
    }
    
    ctx.restore();
}

function drawChildren(ctx, parent, parentX, startY, colors, colorIndex) {
    var childCount = parent.children.length;
    var spacing = Math.min(120, (500 / childCount));
    var totalWidth = (childCount - 1) * spacing;
    var startX = parentX - totalWidth / 2;
    
    parent.children.forEach(function(child, index) {
        var x = startX + index * spacing;
        var y = startY + 20;
        
        ctx.beginPath();
        ctx.moveTo(parentX, startY - 10);
        ctx.lineTo(x, y);
        ctx.strokeStyle = colors[colorIndex % colors.length];
        ctx.lineWidth = 1.5;
        ctx.stroke();
        
        var childRadius = drawNode(ctx, child.text, x, y, colors[(colorIndex + index) % colors.length], false);
        
        if (child.children.length > 0) {
            drawChildren(ctx, child, x, y + childRadius + 20, colors, (colorIndex + index + 1) % colors.length);
        }
    });
}

function drawNode(ctx, text, x, y, color, isMain) {
    var theme = themes[currentTheme];
    var fontSize = isMain ? 16 : 13;
    var padding = isMain ? 20 : 14;
    
    ctx.font = fontSize + 'px Arial, sans-serif';
    var metrics = ctx.measureText(text);
    var textWidth = metrics.width;
    var textHeight = fontSize;
    
    var nodeWidth = Math.max(textWidth + padding * 2, 40);
    var nodeHeight = Math.max(textHeight + padding, 30);
    
    var cornerRadius = isMain ? 20 : 12;
    var x1 = x - nodeWidth / 2;
    var y1 = y - nodeHeight / 2;
    
    ctx.shadowColor = theme.nodeShadow;
    ctx.shadowBlur = isMain ? 15 : 8;
    ctx.shadowOffsetX = 2;
    ctx.shadowOffsetY = 3;
    
    ctx.beginPath();
    ctx.moveTo(x1 + cornerRadius, y1);
    ctx.lineTo(x1 + nodeWidth - cornerRadius, y1);
    ctx.quadraticCurveTo(x1 + nodeWidth, y1, x1 + nodeWidth, y1 + cornerRadius);
    ctx.lineTo(x1 + nodeWidth, y1 + nodeHeight - cornerRadius);
    ctx.quadraticCurveTo(x1 + nodeWidth, y1 + nodeHeight, x1 + nodeWidth - cornerRadius, y1 + nodeHeight);
    ctx.lineTo(x1 + cornerRadius, y1 + nodeHeight);
    ctx.quadraticCurveTo(x1, y1 + nodeHeight, x1, y1 + nodeHeight - cornerRadius);
    ctx.lineTo(x1, y1 + cornerRadius);
    ctx.quadraticCurveTo(x1, y1, x1 + cornerRadius, y1);
    ctx.closePath();
    
    ctx.fillStyle = color;
    ctx.fill();
    
    ctx.shadowBlur = 0;
    ctx.strokeStyle = theme.borderColor;
    ctx.lineWidth = isMain ? 3 : 1;
    ctx.stroke();
    
    ctx.shadowBlur = 0;
    ctx.fillStyle = theme.textColor;
    ctx.font = (isMain ? 'bold ' : '') + fontSize + 'px Arial, sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(text, x, y + 1);
    
    return nodeHeight / 2;
}

// ==================== THEME FUNCTIONS ====================
function setTheme(theme) {
    currentTheme = theme;
    document.querySelectorAll('.theme-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    document.querySelector('.theme-btn[data-theme="' + theme + '"]').classList.add('active');
    renderMindMap();
    showToast('Theme changed to ' + theme.charAt(0).toUpperCase() + theme.slice(1), 'info');
}

// ==================== DOWNLOAD FUNCTIONS ====================
function downloadMindMap() {
    var canvas = document.getElementById('mindmapCanvas');
    var link = document.createElement('a');
    link.download = 'mindmap.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
    showToast('Mind map downloaded!', 'success');
}

// ==================== CLEAR FUNCTIONS ====================
function clearMindMap() {
    document.getElementById('mindmapInput').value = '';
    nodes = [];
    document.getElementById('mindmapPlaceholder').style.display = 'flex';
    renderMindMap();
    showToast('Cleared', 'info');
}

// ==================== EXAMPLES ====================
function loadExample(type) {
    var examples = {
        project: `📋 Project Plan
  🎯 Goals
    Launch new product
    Increase user engagement
    Improve customer satisfaction
  📊 Tasks
    Market research
    Product development
      Design UI/UX
      Backend development
      Testing
    Marketing campaign
  👥 Team
    Product Manager
    Developer Team
    Design Team
    Marketing Team
  ⏰ Timeline
    Q1 Planning
    Q2 Development
    Q3 Testing
    Q4 Launch`,

        study: `📚 Study Notes
  🧪 Biology
    Cell Structure
      Nucleus
      Mitochondria
      Ribosomes
    Genetics
      DNA
      RNA
      Mutations
  🔬 Chemistry
    Organic Chemistry
    Inorganic Chemistry
    Periodic Table
  📐 Mathematics
    Algebra
    Geometry
    Calculus
  🖥️ Computer Science
    Programming
    Data Structures
    Algorithms`,

        business: `💼 Business Plan
  📈 Marketing
    Social Media
      Instagram
      Facebook
      Twitter
    Email Campaigns
    SEO Strategy
  💰 Finance
    Revenue Streams
    Expenses
    Profit Margins
  👥 Operations
    Team Structure
    Processes
    Tools
  🎯 Strategy
    Short-term Goals
    Long-term Vision
    Growth Plan`,

        brainstorm: `💡 Brainstorm Ideas
  🌟 Innovation
    AI Integration
    Automation
    Blockchain
  🎨 Creative
    Design Thinking
    User Experience
    Brand Identity
  🌍 Social Impact
    Sustainability
    Community
    Education
  🚀 Growth
    Partnerships
    Expansion
    New Markets`
    };
    
    var text = examples[type];
    if (!text) return;
    
    document.getElementById('mindmapInput').value = text;
    generateMindMap();
    showToast('Example loaded: ' + type.charAt(0).toUpperCase() + type.slice(1), 'success');
}

// ==================== TOAST SYSTEM ====================
function showToast(message, type) {
    if (type === undefined) type = 'info';
    var toast = document.getElementById('toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toast';
        toast.className = 'toast';
        document.body.appendChild(toast);
    }
    
    toast.textContent = message;
    toast.className = 'toast show ' + type;
    
    clearTimeout(toast._timeout);
    toast._timeout = setTimeout(function() {
        toast.className = 'toast';
    }, 3000);
}

console.log('Mind Map Generator loaded successfully!');
</script>

@include('partials.tool-content')

@endsection