<header class="header">

    <!-- MOBILE MENU BUTTON (hidden on desktop via CSS) -->
    <button type="button"
            id="navToggle"
            class="header-btn nav-toggle"
            aria-controls="toolsNav"
            aria-expanded="false"
            aria-label="Open navigation menu">
        <span class="nav-toggle-bars" aria-hidden="true"></span>
    </button>

    <span class="header-title">GLOBAL AITOOLYFY TOOLS</span>

    <!-- DARK MODE ICON -->
    <button type="button"
            id="darkToggle"
            class="header-btn"
            onclick="toggleDarkMode()"
            aria-label="Toggle dark mode"
            title="Toggle dark mode">
        🌙
    </button>
</header>

<nav class="tools ultra-nav" id="toolsNav" aria-label="Tools">
    <div class="nav-indicator" id="navIndicator" aria-hidden="true"></div> <!-- 🔥 ADD THIS -->

    <button data-url="/dashboard" onclick="location.href='/dashboard'">Dashboard</button>
    <button data-url="/" onclick="location.href='/'">Compare Tool</button>
    <button data-url="/json_formatter" onclick="location.href='/json_formatter'">JSON Formatter</button>
    <!-- <button>Password Generator</button> -->
    <button data-url="/password-generator" onclick="location.href='/password-generator'">Password Generator</button>
    <!-- <button data-url="/sql-minifier" onclick="location.href='/sql-minifier'">SQL Minifier</button> -->
    <button data-url="/sql_minifier" onclick="location.href='/sql_minifier'">SQL Minifier</button>
    <button data-url="/code-beautifier" onclick="location.href='/code-beautifier'">Code Beautifier</button>
    <button data-url="/api_tester" onclick="location.href='/api_tester'">API Tester</button>
    <button data-url="/qr-generator" onclick="location.href='/qr-generator'">QR Code Generator</button>
    <button data-url="/image-to-text" onclick="location.href='/image-to-text'">Image To Text </button>
    <button data-url="/text-to-image" onclick="location.href='/text-to-image'"> Text To Image </button>
</nav>
