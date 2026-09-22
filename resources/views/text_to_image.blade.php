@extends('layouts.app')

@section('content')
<div class="text-to-image-wrapper">
    <div class="tti-container">
        <div class="tti-left">
            <h2>🖼️ Text To Image Generator</h2>

            <textarea id="textInput" oninput="syncActiveLayer()" placeholder="Enter your text here..."></textarea>
            <hr>

            <h3>Text Layers</h3>
            <div id="layersContainer"></div>
            <button type="button" onclick="addLayer()" class="btn-add-layer">➕ Add Layer</button>
            <br><br>

            <label>Template</label>
            <select id="canvasPreset" onchange="changePreset()">
                <option value="instagram">Instagram Post</option>
                <option value="linkedin">LinkedIn Banner</option>
                <option value="facebook">Facebook Cover</option>
                <option value="story">Instagram Story</option>
            </select>
            <br><br>

            <label>Font Family</label>
            <select id="fontFamily" onchange="updateCanvas()">
                <option value="Arial">Arial</option>
                <option value="Verdana">Verdana</option>
                <option value="Georgia">Georgia</option>
                <option value="Courier New">Courier New</option>
                <option value="Tahoma">Tahoma</option>
            </select>
            <br><br>

            <label>Font Size</label>
            <input type="range" min="20" max="100" value="50" id="fontSize" oninput="syncActiveLayer(); updateCanvas();">
            <br><br>

            <div class="color-group">
                <div class="color-item">
                    <label>Text Color</label>
                    <input type="color" id="textColor" value="#ffffff" onchange="syncActiveLayer(); updateCanvas();">
                </div>
                <div class="color-item">
                    <label>Background Color</label>
                    <input type="color" id="bgColor" value="#ff7a18" onchange="updateCanvas();">
                </div>
            </div>
            <br>

            <div class="checkbox-group">
                <label>
                    <input type="checkbox" id="boldText" onchange="syncActiveLayer(); updateCanvas();">
                    Bold
                </label>
                <label>
                    <input type="checkbox" id="italicText" onchange="syncActiveLayer(); updateCanvas();">
                    Italic
                </label>
                <label>
                    <input type="checkbox" id="shadowText" onchange="syncActiveLayer(); updateCanvas();">
                    Shadow
                </label>
                <label>
                    <input type="checkbox" id="outlineText" onchange="syncActiveLayer(); updateCanvas();">
                    Outline
                </label>
            </div>
            <br>

            <div class="file-upload-group">
                <div class="file-upload-item">
                    <label>Background Image</label>
                    <div class="file-input-wrapper">
                        <input type="file" accept="image/*" onchange="loadBackground(event)" id="bgImageInput">
                        <button onclick="removeBackground()" class="btn-remove" id="removeBgBtn" style="display:none;">✕ Remove</button>
                    </div>
                </div>
                <div class="file-upload-item">
                    <label>Watermark Logo</label>
                    <div class="file-input-wrapper">
                        <input type="file" accept="image/*" onchange="loadLogo(event)" id="logoImageInput">
                        <button onclick="removeLogo()" class="btn-remove" id="removeLogoBtn" style="display:none;">✕ Remove</button>
                    </div>
                </div>
            </div>
            <br>

            <hr>

            <h3>Move & Rotate Text</h3>
            <div class="move-controls">
                <button type="button" onclick="moveText('up')">⬆ Up</button>
                <button type="button" onclick="moveText('left')">⬅ Left</button>
                <button type="button" onclick="moveText('right')">➡ Right</button>
                <button type="button" onclick="moveText('down')">⬇ Down</button>
            </div>

            <div class="rotate-controls">
                <button type="button" onclick="rotateLayer(15)" class="btn-rotate">🔄 Rotate Right</button>
                <button type="button" onclick="rotateLayer(-15)" class="btn-rotate">🔄 Rotate Left</button>
                <button type="button" onclick="resetRotation()" class="btn-reset">↺ Reset Rotation</button>
            </div>
            <br>

            <div class="action-buttons">
                <button onclick="downloadPNG()" class="btn-download">📥 Download PNG</button>
                <button onclick="downloadJPG()" class="btn-download">📥 Download JPG</button>
                <button onclick="clearCanvas()" class="btn-clear">🗑️ Clear All</button>
            </div>
        </div>

        <div class="tti-right">
            <canvas id="imageCanvas" width="1080" height="1080"></canvas>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/text-to-image.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/text-to-image.js') }}"></script>
@endpush
