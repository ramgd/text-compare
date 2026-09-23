@extends('layouts.app')

@section('content')
<div class="ip-wrapper">
    <div class="ip-container">
        <div class="ip-header">
            <h1>🌐 IP Address Tools</h1>
            <p class="subtitle">Get your IP address, lookup location, validate IPs, and more</p>
        </div>

        <div class="ip-tabs">
            <button class="tab-btn active" data-tab="myip" onclick="switchTab('myip')">
                <i class="fas fa-user"></i> My IP
            </button>
            <button class="tab-btn" data-tab="lookup" onclick="switchTab('lookup')">
                <i class="fas fa-search"></i> IP Lookup
            </button>
            <button class="tab-btn" data-tab="validate" onclick="switchTab('validate')">
                <i class="fas fa-check-circle"></i> Validate
            </button>
            <button class="tab-btn" data-tab="whois" onclick="switchTab('whois')">
                <i class="fas fa-info-circle"></i> Whois
            </button>
        </div>

        <!-- My IP Tab -->
        <div class="tab-content active" id="myip-tab">
            <div class="ip-grid">
                <div class="myip-info">
                    <div class="ip-card">
                        <div class="ip-icon">📡</div>
                        <div class="ip-details">
                            <span class="ip-label">Your IP Address</span>
                            <span class="ip-value" id="myIPAddress">Loading...</span>
                        </div>
                        <button onclick="copyIP()" class="btn-copy-ip">📋 Copy IP</button>
                        <button onclick="refreshIP()" class="btn-refresh-ip">🔄 Refresh</button>
                    </div>
                    <div class="ip-details-grid">
                        <div class="detail-card">
                            <span class="detail-label">ISP</span>
                            <span class="detail-value" id="ispInfo">-</span>
                        </div>
                        <div class="detail-card">
                            <span class="detail-label">Country</span>
                            <span class="detail-value" id="countryInfo">-</span>
                        </div>
                        <div class="detail-card">
                            <span class="detail-label">City</span>
                            <span class="detail-value" id="cityInfo">-</span>
                        </div>
                        <div class="detail-card">
                            <span class="detail-label">Timezone</span>
                            <span class="detail-value" id="timezoneInfo">-</span>
                        </div>
                        <div class="detail-card">
                            <span class="detail-label">Lat/Long</span>
                            <span class="detail-value" id="locationInfo">-</span>
                        </div>
                        <div class="detail-card">
                            <span class="detail-label">IP Type</span>
                            <span class="detail-value" id="ipType">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- IP Lookup Tab -->
        <div class="tab-content" id="lookup-tab">
            <div class="ip-grid">
                <div class="input-section">
                    <label>Enter IP Address or Domain</label>
                    <div class="lookup-input-group">
                        <input type="text" id="lookupInput" placeholder="e.g., 8.8.8.8 or google.com">
                        <button onclick="lookupIP()" class="btn-lookup">🔍 Lookup</button>
                    </div>
                    <div class="lookup-presets">
                        <button onclick="setLookupIP('8.8.8.8')">8.8.8.8</button>
                        <button onclick="setLookupIP('1.1.1.1')">1.1.1.1</button>
                        <button onclick="setLookupIP('google.com')">google.com</button>
                        <button onclick="setLookupIP('github.com')">github.com</button>
                    </div>
                </div>
                <div class="output-section">
                    <div id="lookupResults">
                        <div class="lookup-result-item">
                            <span class="lookup-label">IP Address:</span>
                            <span class="lookup-value" id="lookupIPAddress">-</span>
                        </div>
                        <div class="lookup-result-item">
                            <span class="lookup-label">Hostname:</span>
                            <span class="lookup-value" id="lookupHostname">-</span>
                        </div>
                        <div class="lookup-result-item">
                            <span class="lookup-label">Country:</span>
                            <span class="lookup-value" id="lookupCountry">-</span>
                        </div>
                        <div class="lookup-result-item">
                            <span class="lookup-label">Region:</span>
                            <span class="lookup-value" id="lookupRegion">-</span>
                        </div>
                        <div class="lookup-result-item">
                            <span class="lookup-label">City:</span>
                            <span class="lookup-value" id="lookupCity">-</span>
                        </div>
                        <div class="lookup-result-item">
                            <span class="lookup-label">ISP:</span>
                            <span class="lookup-value" id="lookupISP">-</span>
                        </div>
                        <div class="lookup-result-item">
                            <span class="lookup-label">Coordinates:</span>
                            <span class="lookup-value" id="lookupCoordinates">-</span>
                        </div>
                        <div class="lookup-result-item">
                            <span class="lookup-label">Timezone:</span>
                            <span class="lookup-value" id="lookupTimezone">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Validate Tab -->
        <div class="tab-content" id="validate-tab">
            <div class="ip-grid">
                <div class="input-section">
                    <label>Validate IP Address</label>
                    <div class="validate-input-group">
                        <input type="text" id="validateInput" placeholder="Enter IP to validate...">
                        <button onclick="validateIP()" class="btn-validate">✅ Validate</button>
                    </div>
                    <div class="validate-presets">
                        <button onclick="setValidateIP('192.168.1.1')">192.168.1.1</button>
                        <button onclick="setValidateIP('256.256.256.256')">256.256.256.256</button>
                        <button onclick="setValidateIP('2001:0db8:85a3:0000:0000:8a2e:0370:7334')">IPv6</button>
                        <button onclick="setValidateIP('10.0.0.1')">10.0.0.1</button>
                    </div>
                </div>
                <div class="output-section">
                    <div id="validateResults">
                        <div class="validate-result-item">
                            <span class="validate-label">IP Address:</span>
                            <span class="validate-value" id="validateIP">-</span>
                        </div>
                        <div class="validate-result-item">
                            <span class="validate-label">Status:</span>
                            <span class="validate-value" id="validateStatus">-</span>
                        </div>
                        <div class="validate-result-item">
                            <span class="validate-label">Type:</span>
                            <span class="validate-value" id="validateType">-</span>
                        </div>
                        <div class="validate-result-item">
                            <span class="validate-label">Version:</span>
                            <span class="validate-value" id="validateVersion">-</span>
                        </div>
                        <div class="validate-result-item">
                            <span class="validate-label">Is Private:</span>
                            <span class="validate-value" id="validatePrivate">-</span>
                        </div>
                        <div class="validate-result-item">
                            <span class="validate-label">Is Loopback:</span>
                            <span class="validate-value" id="validateLoopback">-</span>
                        </div>
                        <div class="validate-result-item">
                            <span class="validate-label">Is Multicast:</span>
                            <span class="validate-value" id="validateMulticast">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Whois Tab -->
        <div class="tab-content" id="whois-tab">
            <div class="ip-grid">
                <div class="input-section">
                    <label>Whois Lookup</label>
                    <div class="whois-input-group">
                        <input type="text" id="whoisInput" placeholder="Enter domain or IP...">
                        <button onclick="whoisLookup()" class="btn-whois">🔍 Whois</button>
                    </div>
                    <div class="whois-presets">
                        <button onclick="setWhois('google.com')">google.com</button>
                        <button onclick="setWhois('github.com')">github.com</button>
                        <button onclick="setWhois('8.8.8.8')">8.8.8.8</button>
                        <button onclick="setWhois('example.com')">example.com</button>
                    </div>
                </div>
                <div class="output-section">
                    <div id="whoisResults">
                        <div class="whois-result-item">
                            <span class="whois-label">Domain/IP:</span>
                            <span class="whois-value" id="whoisDomain">-</span>
                        </div>
                        <div class="whois-result-item">
                            <span class="whois-label">Registrar:</span>
                            <span class="whois-value" id="whoisRegistrar">-</span>
                        </div>
                        <div class="whois-result-item">
                            <span class="whois-label">Creation Date:</span>
                            <span class="whois-value" id="whoisCreation">-</span>
                        </div>
                        <div class="whois-result-item">
                            <span class="whois-label">Expiry Date:</span>
                            <span class="whois-value" id="whoisExpiry">-</span>
                        </div>
                        <div class="whois-result-item">
                            <span class="whois-label">Name Servers:</span>
                            <span class="whois-value" id="whoisNameservers">-</span>
                        </div>
                        <div class="whois-result-item">
                            <span class="whois-label">Status:</span>
                            <span class="whois-value" id="whoisStatus">-</span>
                        </div>
                    </div>
                    <div id="whoisRaw" style="display:none; margin-top:15px; padding:15px; background:#f8f9fa; border-radius:6px; border:1px solid #e0e0e0;">
                        <label>Raw Whois Data</label>
                        <pre id="whoisRawData" style="white-space:pre-wrap; word-break:break-all; max-height:300px; overflow-y:auto; font-size:12px; background:#fff; padding:10px; border-radius:4px;"></pre>
                        <button onclick="copyWhoisRaw()" class="btn-copy-raw">📋 Copy Raw Data</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@include('partials.tool-content')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ip-tools.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/ip-tools.js') }}"></script>
@endpush
