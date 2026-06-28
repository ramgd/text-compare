@extends('layouts.app')

@section('content')
<div class="color-wrapper">
    <div class="color-container">
        <div class="color-header">
            <h1>🎨 Color Palette Generator</h1>
            <p class="subtitle">Create beautiful color palettes with HEX, RGB, and HSL codes</p>
        </div>

        <div class="color-controls">
            <div class="control-group">
                <label>Palette Type:</label>
                <select id="paletteType" onchange="generatePalette()">
                    <option value="monochromatic">Monochromatic</option>
                    <option value="complementary">Complementary</option>
                    <option value="triadic">Triadic</option>
                    <option value="tetradic">Tetradic</option>
                    <option value="analogous">Analogous</option>
                    <option value="random" selected>Random</option>
                </select>
            </div>
            <div class="control-group">
                <label>Number of Colors:</label>
                <select id="colorCount" onchange="generatePalette()">
                    <option value="3">3 Colors</option>
                    <option value="4" selected>4 Colors</option>
                    <option value="5">5 Colors</option>
                    <option value="6">6 Colors</option>
                    <option value="8">8 Colors</option>
                </select>
            </div>
            <div class="control-group">
                <label>Base Color:</label>
                <input type="color" id="baseColor" value="#ff7a18" onchange="generatePalette()">
            </div>
            <button onclick="generatePalette()" class="btn-generate-palette">🔄 Generate New Palette</button>
            <button onclick="randomPalette()" class="btn-random">🎲 Random Palette</button>
        </div>

        <div class="palette-grid" id="paletteGrid">
            <!-- Colors will be generated here -->
        </div>

        <div class="palette-actions">
            <button onclick="copyAllColors()" class="btn-copy-all">📋 Copy All HEX</button>
            <button onclick="exportCSS()" class="btn-export-css">🎨 Export CSS</button>
            <button onclick="exportTailwind()" class="btn-export-tailwind">💨 Export Tailwind</button>
            <button onclick="savePalette()" class="btn-save">💾 Save Palette</button>
            <button onclick="downloadPalette()" class="btn-download-palette">📥 Download PNG</button>
        </div>

        <div class="color-history" id="colorHistory" style="display:none;">
            <h3>📜 Saved Palettes</h3>
            <div id="historyList"></div>
        </div>

        <!-- Color Details Modal -->
        <div class="modal" id="colorModal" style="display:none;">
            <div class="modal-content">
                <span class="modal-close" onclick="closeModal()">&times;</span>
                <h3>Color Details</h3>
                <div id="modalColorPreview" style="width:100%; height:100px; border-radius:8px; margin:15px 0;"></div>
                <div class="modal-details">
                    <div class="detail-row">
                        <span>HEX:</span>
                        <span id="modalHex" style="font-family: monospace;"></span>
                        <button onclick="copyModalHex()" class="btn-copy-small">📋</button>
                    </div>
                    <div class="detail-row">
                        <span>RGB:</span>
                        <span id="modalRgb" style="font-family: monospace;"></span>
                        <button onclick="copyModalRgb()" class="btn-copy-small">📋</button>
                    </div>
                    <div class="detail-row">
                        <span>HSL:</span>
                        <span id="modalHsl" style="font-family: monospace;"></span>
                        <button onclick="copyModalHsl()" class="btn-copy-small">📋</button>
                    </div>
                    <div class="detail-row">
                        <span>Contrast Ratio:</span>
                        <span id="modalContrast" style="font-family: monospace;"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

