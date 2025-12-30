<!-- Cookie Consent Banner -->
<div id="cookieConsentBanner" class="cookie-consent-banner" style="display: none;">
    <div class="cookie-consent-container">
        <div class="cookie-content">
            <div class="cookie-icon">
                <i class="fas fa-cookie-bite"></i>
            </div>
            <div class="cookie-text">
                <h4>🍪 We Value Your Privacy</h4>
                <p>
                    We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. 
                    By clicking "Accept All", you consent to our use of cookies.
                    <a href="{{ route('page.show', 'cookie-policy') }}" class="cookie-learn-more">Learn More</a>
                </p>
            </div>
        </div>
        <div class="cookie-actions">
            <button onclick="acceptCookies()" class="btn-accept">
                <i class="fas fa-check-circle me-2"></i>Accept All
            </button>
            <button onclick="rejectCookies()" class="btn-reject">
                <i class="fas fa-times-circle me-2"></i>Reject Non-Essential
            </button>
            <button onclick="customizeCookies()" class="btn-customize">
                <i class="fas fa-cog me-2"></i>Customize
            </button>
        </div>
    </div>
</div>

<!-- Cookie Customize Modal -->
<div id="cookieCustomizeModal" class="cookie-modal" style="display: none;">
    <div class="cookie-modal-content">
        <div class="cookie-modal-header">
            <h3><i class="fas fa-cookie me-2"></i>Cookie Settings</h3>
            <button onclick="closeCookieModal()" class="close-modal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="cookie-modal-body">
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="category-info">
                        <h5><i class="fas fa-shield-alt text-success"></i> Essential Cookies</h5>
                        <p>Required for the website to function properly. Cannot be disabled.</p>
                    </div>
                    <label class="cookie-switch">
                        <input type="checkbox" id="essential" checked disabled>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="category-info">
                        <h5><i class="fas fa-user-check text-primary"></i> Functional Cookies</h5>
                        <p>Enable enhanced functionality and personalization (e.g., "Remember Me" feature).</p>
                    </div>
                    <label class="cookie-switch">
                        <input type="checkbox" id="functional" checked>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="category-info">
                        <h5><i class="fas fa-chart-line text-info"></i> Analytics Cookies</h5>
                        <p>Help us understand how visitors interact with our website.</p>
                    </div>
                    <label class="cookie-switch">
                        <input type="checkbox" id="analytics">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <div class="cookie-category">
                <div class="cookie-category-header">
                    <div class="category-info">
                        <h5><i class="fas fa-bullhorn text-warning"></i> Marketing Cookies</h5>
                        <p>Used to track visitors across websites to display relevant advertisements.</p>
                    </div>
                    <label class="cookie-switch">
                        <input type="checkbox" id="marketing">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="cookie-modal-footer">
            <button onclick="saveCustomCookies()" class="btn-save">
                <i class="fas fa-save me-2"></i>Save Preferences
            </button>
            <button onclick="acceptAllModal()" class="btn-accept-all">
                <i class="fas fa-check-double me-2"></i>Accept All
            </button>
        </div>
    </div>
</div>

<style>
/* Cookie Consent Banner */
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: white;
    padding: 20px;
    box-shadow: 0 -5px 30px rgba(0, 0, 0, 0.3);
    z-index: 999999;
    animation: slideUp 0.5s ease-out;
    backdrop-filter: blur(10px);
}

@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.cookie-consent-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
    flex-wrap: wrap;
}

.cookie-content {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    flex: 1;
    min-width: 300px;
}

.cookie-icon {
    font-size: 3rem;
    color: #fbbf24;
    animation: bounce 2s ease-in-out infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.cookie-text h4 {
    margin: 0 0 10px 0;
    font-size: 1.3rem;
    font-weight: 700;
}

.cookie-text p {
    margin: 0;
    font-size: 0.95rem;
    line-height: 1.6;
    opacity: 0.95;
}

.cookie-learn-more {
    color: #fbbf24;
    text-decoration: underline;
    font-weight: 600;
    margin-left: 5px;
}

.cookie-learn-more:hover {
    color: #f59e0b;
}

.cookie-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.cookie-actions button {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.btn-accept {
    background: #10b981;
    color: white;
}

.btn-accept:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
}

.btn-reject {
    background: #ef4444;
    color: white;
}

.btn-reject:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
}

.btn-customize {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-customize:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
}

