// Age Calculator JavaScript

let ageInterval = null;

// Calculate Age
function calculateAge() {
    const dobDate = document.getElementById('dobDate').value;
    const dobTime = document.getElementById('dobTime').value || '00:00';
    
    if (!dobDate) {
        showToast('Please select your date of birth', 'error');
        return;
    }
    
    // Create date of birth with time
    const dob = new Date(`${dobDate}T${dobTime}`);
    
    // Validate date
    if (isNaN(dob.getTime())) {
        showToast('Invalid date selected', 'error');
        return;
    }
    
    // Check if date is in future
    const now = new Date();
    if (dob > now) {
        showToast('Date of birth cannot be in the future!', 'error');
        return;
    }
    
    // Update age in real-time
    updateAge(dob);
    
    // Clear existing interval
    if (ageInterval) {
        clearInterval(ageInterval);
    }
    
    // Update every second
    ageInterval = setInterval(() => {
        updateAge(dob);
    }, 1000);
    
    // Show results
    document.getElementById('ageResult').style.display = 'block';
    document.getElementById('agePlaceholder').style.display = 'none';
    
    showToast('Age calculated successfully!', 'success');
}

// Update Age Function
function updateAge(dob) {
    const now = new Date();
    const diff = now - dob;
    
    // Basic calculations
    const years = Math.floor(diff / (1000 * 60 * 60 * 24 * 365.25));
    const months = Math.floor(diff / (1000 * 60 * 60 * 24 * 30.44));
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor(diff / (1000 * 60));
    const seconds = Math.floor(diff / 1000);
    
    // Update cards
    document.getElementById('years').textContent = formatNumber(years);
    document.getElementById('months').textContent = formatNumber(months);
    document.getElementById('days').textContent = formatNumber(days);
    document.getElementById('hours').textContent = formatNumber(hours);
    document.getElementById('minutes').textContent = formatNumber(minutes);
    document.getElementById('seconds').textContent = formatNumber(seconds);
    
    // Update details
    const weeks = Math.floor(days / 7);
    document.getElementById('totalDays').textContent = formatNumber(days);
    document.getElementById('totalWeeks').textContent = formatNumber(weeks);
    document.getElementById('totalHours').textContent = formatNumber(hours);
    document.getElementById('totalMinutes').textContent = formatNumber(minutes);
    
    // Next birthday
    const nextBirthday = getNextBirthday(dob);
    document.getElementById('nextBirthday').textContent = nextBirthday;
    
    // Zodiac sign
    const zodiac = getZodiacSign(dob);
    document.getElementById('zodiacSign').textContent = zodiac;
    
    // Born on (day of week)
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    document.getElementById('bornOn').textContent = daysOfWeek[dob.getDay()];
    
    // Life statistics
    updateLifeStats(seconds);
}

// Life Statistics
function updateLifeStats(seconds) {
    // Average heartbeats: 72 per minute
    const heartbeats = Math.floor(seconds * 72 / 60);
    document.getElementById('heartbeats').textContent = formatNumber(heartbeats);
    
    // Average sleep: 8 hours per day
    const sleepHours = Math.floor(seconds / (60 * 60 * 24) * 8);
    document.getElementById('sleepHours').textContent = formatNumber(sleepHours);
    
    // Average meals: 3 per day
    const meals = Math.floor(seconds / (60 * 60 * 24) * 3);
    document.getElementById('mealsEaten').textContent = formatNumber(meals);
    
    // Average steps: 10,000 per day
    const steps = Math.floor(seconds / (60 * 60 * 24) * 10000);
    document.getElementById('stepsTaken').textContent = formatNumber(steps);
}

// Get Next Birthday
function getNextBirthday(dob) {
    const now = new Date();
    let nextBirthday = new Date(now.getFullYear(), dob.getMonth(), dob.getDate());
    
    if (nextBirthday < now) {
        nextBirthday.setFullYear(nextBirthday.getFullYear() + 1);
    }
    
    const diffDays = Math.ceil((nextBirthday - now) / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
        return '🎉 Today is your birthday!';
    } else if (diffDays === 1) {
        return 'Tomorrow';
    } else {
        return `${diffDays} days`;
    }
}

