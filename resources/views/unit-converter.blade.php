@extends('layouts.app')

@section('content')
<div class="unit-wrapper">
    <div class="unit-container">
        <div class="unit-header">
            <h1>📐 Unit Converter</h1>
            <p class="subtitle">Convert between different units easily and quickly</p>
        </div>

        <div class="unit-converter">
            <div class="converter-controls">
                <div class="control-group">
                    <label>Category</label>
                    <select id="category" onchange="changeCategory()">
                        <option value="length">📏 Length</option>
                        <option value="weight">⚖️ Weight</option>
                        <option value="temperature">🌡️ Temperature</option>
                        <option value="speed">🚀 Speed</option>
                        <option value="area">📐 Area</option>
                        <option value="volume">🧪 Volume</option>
                        <option value="data">💾 Data Storage</option>
                        <option value="time">⏰ Time</option>
                    </select>
                </div>
            </div>

            <div class="converter-grid">
                <div class="input-section">
                    <label>From</label>
                    <select id="fromUnit" onchange="convert()"></select>
                    <input type="number" id="fromValue" value="1" step="any" oninput="convert()" onchange="convert()">
                </div>

                <div class="swap-section">
                    <button onclick="swapUnits()" class="btn-swap">🔄 Swap</button>
                </div>

                <div class="output-section">
                    <label>To</label>
                    <select id="toUnit" onchange="convert()"></select>
                    <input type="text" id="toValue" readonly placeholder="Result">
                </div>
            </div>

            <div class="quick-presets">
                <label>Quick Values:</label>
                <button onclick="setValue(1)">1</button>
                <button onclick="setValue(5)">5</button>
                <button onclick="setValue(10)">10</button>
                <button onclick="setValue(25)">25</button>
                <button onclick="setValue(50)">50</button>
                <button onclick="setValue(100)">100</button>
                <button onclick="setValue(1000)">1000</button>
            </div>

            <div class="result-actions">
                <button onclick="copyResult()" class="btn-copy-result">📋 Copy Result</button>
                <button onclick="saveConversion()" class="btn-save-result">💾 Save</button>
                <button onclick="clearAll()" class="btn-clear-result">🗑️ Clear</button>
            </div>
        </div>

        <!-- Conversion History -->
        <div class="conversion-history" id="historySection" style="display:none;">
            <h3>📜 Conversion History</h3>
            <div id="historyList"></div>
            <button onclick="clearHistory()" class="btn-clear-history">🗑️ Clear History</button>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/unit-converter.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/unit-converter.js') }}"></script>
@endpush
