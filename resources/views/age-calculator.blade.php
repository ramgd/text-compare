@extends('layouts.app')

@section('content')
<div class="age-wrapper">
    <div class="age-container">
        <div class="age-header">
            <h1>🎂 Age Calculator</h1>
            <p class="subtitle">Calculate your exact age in years, months, days, hours, minutes, and seconds</p>
        </div>

        <div class="age-grid">
            <!-- Input Section -->
            <div class="age-input-section">
                <div class="card">
                    <h3>📅 Enter Your Date of Birth</h3>
                    <div class="input-group">
                        <label for="dobDate">Date of Birth</label>
                        <input type="date" id="dobDate" onchange="calculateAge()">
                    </div>
                    <div class="input-group">
                        <label for="dobTime">Time of Birth (Optional)</label>
                        <input type="time" id="dobTime" onchange="calculateAge()">
                    </div>
                    
                    <div class="quick-birthdays">
                        <label>Quick Select:</label>
                        <div class="quick-buttons">
                            <button onclick="setToday()" class="btn-quick">Today</button>
                            <button onclick="setYesterday()" class="btn-quick">Yesterday</button>
                            <button onclick="setBirthday2000()" class="btn-quick">Jan 1, 2000</button>
                            <button onclick="setCustomExample()" class="btn-quick">Example</button>
                        </div>
                    </div>

                    <button onclick="calculateAge()" class="btn-calculate">🧮 Calculate Age</button>
                    <button onclick="resetAll()" class="btn-reset">🔄 Reset</button>
                </div>
            </div>

            <!-- Results Section -->
            <div class="age-result-section">
                <div id="ageResult" style="display: none;">
                    <!-- Age Cards -->
                    <div class="age-cards">
                        <div class="age-card">
                            <div class="age-number" id="years">0</div>
                            <div class="age-label">Years</div>
                        </div>
                        <div class="age-card">
                            <div class="age-number" id="months">0</div>
                            <div class="age-label">Months</div>
                        </div>
                        <div class="age-card">
                            <div class="age-number" id="days">0</div>
                            <div class="age-label">Days</div>
                        </div>
                        <div class="age-card">
                            <div class="age-number" id="hours">0</div>
                            <div class="age-label">Hours</div>
                        </div>
                        <div class="age-card">
                            <div class="age-number" id="minutes">0</div>
                            <div class="age-label">Minutes</div>
                        </div>
                        <div class="age-card">
                            <div class="age-number" id="seconds">0</div>
                            <div class="age-label">Seconds</div>
                        </div>
                    </div>

                    <!-- Detailed Results -->
                    <div class="age-details">
                        <div class="detail-item">
                            <span class="detail-label">Total Days:</span>
                            <span class="detail-value" id="totalDays">0</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Weeks:</span>
                            <span class="detail-value" id="totalWeeks">0</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Hours:</span>
                            <span class="detail-value" id="totalHours">0</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Total Minutes:</span>
                            <span class="detail-value" id="totalMinutes">0</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Next Birthday:</span>
                            <span class="detail-value" id="nextBirthday">0</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Zodiac Sign:</span>
                            <span class="detail-value" id="zodiacSign">-</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Born on:</span>
                            <span class="detail-value" id="bornOn">-</span>
                        </div>
                    </div>

                    <!-- Life Stats -->
                    <div class="life-stats">
                        <h4>📊 Life Statistics</h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-icon">❤️</span>
                                <span class="stat-label">Heartbeats:</span>
                                <span class="stat-value" id="heartbeats">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">😴</span>
                                <span class="stat-label">Sleep Hours:</span>
                                <span class="stat-value" id="sleepHours">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">🍞</span>
                                <span class="stat-label">Meals Eaten:</span>
                                <span class="stat-value" id="mealsEaten">0</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-icon">👣</span>
                                <span class="stat-label">Steps Taken:</span>
                                <span class="stat-value" id="stepsTaken">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="agePlaceholder" class="age-placeholder">
                    <i class="fas fa-cake"></i>
                    <p>Enter your date of birth to see your age</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>
@endsection
