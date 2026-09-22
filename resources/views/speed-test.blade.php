@extends('layouts.app')

@section('content')
<div class="speedtest-wrapper">
    <div class="speedtest-container">
        <!-- Header -->
        <div class="speedtest-header">
            <div class="speedtest-brand">
                <div class="brand-icon-wrapper">
                    <i class="fas fa-wifi"></i>
                </div>
                <h1>Internet Speed Test</h1>
            </div>
            <p class="brand-subtitle">Check your internet speed in real-time</p>
        </div>

        <!-- Main Speed Display -->
        <div class="speedtest-display">
            <div class="speedtest-circle" id="speedtestCircle">
                <div class="speedtest-number-wrapper">
                    <span class="speedtest-number" id="speedtestNumber">0</span>
                    <span class="speedtest-unit">Mbps</span>
                </div>
                <svg class="speedtest-svg" viewBox="0 0 120 120">
                    <circle class="speedtest-bg" cx="60" cy="60" r="54" />
                    <circle class="speedtest-progress" id="speedtestProgress" cx="60" cy="60" r="54" />
                </svg>
            </div>

            <div class="speedtest-stats">
                <div class="stat-item download">
                    <div class="stat-icon">⬇️</div>
                    <div class="stat-info">
                        <span class="stat-label">Download</span>
                        <span class="stat-value" id="speedtestDownload">0 Mbps</span>
                    </div>
                </div>
                <div class="stat-item upload">
                    <div class="stat-icon">⬆️</div>
                    <div class="stat-info">
                        <span class="stat-label">Upload</span>
                        <span class="stat-value" id="speedtestUpload">0 Mbps</span>
                    </div>
                </div>
                <div class="stat-item ping">
                    <div class="stat-icon">📶</div>
                    <div class="stat-info">
                        <span class="stat-label">Latency</span>
                        <span class="stat-value" id="speedtestPing">0 ms</span>
                    </div>
                </div>
                <div class="stat-item jitter">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-info">
                        <span class="stat-label">Jitter</span>
                        <span class="stat-value" id="speedtestJitter">0 ms</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="speedtest-controls">
            <button onclick="speedTestStart()" class="btn-start" id="speedtestBtn">
                <i class="fas fa-play" id="speedtestBtnIcon"></i>
                <span id="speedtestBtnText">Start Test</span>
            </button>
            <button onclick="speedTestReset()" class="btn-reset">
                <i class="fas fa-redo"></i> Reset
            </button>
        </div>

        <!-- Details Section -->
        <div class="speedtest-details" id="speedtestDetails" style="display:none;">
            <div class="details-header" onclick="speedTestToggleDetails()">
                <span>📊 Detailed Results</span>
                <i class="fas fa-chevron-down" id="speedtestArrow"></i>
            </div>
            <div class="details-content" id="speedtestDetailsContent">
                <div class="detail-row">
                    <span class="detail-label">Test Date</span>
                    <span class="detail-value" id="speedtestDate">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Download Speed</span>
                    <span class="detail-value" id="speedtestDownloadDetail">0 Mbps</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Upload Speed</span>
                    <span class="detail-value" id="speedtestUploadDetail">0 Mbps</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Latency</span>
                    <span class="detail-value" id="speedtestPingDetail">0 ms</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jitter</span>
                    <span class="detail-value" id="speedtestJitterDetail">0 ms</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Rating</span>
                    <span class="detail-value" id="speedtestRating">-</span>
                </div>
            </div>
        </div>

        <!-- History Section -->
        <div class="speedtest-history">
            <div class="history-header">
                <span><i class="fas fa-history"></i> Test History</span>
                <button onclick="speedTestClearHistory()" class="btn-clear-history">
                    <i class="fas fa-trash"></i> Clear
                </button>
            </div>
            <div id="speedtestHistoryList" class="history-list">
                <div class="empty-history">
                    <i class="fas fa-inbox"></i>
                    <p>No tests performed yet</p>
                </div>
            </div>
        </div>

        <!-- Internet Status -->
        <div class="speedtest-status">
            <div class="status-item">
                <span class="status-dot" id="statusDot"></span>
                <span class="status-text" id="statusText">Ready</span>
            </div>
            <div class="status-item">
                <i class="fas fa-globe"></i>
                <span id="statusIP">-</span>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="speedtestToast" class="speedtest-toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/speed-test.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/speed-test.js') }}"></script>
@endpush