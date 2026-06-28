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
    console.log('DOM loaded, initializing mind map...');
    setupCanvas();
    loadExample('project');
});

// ==================== CANVAS SETUP ====================
function setupCanvas() {
    var canvas = document.getElementById('mindmapCanvas');
    if (!canvas) {
        console.error('Canvas not found!');
        return;
    }
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
    console.log('generateMindMap called');
    var input = document.getElementById('mindmapInput');
    if (!input) {
        console.error('Input element not found!');
        return;
    }
    var text = input.value;
    if (!text.trim()) {
        showToast('Please enter some ideas first', 'error');
        return;
    }
    
    nodes = parseInput(text);
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
    if (!canvas) return;
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

console.log('Mind Map Generator JS loaded successfully.');