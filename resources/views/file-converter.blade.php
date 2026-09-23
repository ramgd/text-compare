@extends('layouts.app')

@section('content')
<div class="file-converter-wrapper">
    <div class="file-converter-container">
        <div class="file-converter-header">
            <h1>📂 File Format Converter</h1>
            <p class="subtitle">Convert between CSV, JSON, XML, and YAML formats easily</p>
        </div>

        <div class="converter-tabs">
            <button class="tab-btn active" data-tab="csv-json" onclick="switchTab('csv-json')">
                <i class="fas fa-file-csv"></i> CSV ↔ JSON
            </button>
            <button class="tab-btn" data-tab="json-xml" onclick="switchTab('json-xml')">
                <i class="fas fa-code"></i> JSON ↔ XML
            </button>
            <button class="tab-btn" data-tab="json-yaml" onclick="switchTab('json-yaml')">
                <i class="fas fa-file-code"></i> JSON ↔ YAML
            </button>
            <button class="tab-btn" data-tab="xml-json" onclick="switchTab('xml-json')">
                <i class="fas fa-file-code"></i> XML ↔ JSON
            </button>
        </div>

        <!-- CSV ↔ JSON Tab -->
        <div class="tab-content active" id="csv-json-tab">
            <div class="converter-grid">
                <div class="input-section">
                    <label>CSV Input</label>
                    <div class="file-upload-zone" id="csvUploadZone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag & drop CSV file or paste below</p>
                        <input type="file" accept=".csv" onchange="handleCSVFile(event)">
                    </div>
                    <textarea id="csvInput" placeholder="name,age,city&#10;John,25,NYC&#10;Jane,30,LA" spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="csvToJson()" class="btn-convert">🔄 CSV → JSON</button>
                        <button onclick="jsonToCsv()" class="btn-convert-reverse">🔄 JSON → CSV</button>
                        <button onclick="clearCSV()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>JSON Output</label>
                    <textarea id="csvOutput" readonly placeholder="Converted JSON will appear here..." spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="copyCSVOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadCSVOutput('json')" class="btn-download">💾 Download JSON</button>
                        <button onclick="downloadCSVOutput('csv')" class="btn-download">💾 Download CSV</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JSON ↔ XML Tab -->
        <div class="tab-content" id="json-xml-tab">
            <div class="converter-grid">
                <div class="input-section">
                    <label>JSON Input</label>
                    <textarea id="jsonXmlInput" placeholder='{"users": [{"name": "John", "age": 25}]}' spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="jsonToXml()" class="btn-convert">🔄 JSON → XML</button>
                        <button onclick="xmlToJson()" class="btn-convert-reverse">🔄 XML → JSON</button>
                        <button onclick="clearJsonXml()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>XML Output</label>
                    <textarea id="jsonXmlOutput" readonly placeholder="Converted XML will appear here..." spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="copyJsonXmlOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadJsonXmlOutput('xml')" class="btn-download">💾 Download XML</button>
                        <button onclick="downloadJsonXmlOutput('json')" class="btn-download">💾 Download JSON</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JSON ↔ YAML Tab -->
        <div class="tab-content" id="json-yaml-tab">
            <div class="converter-grid">
                <div class="input-section">
                    <label>JSON Input</label>
                    <textarea id="jsonYamlInput" placeholder='{"name": "John", "age": 25, "city": "NYC"}' spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="jsonToYaml()" class="btn-convert">🔄 JSON → YAML</button>
                        <button onclick="yamlToJson()" class="btn-convert-reverse">🔄 YAML → JSON</button>
                        <button onclick="clearJsonYaml()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>YAML Output</label>
                    <textarea id="jsonYamlOutput" readonly placeholder="Converted YAML will appear here..." spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="copyJsonYamlOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadJsonYamlOutput('yaml')" class="btn-download">💾 Download YAML</button>
                        <button onclick="downloadJsonYamlOutput('json')" class="btn-download">💾 Download JSON</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- XML ↔ JSON Tab -->
        <div class="tab-content" id="xml-json-tab">
            <div class="converter-grid">
                <div class="input-section">
                    <label>XML Input</label>
                    <textarea id="xmlJsonInput" placeholder='<root><user><name>John</name><age>25</age></user></root>' spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="xmlToJsonAlt()" class="btn-convert">🔄 XML → JSON</button>
                        <button onclick="jsonToXmlAlt()" class="btn-convert-reverse">🔄 JSON → XML</button>
                        <button onclick="clearXmlJson()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <label>JSON Output</label>
                    <textarea id="xmlJsonOutput" readonly placeholder="Converted JSON will appear here..." spellcheck="false"></textarea>
                    <div class="button-group">
                        <button onclick="copyXmlJsonOutput()" class="btn-copy">📋 Copy</button>
                        <button onclick="downloadXmlJsonOutput('json')" class="btn-download">💾 Download JSON</button>
                        <button onclick="downloadXmlJsonOutput('xml')" class="btn-download">💾 Download XML</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast moved outside wrapper for global visibility -->
<div id="toast" class="toast"></div>
@include('partials.tool-content')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/file-converter.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/file-converter.js') }}"></script>
@endpush
