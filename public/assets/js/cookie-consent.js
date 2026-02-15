// Advanced Cookie Consent Manager with Granular Controls
(function () {
    'use strict';

    const CONSENT_KEY = 'cookie_consent_preferences';

    // Cookie categories
    const categories = {
        necessary: true,  // Always enabled
        analytics: false  // User choice
    };

    // Get saved preferences
    function getPreferences() {
        const saved = localStorage.getItem(CONSENT_KEY);
        if (saved) {
            try {
                return JSON.parse(saved);
            } catch (e) {
                return null;
            }
        }
        return null;
    }

    // Save preferences
    function savePreferences(prefs) {
        localStorage.setItem(CONSENT_KEY, JSON.stringify(prefs));
    }

    // Check if user has made a choice
    function hasConsent() {
        return getPreferences() !== null;
    }

    // Load Google Analytics
    function loadGoogleAnalytics() {
        if (window.gtag) {
            return; // Already loaded
        }

        const script = document.createElement('script');
        script.async = true;
        // Updated Google Tag ID for Trovaveterinario
        script.src = 'https://www.googletagmanager.com/gtag/js?id=G-J048HYF8SQ';
        document.head.appendChild(script);

        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        window.gtag = gtag;
        gtag('js', new Date());
        gtag('config', 'G-J048HYF8SQ');
    }

    // Apply consent preferences
    function applyPreferences(prefs) {
        if (prefs.analytics) {
            loadGoogleAnalytics();
        }
    }

    // Accept all cookies
    function acceptAll() {
        const prefs = {
            necessary: true,
            analytics: true
        };
        savePreferences(prefs);
        applyPreferences(prefs);
        hideBanner();
        hideModal();
    }

    // Reject optional cookies
    function rejectAll() {
        const prefs = {
            necessary: true,
            analytics: false
        };
        savePreferences(prefs);
        hideBanner();
        hideModal();
    }

    // Save custom preferences from modal
    function saveCustomPreferences() {
        const analyticsToggle = document.getElementById('cookie-analytics');
        const prefs = {
            necessary: true,
            analytics: analyticsToggle ? analyticsToggle.checked : false
        };
        savePreferences(prefs);
        applyPreferences(prefs);
        hideModal();
        hideBanner();
    }

    // Show/hide banner
    function showBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'block';
        }
    }

    function hideBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'none';
        }
    }

    // Show/hide modal
    function showModal() {
        const modal = document.getElementById('cookie-preferences-modal');
        if (modal) {
            modal.style.display = 'flex';

            // Update toggles based on current prefs
            const prefs = getPreferences() || { necessary: true, analytics: false };
            const analyticsToggle = document.getElementById('cookie-analytics');
            if (analyticsToggle) {
                analyticsToggle.checked = prefs.analytics;
            }
        }
    }

    function hideModal() {
        const modal = document.getElementById('cookie-preferences-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Initialize
    function init() {
        const prefs = getPreferences();

        // If already consented, apply preferences
        if (prefs) {
            applyPreferences(prefs);
            return;
        }

        // Show banner if no consent
        showBanner();

        // Attach event listeners
        const acceptAllBtn = document.getElementById('cookie-accept-all');
        const rejectAllBtn = document.getElementById('cookie-reject-all');
        const manageBtn = document.getElementById('cookie-manage');
        const savePrefsBtn = document.getElementById('cookie-save-preferences');
        const modalCloseBtn = document.getElementById('cookie-modal-close');
        const modalOverlay = document.getElementById('cookie-preferences-modal');

        if (acceptAllBtn) {
            acceptAllBtn.addEventListener('click', acceptAll);
        }

        if (rejectAllBtn) {
            rejectAllBtn.addEventListener('click', rejectAll);
        }

        if (manageBtn) {
            manageBtn.addEventListener('click', showModal);
        }

        if (savePrefsBtn) {
            savePrefsBtn.addEventListener('click', saveCustomPreferences);
        }

        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', hideModal);
        }

        // Close modal when clicking overlay
        if (modalOverlay) {
            modalOverlay.addEventListener('click', function (e) {
                if (e.target === modalOverlay) {
                    hideModal();
                }
            });
        }
    }

    // Expose function to open preferences
    window.openCookiePreferences = function () {
        showModal();
    };

    // Expose revoke function
    window.revokeCookieConsent = function () {
        localStorage.removeItem(CONSENT_KEY);
        location.reload();
    };

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
