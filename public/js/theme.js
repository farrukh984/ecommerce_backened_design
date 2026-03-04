/**
 * Global Theme Manager
 * Handles dark/light mode toggle with localStorage persistence
 */
(function() {
    'use strict';

    const STORAGE_KEY = 'site_theme';

    // Get saved theme or default to light
    function getSavedTheme() {
        return localStorage.getItem(STORAGE_KEY) || 'light';
    }

    // Apply theme to document
    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        updateToggleIcons(theme);
    }

    // Update all toggle button icons on the page
    function updateToggleIcons(theme) {
        document.querySelectorAll('.theme-toggle-icon').forEach(function(icon) {
            icon.className = theme === 'dark'
                ? 'fa-solid fa-sun theme-toggle-icon'
                : 'fa-solid fa-moon theme-toggle-icon';
        });
    }

    // Toggle between light and dark
    function toggleTheme() {
        var current = document.documentElement.getAttribute('data-theme');
        applyTheme(current === 'dark' ? 'light' : 'dark');
    }

    // Initialize on page load
    applyTheme(getSavedTheme());

    // Expose globally
    window.toggleTheme = toggleTheme;
    window.applyTheme  = applyTheme;

    // Auto-bind all .theme-toggle buttons
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.theme-toggle').forEach(function(btn) {
            btn.addEventListener('click', toggleTheme);
        });
        updateToggleIcons(getSavedTheme());
    });
})();
