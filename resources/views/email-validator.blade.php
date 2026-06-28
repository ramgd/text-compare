@extends('layouts.app')

@section('content')
<div class="email-wrapper">
    <div class="email-container">
        <div class="email-header">
            <h1>📧 Email Validator & Generator</h1>
            <p class="subtitle">Validate email addresses, check domain, generate fake emails for testing</p>
        </div>

        <div class="email-tabs">
            <button class="tab-btn active" data-tab="validate" onclick="switchTab('validate')">
                <i class="fas fa-check-circle"></i> Validate
            </button>
            <button class="tab-btn" data-tab="generate" onclick="switchTab('generate')">
                <i class="fas fa-plus-circle"></i> Generate
            </button>
            <button class="tab-btn" data-tab="bulk" onclick="switchTab('bulk')">
                <i class="fas fa-list"></i> Bulk Validate
            </button>
        </div>

        <!-- Validate Tab -->
        <div class="tab-content active" id="validate-tab">
            <div class="email-grid">
                <div class="input-section">
                    <label>Enter Email Address</label>
                    <div class="email-input-group">
                        <input type="text" id="emailInput" placeholder="e.g., test@example.com">
                        <button onclick="validateEmail()" class="btn-validate">✅ Validate</button>
                    </div>
                    <div class="quick-emails">
                        <label>Quick Test:</label>
                        <button onclick="setEmail('test@gmail.com')">test@gmail.com</button>
                        <button onclick="setEmail('admin@yahoo.com')">admin@yahoo.com</button>
                        <button onclick="setEmail('user@outlook.com')">user@outlook.com</button>
                        <button onclick="setEmail('invalid-email')">invalid-email</button>
                        <button onclick="setEmail('test@mailinator.com')">test@mailinator.com</button>
                    </div>
                </div>
                <div class="output-section">
                    <h3>Validation Results</h3>
                    <div id="validateResults">
                        <div class="result-item">
                            <span class="result-label">Email:</span>
                            <span class="result-value" id="valEmail">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Status:</span>
                            <span class="result-value" id="valStatus">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Domain:</span>
                            <span class="result-value" id="valDomain">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">MX Records:</span>
                            <span class="result-value" id="valMx">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Disposable:</span>
                            <span class="result-value" id="valDisposable">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Format:</span>
                            <span class="result-value" id="valFormat">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Username:</span>
                            <span class="result-value" id="valUsername">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate Tab -->
        <div class="tab-content" id="generate-tab">
            <div class="email-grid">
                <div class="input-section">
                    <label>Generate Test Emails</label>
                    <div class="generate-controls">
                        <div class="control-group">
                            <label>Number of Emails:</label>
                            <select id="emailCount" onchange="generateEmails()">
                                <option value="1">1</option>
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="control-group">
                            <label>Domain:</label>
                            <select id="emailDomain">
                                <option value="gmail.com">gmail.com</option>
                                <option value="yahoo.com">yahoo.com</option>
                                <option value="outlook.com">outlook.com</option>
                                <option value="hotmail.com">hotmail.com</option>
                                <option value="example.com">example.com</option>
                                <option value="test.com">test.com</option>
                                <option value="company.com">company.com</option>
                            </select>
                        </div>
                    </div>
                    <div class="button-group">
                        <button onclick="generateEmails()" class="btn-generate">🔄 Generate</button>
                        <button onclick="copyGeneratedEmails()" class="btn-copy">📋 Copy All</button>
                        <button onclick="downloadEmails()" class="btn-download">💾 Download</button>
                        <button onclick="clearGenerated()" class="btn-clear">🗑️ Clear</button>
                    </div>
                </div>
                <div class="output-section">
                    <h3>Generated Emails</h3>
                    <div id="generatedEmails">
                        <textarea id="generatedList" readonly placeholder="Generated emails will appear here..."></textarea>
                        <div class="generated-stats">
                            <span>Total: <span id="genCount">0</span></span>
                            <span>Copy to clipboard or download as CSV</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Validate Tab -->
        <div class="tab-content" id="bulk-tab">
            <div class="email-grid">
                <div class="input-section">
                    <label>Bulk Email Validation</label>
                    <textarea id="bulkInput" placeholder="Enter emails one per line..."></textarea>
                    <div class="button-group">
                        <button onclick="bulkValidate()" class="btn-validate">✅ Validate All</button>
                        <button onclick="clearBulk()" class="btn-clear">🗑️ Clear</button>
                        <button onclick="downloadBulkResults()" class="btn-download">💾 Download Results</button>
                    </div>
                    <div class="bulk-presets">
                        <label>Sample:</label>
                        <button onclick="loadSampleEmails()">Load Sample</button>
                    </div>
                </div>
                <div class="output-section">
                    <h3>Bulk Results</h3>
                    <div id="bulkResults">
                        <div class="bulk-stats">
                            <span>Total: <span id="bulkTotal">0</span></span>
                            <span>Valid: <span id="bulkValid">0</span></span>
                            <span>Invalid: <span id="bulkInvalid">0</span></span>
                            <span>Disposable: <span id="bulkDisposable">0</span></span>
                        </div>
                        <div id="bulkList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection
<!-- 
@push('styles')
<link rel="stylesheet" href="{{ asset('css/email-validator.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/email-validator.js') }}"></script>
@endpush -->