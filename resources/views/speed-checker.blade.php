@extends('layouts.app')

@section('content')
<div id="speedCheckerApp">
    <div class="speed-checker-wrapper">
        <div class="speed-checker-container">
            <!-- Header -->
            <div class="sc-header">
                <div class="sc-brand">
                    <div class="sc-brand-icon">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <div>
                        <h1>Speed Checker</h1>
                        <p class="sc-subtitle">Professional Internet Speed Test</p>
                    </div>
                </div>
                <div class="sc-theme-toggle">
                    <button onclick="scToggleTheme()" class="sc-theme-btn" id="scThemeBtn">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>

            <!-- Main Test Area -->
            <div class="sc-main">
                <!-- Speed Display -->
                <div class="sc-speed-display">
                    <div class="sc-speed-circle" id="scSpeedCircle">
                        <canvas id="scSpeedCanvas" width="260" height="260"></canvas>
                        <div class="sc-speed-center">
                            <span class="sc-speed-number" id="scSpeedNumber">0</span>
                            <span class="sc-speed-unit">Mbps</span>
                            <span class="sc-speed-status" id="scSpeedStatus">Ready</span>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="sc-stats-grid">
                    <div class="sc-stat-card download">
                        <div class="sc-stat-icon">⬇️</div>
                        <div class="sc-stat-info">
                            <span class="sc-stat-label">Download</span>
                            <span class="sc-stat-value" id="scDownload">-- Mbps</span>
                        </div>
                    </div>
                    <div class="sc-stat-card upload">
                        <div class="sc-stat-icon">⬆️</div>
                        <div class="sc-stat-info">
                            <span class="sc-stat-label">Upload</span>
                            <span class="sc-stat-value" id="scUpload">-- Mbps</span>
                        </div>
                    </div>
                    <div class="sc-stat-card ping">
                        <div class="sc-stat-icon">📶</div>
                        <div class="sc-stat-info">
                            <span class="sc-stat-label">Ping</span>
                            <span class="sc-stat-value" id="scPing">-- ms</span>
                        </div>
                    </div>
                    <div class="sc-stat-card jitter">
                        <div class="sc-stat-icon">⚡</div>
                        <div class="sc-stat-info">
                            <span class="sc-stat-label">Jitter</span>
                            <span class="sc-stat-value" id="scJitter">-- ms</span>
                        </div>
                    </div>
                    <div class="sc-stat-card packet-loss">
                        <div class="sc-stat-icon">📦</div>
                        <div class="sc-stat-info">
                            <span class="sc-stat-label">Packet Loss</span>
                            <span class="sc-stat-value" id="scPacketLoss">0%</span>
                        </div>
                    </div>
                    <div class="sc-stat-card ttfb">
                        <div class="sc-stat-icon">🌐</div>
                        <div class="sc-stat-info">
                            <span class="sc-stat-label">TTFB</span>
                            <span class="sc-stat-value" id="scTTFB">-- ms</span>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="sc-controls">
                    <button onclick="scStartTest()" class="sc-btn-start" id="scStartBtn">
                        <i class="fas fa-play" id="scStartIcon"></i>
                        <span id="scStartText">Start Test</span>
                    </button>
                    <button onclick="scResetTest()" class="sc-btn-reset">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                    <button onclick="scExportResults()" class="sc-btn-export">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>

                <!-- Charts -->
                <div class="sc-charts">
                    <div class="sc-chart-container">
                        <h4>Download Speed</h4>
                        <canvas id="scDownloadChart"></canvas>
                    </div>
                    <div class="sc-chart-container">
                        <h4>Upload Speed</h4>
                        <canvas id="scUploadChart"></canvas>
                    </div>
                    <div class="sc-chart-container">
                        <h4>Ping History</h4>
                        <canvas id="scPingChart"></canvas>
                    </div>
                </div>

                <!-- Connection Info -->
                <div class="sc-connection-info">
                    <div class="sc-info-grid">
                        <div class="sc-info-item">
                            <span class="sc-info-label">IP Address</span>
                            <span class="sc-info-value" id="scIP">-</span>
                        </div>
                        <div class="sc-info-item">
                            <span class="sc-info-label">ISP</span>
                            <span class="sc-info-value" id="scISP">-</span>
                        </div>
                        <div class="sc-info-item">
                            <span class="sc-info-label">Location</span>
                            <span class="sc-info-value" id="scLocation">-</span>
                        </div>
                        <div class="sc-info-item">
                            <span class="sc-info-label">Connection</span>
                            <span class="sc-info-value" id="scConnectionType">-</span>
                        </div>
                    </div>
                </div>

                <!-- History -->
                <div class="sc-history">
                    <div class="sc-history-header">
                        <h4><i class="fas fa-history"></i> Test History</h4>
                        <div class="sc-history-actions">
                            <input type="text" id="scSearchHistory" placeholder="Search..." onkeyup="scFilterHistory()">
                            <button onclick="scClearHistory()" class="sc-btn-clear-history">
                                <i class="fas fa-trash"></i> Clear
                            </button>
                        </div>
                    </div>
                    <div id="scHistoryList" class="sc-history-list">
                        <div class="sc-empty-history">
                            <i class="fas fa-inbox"></i>
                            <p>No tests performed yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="scToast" class="sc-toast"></div>
@endsection

<!-- @push('styles')
<link rel="stylesheet" href="{{ asset('css/speed-checker.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/speed-checker.js') }}"></script>
@endpush -->