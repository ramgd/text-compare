// ============================================
// MARKDOWN EDITOR - COMPLETE JAVASCRIPT
// ============================================

// ==================== MARKDOWN RENDERER ====================

function renderMarkdown() {
    var input = document.getElementById('markdownInput');
    var preview = document.getElementById('markdownPreview');
    var text = input.value;
    
    // Update word count
    updateStats(text);
    
    // Render markdown to HTML
    preview.innerHTML = markedParse(text);
}

function markedParse(text) {
    // Convert markdown to HTML
    var html = text;
    
    // Headers
    html = html.replace(/^### (.*$)/gim, '<h3>$1</h3>');
    html = html.replace(/^## (.*$)/gim, '<h2>$1</h2>');
    html = html.replace(/^# (.*$)/gim, '<h1>$1</h1>');
    
    // Bold
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/__(.*?)__/g, '<strong>$1</strong>');
    
    // Italic
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>');
    html = html.replace(/_(.*?)_/g, '<em>$1</em>');
    
    // Strikethrough
    html = html.replace(/~~(.*?)~~/g, '<del>$1</del>');
    
    // Inline code
    html = html.replace(/`(.*?)`/g, '<code>$1</code>');
    
    // Code blocks
    html = html.replace(/```([\s\S]*?)```/g, function(match, code) {
        return '<pre><code>' + escapeHtml(code.trim()) + '</code></pre>';
    });
    
    // Blockquotes
    html = html.replace(/^> (.*$)/gim, '<blockquote>$1</blockquote>');
    
    // Unordered lists
    html = html.replace(/^- (.*$)/gim, '<li>$1</li>');
    html = html.replace(/(<li>.*<\/li>)/g, '<ul>$1</ul>');
    
    // Ordered lists
    html = html.replace(/^\d+\. (.*$)/gim, '<li>$1</li>');
    html = html.replace(/(<li>.*<\/li>)/g, '<ol>$1</ol>');
    
    // Horizontal rule
    html = html.replace(/^---$/gim, '<hr>');
    
    // Links
    html = html.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank">$1</a>');
    
    // Images
    html = html.replace(/!\[(.*?)\]\((.*?)\)/g, '<img src="$2" alt="$1">');
    
    // Paragraphs (convert double newlines to paragraphs)
    html = html.replace(/\n\n/g, '</p><p>');
    html = '<p>' + html + '</p>';
    
    // Clean up empty paragraphs
    html = html.replace(/<p><\/p>/g, '');
    html = html.replace(/<p>\s*<h/g, '<h');
    html = html.replace(/<\/h\d>\s*<\/p>/g, function(match) {
        return match.replace('</p>', '');
    });
    html = html.replace(/<p>\s*<ul/g, '<ul');
    html = html.replace(/<\/ul>\s*<\/p>/g, '</ul>');
    html = html.replace(/<p>\s*<ol/g, '<ol');
    html = html.replace(/<\/ol>\s*<\/p>/g, '</ol>');
    html = html.replace(/<p>\s*<blockquote/g, '<blockquote');
    html = html.replace(/<\/blockquote>\s*<\/p>/g, '</blockquote>');
    html = html.replace(/<p>\s*<pre/g, '<pre');
    html = html.replace(/<\/pre>\s*<\/p>/g, '</pre>');
    html = html.replace(/<p>\s*<hr/g, '<hr');
    html = html.replace(/<hr>\s*<\/p>/g, '<hr>');
    
    return html;
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ==================== STATS FUNCTIONS ====================

function updateStats(text) {
    var wordCount = text.trim() ? text.trim().split(/\s+/).length : 0;
    var charCount = text.length;
    var lineCount = text ? text.split('\n').length : 0;
    
    document.getElementById('wordCount').textContent = wordCount;
    document.getElementById('charCount').textContent = charCount;
    document.getElementById('lineCount').textContent = lineCount;
}

// ==================== FORMAT FUNCTIONS ====================

window.insertFormat = function(type) {
    var textarea = document.getElementById('markdownInput');
    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;
    var text = textarea.value;
    var selected = text.substring(start, end);
    var replacement = '';
    
    switch(type) {
        case 'bold':
            replacement = '**' + selected + '**';
            break;
        case 'italic':
            replacement = '*' + selected + '*';
            break;
        case 'heading':
            replacement = '# ' + selected;
            break;
        case 'link':
            if (selected) {
                replacement = '[' + selected + '](url)';
            } else {
                replacement = '[text](url)';
            }
            break;
        case 'image':
            replacement = '![alt text](image-url)';
            break;
        case 'code':
            replacement = '`' + selected + '`';
            break;
        case 'codeblock':
            if (selected) {
                replacement = '```\n' + selected + '\n```';
            } else {
                replacement = '```\ncode here\n```';
            }
            break;
        case 'list':
            if (selected) {
                var lines = selected.split('\n');
                replacement = lines.map(function(line) {
                    return '- ' + line;
                }).join('\n');
            } else {
                replacement = '- item';
            }
            break;
        case 'quote':
            if (selected) {
                var lines = selected.split('\n');
                replacement = lines.map(function(line) {
                    return '> ' + line;
                }).join('\n');
            } else {
                replacement = '> quote';
            }
            break;
        case 'table':
            replacement = '| Header 1 | Header 2 |\n|----------|----------|\n| Cell 1   | Cell 2   |';
            break;
        case 'hr':
            replacement = '---';
            break;
        default:
            return;
    }
    
    // Insert replacement
    textarea.value = text.substring(0, start) + replacement + text.substring(end);
    
    // Set cursor position
    var newCursor = start + replacement.length;
    textarea.selectionStart = newCursor;
    textarea.selectionEnd = newCursor;
    
    textarea.focus();
    renderMarkdown();
};

// ==================== VIEW FUNCTIONS ====================

window.setView = function(view) {
    var container = document.getElementById('editorContainer');
    var input = document.getElementById('editorInput');
    var preview = document.getElementById('editorPreview');
    
    // Update active button
    document.querySelectorAll('.view-btn').forEach(function(btn) {
        btn.classList.remove('active');
    });
    document.querySelector('.view-btn[data-view="' + view + '"]').classList.add('active');
    
    // Reset styles
    input.style.display = '';
    preview.style.display = '';
    input.style.flex = '';
    preview.style.flex = '';
    input.style.width = '';
    preview.style.width = '';
    
    switch(view) {
        case 'write':
            preview.style.display = 'none';
            input.style.flex = '1';
            break;
        case 'preview':
            input.style.display = 'none';
            preview.style.flex = '1';
            break;
        case 'split':
        default:
            // Default split view
            break;
    }
};

// ==================== SAMPLE TEMPLATES ====================

window.loadSample = function(type) {
    var templates = {
        readme: `# Project Title\n\n## Description\n\nBrief description of your project.\n\n## Installation\n\n\`\`\`bash\nnpm install\n\`\`\`\n\n## Usage\n\n\`\`\`javascript\nconst app = require('app');\napp.start();\n\`\`\`\n\n## Features\n\n- Feature 1\n- Feature 2\n- Feature 3\n\n## License\n\nMIT`,
        
        blog: `# My Blog Post\n\n## Introduction\n\nWelcome to my blog post! This is where I share my thoughts and ideas.\n\n## Main Content\n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\n\n### Key Points\n\n1. **First Point** - Explanation of first point\n2. **Second Point** - Explanation of second point\n3. **Third Point** - Explanation of third point\n\n## Conclusion\n\nThank you for reading! Feel free to [leave a comment](mailto:example@email.com).`,
        
        cheatsheet: `# Markdown Cheatsheet\n\n## Headers\n\n# H1\n## H2\n### H3\n\n## Text Formatting\n\n**Bold text**\n*Italic text*\n~~Strikethrough~~\n\`inline code\`\n\n## Lists\n\n- Unordered item 1\n- Unordered item 2\n  - Nested item\n\n1. Ordered item 1\n2. Ordered item 2\n\n## Links & Images\n\n[Link text](https://example.com)\n![Image alt](image-url.jpg)\n\n## Code Blocks\n\n\`\`\`javascript\nfunction hello() {\n  console.log('Hello World!');\n}\n\`\`\`\n\n## Blockquotes\n\n> This is a blockquote\n\n## Tables\n\n| Header 1 | Header 2 |\n|----------|----------|\n| Cell 1   | Cell 2   |`,
        
        todo: `# Todo List\n\n## Today's Tasks\n\n- [x] Complete project documentation\n- [x] Review pull requests\n- [ ] Write unit tests\n- [ ] Deploy to production\n- [ ] Update dependencies\n\n## This Week\n\n- [ ] Plan team meeting\n- [ ] Design new feature\n- [ ] Refactor legacy code\n\n## Next Month\n\n- [ ] Migrate database\n- [ ] Upgrade framework\n- [ ] Performance optimization\n\n## Notes\n\n> Remember to backup data before deployment!\n\n## Priority\n\n1. **High**: Fix security vulnerability\n2. **Medium**: Optimize queries\n3. **Low**: Update documentation`
    };
    
    var textarea = document.getElementById('markdownInput');
    textarea.value = templates[type] || '';
    renderMarkdown();
    showToast('Template loaded: ' + type, 'success');
};

// ==================== EDITOR ACTIONS ====================

window.clearEditor = function() {
    if (!confirm('Clear all content?')) return;
    document.getElementById('markdownInput').value = '';
    renderMarkdown();
    showToast('Editor cleared', 'info');
};

window.copyMarkdown = function() {
    var text = document.getElementById('markdownInput').value;
    if (!text) {
        showToast('Nothing to copy', 'error');
        return;
    }
    
    navigator.clipboard.writeText(text).then(function() {
        showToast('Markdown copied to clipboard!', 'success');
    }).catch(function() {
        fallbackCopy(text);
    });
};

window.downloadMarkdown = function() {
    var text = document.getElementById('markdownInput').value;
    if (!text) {
        showToast('No content to download', 'error');
        return;
    }
    
    var blob = new Blob([text], { type: 'text/markdown' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'document.md';
    a.click();
    URL.revokeObjectURL(url);
    showToast('Markdown downloaded!', 'success');
};

window.exportHTML = function() {
    var html = document.getElementById('markdownPreview').innerHTML;
    if (!html) {
        showToast('No content to export', 'error');
        return;
    }
    
    var title = prompt('Enter title for HTML page:', 'Markdown Document');
    if (title === null) return;
    
    var fullHtml = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${title || 'Markdown Document'}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/5.1.0/github-markdown-light.min.css">
    <style>
        body {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
        }
        .markdown-body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="markdown-body">
        ${html}
    </div>
</body>
</html>`;
    
    var blob = new Blob([fullHtml], { type: 'text/html' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = 'document.html';
    a.click();
    URL.revokeObjectURL(url);
    showToast('HTML exported!', 'success');
};

function fallbackCopy(text) {
    var textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.style.position = 'fixed';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand('copy');
        showToast('Copied to clipboard!', 'success');
    } catch (e) {
        showToast('Failed to copy', 'error');
    }
    document.body.removeChild(textarea);
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

// ==================== KEYBOARD SHORTCUTS ====================

document.addEventListener('keydown', function(e) {
    // Ctrl+B for bold
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        insertFormat('bold');
    }
    // Ctrl+I for italic
    if (e.ctrlKey && e.key === 'i') {
        e.preventDefault();
        insertFormat('italic');
    }
    // Ctrl+Shift+C for code block
    if (e.ctrlKey && e.shiftKey && e.key === 'C') {
        e.preventDefault();
        insertFormat('codeblock');
    }
});

// ==================== INITIALIZATION ====================

document.addEventListener('DOMContentLoaded', function() {
    // Load default sample
    loadSample('readme');
});