// Get Zodiac Sign
function getZodiacSign(dob) {
    const month = dob.getMonth() + 1;
    const day = dob.getDate();
    
    const zodiacSigns = [
        { sign: '♈ Aries', start: { month: 3, day: 21 }, end: { month: 4, day: 19 } },
        { sign: '♉ Taurus', start: { month: 4, day: 20 }, end: { month: 5, day: 20 } },
        { sign: '♊ Gemini', start: { month: 5, day: 21 }, end: { month: 6, day: 20 } },
        { sign: '♋ Cancer', start: { month: 6, day: 21 }, end: { month: 7, day: 22 } },
        { sign: '♌ Leo', start: { month: 7, day: 23 }, end: { month: 8, day: 22 } },
        { sign: '♍ Virgo', start: { month: 8, day: 23 }, end: { month: 9, day: 22 } },
        { sign: '♎ Libra', start: { month: 9, day: 23 }, end: { month: 10, day: 22 } },
        { sign: '♏ Scorpio', start: { month: 10, day: 23 }, end: { month: 11, day: 21 } },
        { sign: '♐ Sagittarius', start: { month: 11, day: 22 }, end: { month: 12, day: 21 } },
        { sign: '♑ Capricorn', start: { month: 12, day: 22 }, end: { month: 1, day: 19 } },
        { sign: '♒ Aquarius', start: { month: 1, day: 20 }, end: { month: 2, day: 18 } },
        { sign: '♓ Pisces', start: { month: 2, day: 19 }, end: { month: 3, day: 20 } }
    ];
    
    for (const zodiac of zodiacSigns) {
        const start = new Date(2000, zodiac.start.month - 1, zodiac.start.day);
        const end = new Date(2000, zodiac.end.month - 1, zodiac.end.day);
        const current = new Date(2000, month - 1, day);
        
        if (zodiac.sign === '♑ Capricorn') {
            if ((month === 12 && day >= 22) || (month === 1 && day <= 19)) {
                return zodiac.sign;
            }
        } else {
            if (current >= start && current <= end) {
                return zodiac.sign;
            }
        }
    }
    return 'Unknown';
}

// Format Number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Quick Set Functions
function setToday() {
    const today = new Date();
    const year = today.getFullYear() - 25; // Default 25 years old
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    document.getElementById('dobDate').value = `${year}-${month}-${day}`;
    document.getElementById('dobTime').value = '00:00';
    calculateAge();
}

function setYesterday() {
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    const year = yesterday.getFullYear() - 25;
    const month = String(yesterday.getMonth() + 1).padStart(2, '0');
    const day = String(yesterday.getDate()).padStart(2, '0');
    document.getElementById('dobDate').value = `${year}-${month}-${day}`;
    document.getElementById('dobTime').value = '00:00';
    calculateAge();
}

function setBirthday2000() {
    document.getElementById('dobDate').value = '2000-01-01';
    document.getElementById('dobTime').value = '00:00';
    calculateAge();
}

function setCustomExample() {
    document.getElementById('dobDate').value = '1990-06-15';
    document.getElementById('dobTime').value = '14:30';
    calculateAge();
}

// Reset All
function resetAll() {
    document.getElementById('dobDate').value = '';
    document.getElementById('dobTime').value = '';
    document.getElementById('ageResult').style.display = 'none';
    document.getElementById('agePlaceholder').style.display = 'flex';
    
    if (ageInterval) {
        clearInterval(ageInterval);
        ageInterval = null;
    }
    
    showToast('Reset successfully', 'info');
}

// Auto calculate on date change
document.addEventListener('DOMContentLoaded', function() {
    // Set default date to 25 years ago
    const today = new Date();
    const year = today.getFullYear() - 25;
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    document.getElementById('dobDate').value = `${year}-${month}-${day}`;
    
    // Auto calculate
    setTimeout(() => {
        calculateAge();
    }, 100);
});

// Toast System
/* showToast() lives in js/common.js - every tool had a byte-for-byte
   equivalent copy of it. common.js is loaded first on every page. */
