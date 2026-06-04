/**
 * APSU Client Scripts - assets/js/main.js
 *
 * Loaded on login and all authenticated pages (footer.php).
 * Handles password toggle, confirm dialogs, strength meter, search debounce, flash autohide.
 */
'use strict';

/* ============================================================
   Password visibility toggle (login and add/edit user forms)
   ============================================================ */
function togglePassword(fieldId) {
    // Find the password input and its matching eye icon by ID.
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById('eye-icon-' + fieldId);
    if (!field) return;

    // Toggle the input type between hidden password and visible text.
    const isHidden = field.type === 'password';
    field.type = isHidden ? 'text' : 'password';

    // Swap the eye icon so it matches the current visibility state.
    if (icon) {
        icon.innerHTML = isHidden
            ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
    }
}

/* ============================================================
   Confirm-before-submit for dangerous actions
   ============================================================ */
function confirmAction(message, formEl) {
    // Submit the supplied form only after the user confirms the action.
    if (window.confirm(message)) {
        formEl.submit();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    /* Attach confirm dialogs to any form with data-confirm attribute */
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function handleSubmit(e) {
            // Stop the normal submit so the confirmation can run first.
            e.preventDefault();
            if (window.confirm(form.dataset.confirm)) {
                // Remove this listener before resubmitting to avoid an infinite loop.
                form.removeEventListener('submit', handleSubmit);
                form.submit();
            }
        });
    });

    /* ============================================================
       Password strength meter
       ============================================================ */
    const passwordInput = document.getElementById('password');
    const strengthFill  = document.getElementById('strength-fill');
    const strengthLabel = document.getElementById('strength-label');

    if (passwordInput && strengthFill && strengthLabel) {
        passwordInput.addEventListener('input', function () {
            // Calculate a simple score from length, uppercase, lowercase, and digits.
            const val   = this.value;
            const score = calcStrength(val);

            // Map each score to the bar width, color, and label shown to the user.
            const levels = [
                { width: '0%',   color: '#e2e8f0', text: '' },
                { width: '25%',  color: '#dc2626', text: 'Weak' },
                { width: '50%',  color: '#d97706', text: 'Fair' },
                { width: '75%',  color: '#0284c7', text: 'Good' },
                { width: '100%', color: '#16a34a', text: 'Strong' },
            ];

            const level = levels[score];
            strengthFill.style.width       = level.width;
            strengthFill.style.background  = level.color;
            strengthLabel.textContent      = level.text;
            strengthLabel.style.color      = level.color;
        });
    }

    function calcStrength(password) {
        // Return 0 for empty input so the meter stays hidden/neutral.
        if (!password) return 0;

        // Award one point for each required password rule that is satisfied.
        let score = 0;
        if (password.length >= 8)          score++;
        if (/[A-Z]/.test(password))        score++;
        if (/[a-z]/.test(password))        score++;
        if (/[0-9]/.test(password))        score++;
        return Math.min(score, 4);
    }

    /* ============================================================
       Live search / filter — debounced auto-submit
       ============================================================ */
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        let timer;
        searchInput.addEventListener('input', function () {
            // Wait until typing pauses before submitting the search form.
            clearTimeout(timer);
            timer = setTimeout(function () {
                const form = searchInput.closest('form');
                if (form) form.submit();
            }, 500);
        });
    }

    /* Auto-submit filter dropdowns immediately */
    document.querySelectorAll('.filter-select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            // Submit the surrounding filter form when a dropdown value changes.
            const form = sel.closest('form');
            if (form) form.submit();
        });
    });

    /* ============================================================
       Flash message auto-dismiss
       ============================================================ */
    document.querySelectorAll('.alert[data-autohide]').forEach(function (el) {
        // Use the element delay if present, otherwise default to four seconds.
        const delay = parseInt(el.dataset.autohide) || 4000;
        setTimeout(function () {
            // Fade out first, then remove the element from the page.
            el.style.transition = 'opacity .4s';
            el.style.opacity    = '0';
            setTimeout(function () { el.remove(); }, 420);
        }, delay);
    });

    /* ============================================================
       Table row checkbox: select all
       ============================================================ */
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            // Keep every row checkbox in sync with the master checkbox.
            document.querySelectorAll('.row-check').forEach(function (cb) {
                cb.checked = selectAll.checked;
            });
        });
    }
});