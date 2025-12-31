@extends('frontend.layouts.app')

@section('title', 'Student Registration')

@push('styles')
    @vite('resources/css/student-auth.css')
    <style>
        .country-select-wrapper {
            position: relative;
        }
        
        .country-search-input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background: white;
        }
        
        .country-search-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        .country-search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
        }
        
        .country-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 10px 10px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .country-dropdown.show {
            display: block;
        }
        
        .country-option {
            padding: 12px 15px;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .country-option:hover {
            background: #f3f4f6;
        }
        
        .country-option.selected {
            background: #eff6ff;
            color: #1e3a8a;
            font-weight: 600;
        }
        
        .country-flag {
            font-size: 20px;
            width: 24px;
            text-align: center;
        }
        
        .country-code {
            color: #6b7280;
            font-size: 13px;
            font-weight: normal;
        }
        
        .auto-detected-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 15px;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 500;
        }
        
        .form-group-enhanced {
            position: relative;
        }
        
        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            z-index: 1;
        }
        
        .form-control-with-icon {
            padding-left: 45px;
        }
        
        .register-card {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .form-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e3a8a;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }
        
        .password-strength-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .password-strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s;
            border-radius: 2px;
        }
        
        .password-strength-weak { background: #ef4444; }
        .password-strength-medium { background: #f59e0b; }
        .password-strength-strong { background: #10b981; }
    </style>
@endpush

@section('content')
<section class="register-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                <div class="register-card">
                    <div class="register-header">
                        <div class="icon">👨‍🎓</div>
                        <h2>Student Registration</h2>
                        <p class="mb-0">Join us today and start your learning journey</p>
                    </div>

                    <div class="register-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('student.register') }}" id="student-register-form">
                            @csrf

                            <!-- Honeypot Fields (hidden from real users, visible to bots) -->
                            <input type="text" name="website_url" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
                            <input type="text" name="phone_number_confirm" value="" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
                            <input type="hidden" name="country_code" id="country_code" value="{{ old('country_code') }}">

                            <!-- Personal Information Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-user me-2"></i>Personal Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-user me-1"></i>First Name <span class="required">*</span>
                                        </label>
                                        <div class="form-group-enhanced">
                                            <i class="fas fa-user form-icon"></i>
                                            <input type="text" name="first_name" class="form-control form-control-with-icon @error('first_name') is-invalid @enderror"
                                                   value="{{ old('first_name') }}" required placeholder="Enter first name">
                                        </div>
                                        @error('first_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-user me-1"></i>Last Name <span class="required">*</span>
                                        </label>
                                        <div class="form-group-enhanced">
                                            <i class="fas fa-user form-icon"></i>
                                            <input type="text" name="last_name" class="form-control form-control-with-icon @error('last_name') is-invalid @enderror"
                                                   value="{{ old('last_name') }}" required placeholder="Enter last name">
                                        </div>
                                        @error('last_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-calendar me-1"></i>Date of Birth
                                        </label>
                                        <div class="form-group-enhanced">
                                            <i class="fas fa-calendar form-icon"></i>
                                            <input type="date" name="date_of_birth" class="form-control form-control-with-icon @error('date_of_birth') is-invalid @enderror"
                                                   value="{{ old('date_of_birth') }}">
                                        </div>
                                        @error('date_of_birth')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-globe me-1"></i>Country
                                        </label>
                                        <div class="country-select-wrapper">
                                            <input type="text" id="country_search" class="country-search-input" 
                                                   placeholder="Search or select country..." 
                                                   autocomplete="off"
                                                   value="{{ old('country_name') }}">
                                            <i class="fas fa-search country-search-icon"></i>
                                            <div class="country-dropdown" id="country_dropdown"></div>
                                        </div>
                                        <div id="auto_detected_badge" class="auto-detected-badge" style="display: none;">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>Auto-detected from your location</span>
                                        </div>
                                        @error('country')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-envelope me-2"></i>Contact Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-envelope me-1"></i>Email Address <span class="required">*</span>
                                        </label>
                                        <div class="form-group-enhanced">
                                            <i class="fas fa-envelope form-icon"></i>
                                            <input type="email" name="email" class="form-control form-control-with-icon @error('email') is-invalid @enderror"
                                                   value="{{ old('email') }}" required placeholder="your.email@example.com">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-phone me-1"></i>Phone Number
                                        </label>
                                        <div class="phone-input-wrapper">
                                            <div class="phone-code-display" id="phone_code_display">+1</div>
                                            <input type="text" name="phone" id="phone_input" class="form-control phone-input @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone') }}" placeholder="1234567890" maxlength="15">
                                        </div>
                                        <small class="text-muted" id="phone_hint">Enter phone number without country code</small>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Security Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="fas fa-lock me-2"></i>Security
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-lock me-1"></i>Password <span class="required">*</span>
                                        </label>
                                        <div class="form-group-enhanced">
                                            <i class="fas fa-lock form-icon"></i>
                                            <input type="password" name="password" id="password" class="form-control form-control-with-icon @error('password') is-invalid @enderror"
                                                   required placeholder="Create a strong password">
                                        </div>
                                        <div class="password-strength">
                                            <div class="password-strength-bar">
                                                <div class="password-strength-fill" id="password_strength_fill"></div>
                                            </div>
                                            <small id="password_strength_text" class="text-muted"></small>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="fas fa-lock me-1"></i>Confirm Password <span class="required">*</span>
                                        </label>
                                        <div class="form-group-enhanced">
                                            <i class="fas fa-lock form-icon"></i>
                                            <input type="password" name="password_confirmation" class="form-control form-control-with-icon"
                                                   required placeholder="Confirm your password">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-register w-100" id="register-submit-btn">
                                    <i class="fas fa-user-plus me-2"></i> Create Account
                                </button>
                            </div>

                            <div class="col-12 text-center mt-3">
                                <p class="mb-0 text-muted">Already have an account?
                                    <a href="{{ route('student.login') }}" class="fw-bold">Login to your account</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Countries data from PHP
const countries = @json($countries ?? []);
const phoneCodes = @json($phoneCodes ?? []);
let selectedCountryCode = null;
let autoDetected = false;

// Initialize country dropdown
function initCountryDropdown() {
    const searchInput = document.getElementById('country_search');
    const dropdown = document.getElementById('country_dropdown');
    const countryCodeInput = document.getElementById('country_code');
    
    // Render all countries
    function renderCountries(filter = '') {
        dropdown.innerHTML = '';
        const filterLower = filter.toLowerCase();
        let count = 0;
        const maxResults = 50;
        
        for (const [code, name] of Object.entries(countries)) {
            if (filter && !name.toLowerCase().includes(filterLower) && !code.toLowerCase().includes(filterLower)) {
                continue;
            }
            
            if (count >= maxResults) break;
            
            const option = document.createElement('div');
            option.className = 'country-option';
            option.dataset.code = code;
            option.dataset.name = name;
            
            // Get flag emoji (simple mapping for common countries)
            const flag = getCountryFlag(code);
            
            option.innerHTML = `
                <span class="country-flag">${flag}</span>
                <span>${name}</span>
                <span class="country-code ms-auto">${code}</span>
            `;
            
            option.addEventListener('click', function() {
                selectCountry(code, name, false);
            });
            
            dropdown.appendChild(option);
            count++;
        }
        
        if (count === 0) {
            dropdown.innerHTML = '<div class="country-option" style="cursor: default; color: #6b7280;">No countries found</div>';
        }
    }
    
    // Show dropdown
    searchInput.addEventListener('focus', function() {
        renderCountries();
        dropdown.classList.add('show');
    });
    
    // Filter on input
    searchInput.addEventListener('input', function() {
        renderCountries(this.value);
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    // Select country
    function selectCountry(code, name, isAutoDetected = false) {
        selectedCountryCode = code;
        searchInput.value = name;
        countryCodeInput.value = code;
        dropdown.classList.remove('show');
        autoDetected = isAutoDetected;
        
        // Update phone country code
        updatePhoneCountryCode(code);
        
        // Show/hide auto-detected badge
        const badge = document.getElementById('auto_detected_badge');
        if (isAutoDetected) {
            badge.style.display = 'inline-flex';
        } else {
            badge.style.display = 'none';
        }
        
        // Highlight selected option
        document.querySelectorAll('.country-option').forEach(opt => {
            opt.classList.remove('selected');
            if (opt.dataset.code === code) {
                opt.classList.add('selected');
            }
        });
    }
    
    // Update phone country code display
    function updatePhoneCountryCode(countryCode) {
        const phoneCodeDisplay = document.getElementById('phone_code_display');
        const phoneInput = document.getElementById('phone_input');
        
        if (phoneCodes[countryCode]) {
            phoneCodeDisplay.textContent = phoneCodes[countryCode];
            phoneCodeDisplay.style.display = 'flex';
        } else {
            phoneCodeDisplay.textContent = '+1';
            phoneCodeDisplay.style.display = 'flex';
        }
    }
    
    // Auto-detect country from IP
    async function detectCountryFromIP() {
        try {
            const response = await fetch('https://ipapi.co/json/');
            const data = await response.json();
            
            if (data.country_code && countries[data.country_code]) {
                selectCountry(data.country_code, countries[data.country_code], true);
                // Phone code will be updated automatically in selectCountry function
            }
        } catch (error) {
            console.log('Could not detect country from IP:', error);
        }
    }
    
    // Auto-detect on page load if no country is selected
    if (!countryCodeInput.value) {
        detectCountryFromIP();
    } else {
        // If old value exists, set it
        const oldCode = countryCodeInput.value;
        if (countries[oldCode]) {
            searchInput.value = countries[oldCode];
            selectedCountryCode = oldCode;
            updatePhoneCountryCode(oldCode);
        }
    }
    
    // Initialize phone code on page load
    if (selectedCountryCode) {
        updatePhoneCountryCode(selectedCountryCode);
    } else {
        // Default to US (+1) if no country selected
        updatePhoneCountryCode('US');
    }
}

// Get country flag emoji
function getCountryFlag(code) {
    // Convert country code to flag emoji
    const codePoints = code
        .toUpperCase()
        .split('')
        .map(char => 127397 + char.charCodeAt());
    return String.fromCodePoint(...codePoints);
}

// Password strength checker
function initPasswordStrength() {
    const passwordInput = document.getElementById('password');
    const strengthFill = document.getElementById('password_strength_fill');
    const strengthText = document.getElementById('password_strength_text');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let strengthLabel = '';
        let strengthClass = '';
        
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/\d/.test(password)) strength++;
        if (/[^a-zA-Z\d]/.test(password)) strength++;
        
        if (strength <= 2) {
            strengthLabel = 'Weak';
            strengthClass = 'password-strength-weak';
        } else if (strength <= 3) {
            strengthLabel = 'Medium';
            strengthClass = 'password-strength-medium';
        } else {
            strengthLabel = 'Strong';
            strengthClass = 'password-strength-strong';
        }
        
        strengthFill.style.width = (strength * 20) + '%';
        strengthFill.className = 'password-strength-fill ' + strengthClass;
        strengthText.textContent = password ? `Password strength: ${strengthLabel}` : '';
    });
}

// Format phone number with country code before submission
function formatPhoneNumber() {
    const phoneInput = document.getElementById('phone_input');
    const phoneCodeDisplay = document.getElementById('phone_code_display');
    const phoneValue = phoneInput.value.trim();
    
    if (phoneValue) {
        // Remove any existing country code
        let cleanPhone = phoneValue.replace(/^\+\d{1,4}\s*/, '');
        
        // Get country code
        const countryCode = phoneCodeDisplay.textContent.trim();
        
        // Combine country code with phone number
        const fullPhone = countryCode + ' ' + cleanPhone;
        phoneInput.value = fullPhone;
    }
}

// Form submission with reCAPTCHA
document.getElementById('student-register-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Format phone number with country code
    formatPhoneNumber();
    
    const submitBtn = document.getElementById('register-submit-btn');
    const originalHtml = submitBtn.innerHTML;
    const form = this;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating Account...';
    
    try {
        // Get reCAPTCHA token if available
        if (typeof executeRecaptcha === 'function') {
            const token = await executeRecaptcha('student_register');
            
            // Add token to form
            let tokenInput = document.querySelector('input[name="recaptcha_token"]');
            if (!tokenInput) {
                tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = 'recaptcha_token';
                form.appendChild(tokenInput);
            }
            tokenInput.value = token;
        }
        
        // Submit the form after token is added
        form.submit();
    } catch (error) {
        console.error('reCAPTCHA error:', error);
        // Re-enable button on error
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHtml;
        alert('Security verification failed. Please refresh the page and try again.');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initCountryDropdown();
    initPasswordStrength();
});
</script>
@endpush
