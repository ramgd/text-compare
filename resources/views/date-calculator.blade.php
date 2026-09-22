@extends('layouts.app')

@section('content')
<div class="date-wrapper">
    <div class="date-container">
        <div class="date-header">
            <h1>📅 Date & Time Calculator</h1>
            <p class="subtitle">Calculate days between dates, add/subtract time, and more</p>
        </div>

        <div class="date-tabs">
            <button class="tab-btn active" data-tab="between" onclick="switchTab('between')">
                <i class="fas fa-calendar-alt"></i> Date Difference
            </button>
            <button class="tab-btn" data-tab="add" onclick="switchTab('add')">
                <i class="fas fa-plus-circle"></i> Add/Subtract
            </button>
            <button class="tab-btn" data-tab="business" onclick="switchTab('business')">
                <i class="fas fa-briefcase"></i> Business Days
            </button>
            <button class="tab-btn" data-tab="timezone" onclick="switchTab('timezone')">
                <i class="fas fa-globe"></i> Timezone
            </button>
        </div>

        <!-- Date Difference Tab -->
        <div class="tab-content active" id="between-tab">
            <div class="date-grid">
                <div class="input-section">
                    <div class="date-input-group">
                        <label>Start Date</label>
                        <input type="date" id="startDate" onchange="calculateDifference()">
                    </div>
                    <div class="date-input-group">
                        <label>End Date</label>
                        <input type="date" id="endDate" onchange="calculateDifference()">
                    </div>
                    <div class="button-group">
                        <button onclick="calculateDifference()" class="btn-calculate">🧮 Calculate</button>
                        <button onclick="clearDifference()" class="btn-clear">🗑️ Clear</button>
                    </div>
                    <div class="quick-presets">
                        <label>Quick:</label>
                        <button onclick="setDateRange(7)">7 Days</button>
                        <button onclick="setDateRange(30)">30 Days</button>
                        <button onclick="setDateRange(90)">90 Days</button>
                        <button onclick="setDateRange(365)">1 Year</button>
                        <button onclick="setDateRange(730)">2 Years</button>
                    </div>
                </div>
                <div class="output-section">
                    <h3>Difference Results</h3>
                    <div id="differenceResults">
                        <div class="result-item">
                            <span class="result-label">Years:</span>
                            <span class="result-value" id="diffYears">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Months:</span>
                            <span class="result-value" id="diffMonths">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Days:</span>
                            <span class="result-value" id="diffDays">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Hours:</span>
                            <span class="result-value" id="diffHours">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Minutes:</span>
                            <span class="result-value" id="diffMinutes">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Seconds:</span>
                            <span class="result-value" id="diffSeconds">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Weeks:</span>
                            <span class="result-value" id="diffWeeks">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Weekdays:</span>
                            <span class="result-value" id="diffWeekdays">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Weekends:</span>
                            <span class="result-value" id="diffWeekends">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Subtract Tab -->
        <div class="tab-content" id="add-tab">
            <div class="date-grid">
                <div class="input-section">
                    <div class="date-input-group">
                        <label>Base Date</label>
                        <input type="date" id="baseDate" onchange="calculateAddSubtract()">
                    </div>
                    <div class="add-subtract-controls">
                        <div class="control-row">
                            <label>Years</label>
                            <input type="number" id="addYears" value="0" min="0" max="100" onchange="calculateAddSubtract()" oninput="calculateAddSubtract()">
                        </div>
                        <div class="control-row">
                            <label>Months</label>
                            <input type="number" id="addMonths" value="0" min="0" max="12" onchange="calculateAddSubtract()" oninput="calculateAddSubtract()">
                        </div>
                        <div class="control-row">
                            <label>Days</label>
                            <input type="number" id="addDays" value="0" min="0" max="365" onchange="calculateAddSubtract()" oninput="calculateAddSubtract()">
                        </div>
                        <div class="control-row">
                            <label>Hours</label>
                            <input type="number" id="addHours" value="0" min="0" max="23" onchange="calculateAddSubtract()" oninput="calculateAddSubtract()">
                        </div>
                        <div class="control-row">
                            <label>Minutes</label>
                            <input type="number" id="addMinutes" value="0" min="0" max="59" onchange="calculateAddSubtract()" oninput="calculateAddSubtract()">
                        </div>
                    </div>
                    <div class="button-group">
                        <button onclick="setAddMode('add')" class="btn-add-mode active" id="addModeBtn">➕ Add</button>
                        <button onclick="setAddMode('subtract')" class="btn-subtract-mode" id="subtractModeBtn">➖ Subtract</button>
                    </div>
                    <button onclick="calculateAddSubtract()" class="btn-calculate">🧮 Calculate</button>
                </div>
                <div class="output-section">
                    <h3>Result Date</h3>
                    <div id="addResults">
                        <div class="result-item">
                            <span class="result-label">Original Date:</span>
                            <span class="result-value" id="addOriginalDate">-</span>
                        </div>
                        <div class="result-item highlight-result">
                            <span class="result-label">New Date:</span>
                            <span class="result-value" id="addNewDate">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Day of Week:</span>
                            <span class="result-value" id="addDayOfWeek">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Week Number:</span>
                            <span class="result-value" id="addWeekNumber">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Days Tab -->
        <div class="tab-content" id="business-tab">
            <div class="date-grid">
                <div class="input-section">
                    <div class="date-input-group">
                        <label>Start Date</label>
                        <input type="date" id="businessStartDate" onchange="calculateBusinessDays()">
                    </div>
                    <div class="date-input-group">
                        <label>Business Days to Add</label>
                        <input type="number" id="businessDays" value="10" min="1" max="365" onchange="calculateBusinessDays()" oninput="calculateBusinessDays()">
                    </div>
                    <button onclick="calculateBusinessDays()" class="btn-calculate">🧮 Calculate</button>
                </div>
                <div class="output-section">
                    <h3>Business Days Result</h3>
                    <div id="businessResults">
                        <div class="result-item">
                            <span class="result-label">Start Date:</span>
                            <span class="result-value" id="businessStart">-</span>
                        </div>
                        <div class="result-item highlight-result">
                            <span class="result-label">End Date (Business):</span>
                            <span class="result-value" id="businessEnd">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Total Business Days:</span>
                            <span class="result-value" id="businessTotal">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Weekends Skipped:</span>
                            <span class="result-value" id="businessWeekends">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timezone Tab -->
        <div class="tab-content" id="timezone-tab">
            <div class="date-grid">
                <div class="input-section">
                    <div class="date-input-group">
                        <label>Select Date & Time</label>
                        <input type="datetime-local" id="timezoneDateTime" onchange="convertTimezone()">
                    </div>
                    <div class="date-input-group">
                        <label>From Timezone</label>
                        <select id="fromTimezone" onchange="convertTimezone()">
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">US Eastern</option>
                            <option value="America/Chicago">US Central</option>
                            <option value="America/Denver">US Mountain</option>
                            <option value="America/Los_Angeles">US Pacific</option>
                            <option value="Europe/London">London</option>
                            <option value="Europe/Paris">Paris</option>
                            <option value="Asia/Dubai">Dubai</option>
                            <option value="Asia/Kolkata">India (IST)</option>
                            <option value="Asia/Tokyo">Tokyo</option>
                            <option value="Australia/Sydney">Sydney</option>
                        </select>
                    </div>
                    <div class="date-input-group">
                        <label>To Timezone</label>
                        <select id="toTimezone" onchange="convertTimezone()">
                            <option value="UTC">UTC</option>
                            <option value="America/New_York">US Eastern</option>
                            <option value="America/Chicago">US Central</option>
                            <option value="America/Denver">US Mountain</option>
                            <option value="America/Los_Angeles">US Pacific</option>
                            <option value="Europe/London">London</option>
                            <option value="Europe/Paris">Paris</option>
                            <option value="Asia/Dubai">Dubai</option>
                            <option value="Asia/Kolkata" selected>India (IST)</option>
                            <option value="Asia/Tokyo">Tokyo</option>
                            <option value="Australia/Sydney">Sydney</option>
                        </select>
                    </div>
                    <button onclick="convertTimezone()" class="btn-calculate">🔄 Convert</button>
                </div>
                <div class="output-section">
                    <h3>Timezone Conversion</h3>
                    <div id="timezoneResults">
                        <div class="result-item">
                            <span class="result-label">Original Time:</span>
                            <span class="result-value" id="tzOriginal">-</span>
                        </div>
                        <div class="result-item highlight-result">
                            <span class="result-label">Converted Time:</span>
                            <span class="result-value" id="tzConverted">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Time Difference:</span>
                            <span class="result-value" id="tzDifference">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">From Timezone:</span>
                            <span class="result-value" id="tzFrom">-</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">To Timezone:</span>
                            <span class="result-value" id="tzTo">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/date-calculator.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/date-calculator.js') }}"></script>
@endpush
