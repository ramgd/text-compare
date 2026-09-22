window.showToast = function (message, type = "info") {

    let toast = document.getElementById("toaster");

    if (!toast) return;

    toast.className = "toaster show " + type;
    toast.innerText = message;

    setTimeout(() => {
        toast.className = "toaster";
    }, 3000);
}

function go(url) {
    window.location.href = url;
}

let path = window.location.pathname;

document.querySelectorAll(".tools button").forEach(btn => {
    if (btn.dataset.url === path) {
        btn.classList.add("active");
    }
});

window.addEventListener("DOMContentLoaded", function () {

    let path = window.location.pathname || "/";

    let nav = document.getElementById("toolsNav");
    let buttons = document.querySelectorAll("#toolsNav button[data-url]");
    let indicator = document.getElementById("navIndicator");

    if (!nav || !indicator) return;

    let activeBtn = null;

    /* SET ACTIVE + INITIAL POSITION */
    buttons.forEach(btn => {

        let url = btn.dataset.url;

        if (url === path) {
            btn.classList.add("active");
            activeBtn = btn;
            moveIndicator(btn);
        }

        /* HOVER EFFECT 🔥 */
        btn.addEventListener("mouseenter", function () {
            moveIndicator(btn);
        });

        /* CLICK */
        btn.addEventListener("click", function () {
            activeBtn = btn;
            moveIndicator(btn);
        });

    });

    /* RESET TO ACTIVE ON LEAVE */
    nav.addEventListener("mouseleave", function () {
        if (activeBtn) {
            moveIndicator(activeBtn);
        }
    });

    /* MOVE INDICATOR */
    function moveIndicator(element) {

        /* The indicator is hidden on the mobile menu - skip the maths there
           so it can't be left with a stale width/offset on resize. */
        if (getComputedStyle(indicator).display === "none") return;

        let rect = element.getBoundingClientRect();
        let parentRect = element.parentElement.getBoundingClientRect();

        if (!rect.width) return;

        indicator.style.width = rect.width + "px";
        indicator.style.left = (rect.left - parentRect.left) + "px";
    }

    /* Reposition after a resize crosses the mobile/desktop boundary */
    let resizeTimer;
    window.addEventListener("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (activeBtn) moveIndicator(activeBtn);
        }, 150);
    });

    /* SCROLL SHADOW 🔥 */
    window.addEventListener("scroll", function () {

        if (window.scrollY > 10) {
            nav.classList.add("scrolled");
        } else {
            nav.classList.remove("scrolled");
        }

    });

});



function toggleDarkMode() {

    let body = document.body;
    let icon = document.getElementById("darkToggle");

    /* SAFETY CHECK 👇 */
    if (!icon) return;

    body.classList.toggle("dark");

    if (body.classList.contains("dark")) {
        localStorage.setItem("darkMode", "on");
        icon.innerText = "☀️"; // ✅ change to sun
    } else {
        localStorage.setItem("darkMode", "off");
        icon.innerText = "🌙"; // ✅ change to moon
    }

}

/* PAGE LOAD FIX */

window.addEventListener("DOMContentLoaded", function () {

    let mode = localStorage.getItem("darkMode");
    let icon = document.getElementById("darkToggle");

    /* SAFETY CHECK 👇 */
    if (!icon) return;

    if (mode === "on") {
        document.body.classList.add("dark");
        icon.innerText = "☀️"; // ✅ load sun
    } else {
        icon.innerText = "🌙"; // ✅ load moon
    }

});


/* ===== common.js — Global Utilities ===== */

// ── Toast Notification ──────────────────────────────────────
window.showToast = function (msg, type = '') {
    const t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.className = 'toast show ' + type;
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.className = 'toast', 3200);
};

// ── Theme Toggle ─────────────────────────────────────────────
(function () {
    const saved = localStorage.getItem('pdfpro_theme') || 'dark';
    document.documentElement.setAttribute('data-theme', saved);

    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.getElementById('themeToggle');
        if (!btn) return;
        const icon = btn.querySelector('i');
        const update = (theme) => {
            icon.className = theme === 'dark' ? 'fa fa-moon' : 'fa fa-sun';
        };
        update(saved);
        btn.addEventListener('click', () => {
            const cur = document.documentElement.getAttribute('data-theme');
            const next = cur === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('pdfpro_theme', next);
            update(next);
        });
    });
})();

// ── CSRF Helper for fetch ─────────────────────────────────────
window.getCsrf = function () {
    const m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.getAttribute('content') : '';
};

window.apiFetch = async function (url, options = {}) {
    const defaults = {
        headers: {
            'X-CSRF-TOKEN': getCsrf(),
            'Accept': 'application/json',
            ...(options.headers || {})
        }
    };
    const res = await fetch(url, { ...options, ...defaults, headers: { ...defaults.headers, ...(options.headers || {}) } });
    if (!res.ok) {
        const err = await res.json().catch(() => ({ message: 'Request failed' }));
        throw new Error(err.message || 'Request failed');
    }
    return res;
};

// ── Format File Size ──────────────────────────────────────────
window.formatBytes = function (bytes, dec = 1) {
    if (!bytes) return '0 B';
    const k = 1024, dm = dec < 0 ? 0 : dec;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

// ── Mobile Navigation Menu ───────────────────────────────────
// The tools nav collapses behind a hamburger at <=900px (see style.css).
// Desktop is untouched: the button is display:none and the nav stays open.
(function () {

    document.addEventListener('DOMContentLoaded', function () {

        const toggle = document.getElementById('navToggle');
        const nav = document.getElementById('toolsNav');

        if (!toggle || !nav) return;

        const isCollapsible = () =>
            getComputedStyle(toggle).display !== 'none';

        function setOpen(open) {
            nav.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            toggle.setAttribute(
                'aria-label',
                open ? 'Close navigation menu' : 'Open navigation menu'
            );
        }

        function close() {
            if (nav.classList.contains('open')) setOpen(false);
        }

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            setOpen(!nav.classList.contains('open'));
        });

        // Selecting a tool navigates away, but close first so the menu
        // isn't left open if navigation is blocked or the URL is the same.
        nav.querySelectorAll('button[data-url]').forEach(function (btn) {
            btn.addEventListener('click', close);
        });

        // Tapping outside the panel dismisses it
        document.addEventListener('click', function (e) {
            if (!isCollapsible()) return;
            if (nav.contains(e.target) || toggle.contains(e.target)) return;
            close();
        });

        // Escape closes and returns focus to the button
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            if (!nav.classList.contains('open')) return;
            close();
            toggle.focus();
        });

        // Growing past the breakpoint must not leave `.open` stuck on
        let t;
        window.addEventListener('resize', function () {
            clearTimeout(t);
            t = setTimeout(function () {
                if (!isCollapsible()) close();
            }, 150);
        });

    });

})();
