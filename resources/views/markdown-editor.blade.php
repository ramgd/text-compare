@extends('layouts.app')

@section('content')
<div class="markdown-wrapper">
    <div class="markdown-container">
        <div class="markdown-header">
            <h1>📝 Markdown Editor</h1>
            <p class="subtitle">Write and preview markdown in real-time with live rendering</p>
        </div>

        <div class="editor-toolbar">
            <div class="toolbar-group">
                <button onclick="insertFormat('bold')" title="Bold"><b>B</b></button>
                <button onclick="insertFormat('italic')" title="Italic"><i>I</i></button>
                <button onclick="insertFormat('heading')" title="Heading">H</button>
                <button onclick="insertFormat('link')" title="Link">🔗</button>
                <button onclick="insertFormat('image')" title="Image">🖼️</button>
                <button onclick="insertFormat('code')" title="Code">{ }</button>
                <button onclick="insertFormat('codeblock')" title="Code Block">{📦}</button>
                <button onclick="insertFormat('list')" title="List">📋</button>
                <button onclick="insertFormat('quote')" title="Quote">💬</button>
                <button onclick="insertFormat('table')" title="Table">📊</button>
                <button onclick="insertFormat('hr')" title="Horizontal Line">➖</button>
            </div>
            <div class="toolbar-group">
                <button onclick="clearEditor()" class="btn-clear-editor">🗑️ Clear</button>
                <button onclick="copyMarkdown()" class="btn-copy-markdown">📋 Copy</button>
                <button onclick="downloadMarkdown()" class="btn-download-markdown">💾 Download</button>
                <button onclick="exportHTML()" class="btn-export-html">🌐 Export HTML</button>
            </div>
        </div>

        <div class="editor-view-toggle">
            <button class="view-btn active" data-view="split" onclick="setView('split')">📐 Split View</button>
            <button class="view-btn" data-view="write" onclick="setView('write')">✏️ Write</button>
            <button class="view-btn" data-view="preview" onclick="setView('preview')">👁️ Preview</button>
        </div>

        <div class="editor-container" id="editorContainer">
            <div class="editor-input" id="editorInput">
                <label>Write Markdown</label>
                <textarea id="markdownInput" oninput="renderMarkdown()" placeholder="Write your markdown here..."></textarea>
                <div class="word-count">
                    <span>Words: <span id="wordCount">0</span></span>
                    <span>Characters: <span id="charCount">0</span></span>
                    <span>Lines: <span id="lineCount">0</span></span>
                </div>
            </div>
            <div class="editor-preview" id="editorPreview">
                <label>Preview</label>
                <div id="markdownPreview" class="markdown-body"></div>
            </div>
        </div>

        <!-- Sample Templates -->
        <div class="sample-templates">
            <label>Quick Templates:</label>
            <button onclick="loadSample('readme')">📄 README</button>
            <button onclick="loadSample('blog')">📝 Blog Post</button>
            <button onclick="loadSample('cheatsheet')">📋 Cheatsheet</button>
            <button onclick="loadSample('todo')">✅ Todo List</button>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/markdown-editor.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/markdown-editor.js') }}"></script>
@endpush