/* Cookie Modal */
.cookie-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 9999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.cookie-modal-content {
    background: white;
    border-radius: 15px;
    max-width: 700px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.cookie-modal-header {
    padding: 25px;
    background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 15px 15px 0 0;
}

.cookie-modal-header h3 {
    margin: 0;
    font-size: 1.5rem;
}

.close-modal {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.close-modal:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.cookie-modal-body {
    padding: 25px;
}

.cookie-category {
    margin-bottom: 20px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 4px solid #3b82f6;
}

.cookie-category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.category-info h5 {
    margin: 0 0 8px 0;
    color: #1e293b;
    font-size: 1.1rem;
}

.category-info p {
    margin: 0;
    color: #64748b;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Toggle Switch */
.cookie-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 30px;
    flex-shrink: 0;
}

.cookie-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.4s;
    border-radius: 30px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

input:checked + .slider {
    background-color: #10b981;
}

input:checked + .slider:before {
    transform: translateX(30px);
}

input:disabled + .slider {
    opacity: 0.5;
    cursor: not-allowed;
}

.cookie-modal-footer {
    padding: 20px 25px;
    background: #f8fafc;
    border-radius: 0 0 15px 15px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.cookie-modal-footer button {
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-save {
    background: #3b82f6;
    color: white;
}

.btn-save:hover {
    background: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
}

.btn-accept-all {
    background: #10b981;
    color: white;
}

.btn-accept-all:hover {
    background: #059669;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
}

/* Responsive */
@media (max-width: 768px) {
    .cookie-consent-container {
        flex-direction: column;
        gap: 20px;
    }

    .cookie-content {
        flex-direction: column;
        text-align: center;
    }

    .cookie-actions {
        width: 100%;
        justify-content: center;
    }

    .cookie-actions button {
        flex: 1;
        min-width: 0;
    }

    .cookie-text h4 {
        font-size: 1.1rem;
    }

    .cookie-text p {
        font-size: 0.85rem;
    }

    .cookie-modal-content {
        margin: 10px;
    }

    .cookie-category-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .cookie-switch {
        align-self: flex-end;
    }
}
</style>

<script>
// Cookie Consent Functions
function checkCookieConsent() {
    const consent = localStorage.getItem('cookieConsent');
    if (!consent) {
        setTimeout(() => {
            document.getElementById('cookieConsentBanner').style.display = 'block';
        }, 1000);
    }
}

function acceptCookies() {
    const consent = {
        essential: true,
        functional: true,
        analytics: true,
        marketing: true,
        timestamp: new Date().toISOString()
    };
    localStorage.setItem('cookieConsent', JSON.stringify(consent));
    document.getElementById('cookieConsentBanner').style.display = 'none';
    
    // Show success message
    showToast('✅ Cookie preferences saved!', 'success');
}

function rejectCookies() {
    const consent = {
        essential: true,
        functional: false,
        analytics: false,
        marketing: false,
        timestamp: new Date().toISOString()
    };
    localStorage.setItem('cookieConsent', JSON.stringify(consent));
    document.getElementById('cookieConsentBanner').style.display = 'none';
    
    // Show success message
    showToast('✅ Only essential cookies will be used.', 'info');
}

function customizeCookies() {
    document.getElementById('cookieCustomizeModal').style.display = 'flex';
    
    // Load current preferences if any
    const consent = JSON.parse(localStorage.getItem('cookieConsent') || '{}');
    document.getElementById('functional').checked = consent.functional !== false;
    document.getElementById('analytics').checked = consent.analytics || false;
    document.getElementById('marketing').checked = consent.marketing || false;
}

function closeCookieModal() {
    document.getElementById('cookieCustomizeModal').style.display = 'none';
}

function saveCustomCookies() {
    const consent = {
        essential: true,
        functional: document.getElementById('functional').checked,
        analytics: document.getElementById('analytics').checked,
        marketing: document.getElementById('marketing').checked,
        timestamp: new Date().toISOString()
    };
    localStorage.setItem('cookieConsent', JSON.stringify(consent));
    document.getElementById('cookieConsentBanner').style.display = 'none';
    document.getElementById('cookieCustomizeModal').style.display = 'none';
    
    // Show success message
    showToast('✅ Cookie preferences saved!', 'success');
}

function acceptAllModal() {
    acceptCookies();
    closeCookieModal();
}

function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = `cookie-toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#3b82f6'};
        color: white;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        z-index: 99999999;
        animation: slideInRight 0.3s ease;
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkCookieConsent();
});
</script>

