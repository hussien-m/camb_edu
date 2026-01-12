@extends('frontend.layouts.app')

@section('title', 'Student Registration')

@push('styles')
    @vite('resources/css/student-auth.css')
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

// Form submission
document.getElementById('student-register-form').addEventListener('submit', function(e) {
    // Format phone number with country code
    formatPhoneNumber();
    
    // Ensure honeypot fields are empty (important for spam protection)
    const websiteUrlField = document.querySelector('input[name="website_url"]');
    const phoneConfirmField = document.querySelector('input[name="phone_number_confirm"]');
    
    if (websiteUrlField) {
        websiteUrlField.value = '';
    }
    if (phoneConfirmField) {
        phoneConfirmField.value = '';
    }
    
    const submitBtn = document.getElementById('register-submit-btn');
    const form = this;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating Account...';
    
    // Allow form to submit normally (don't prevent default)
    // Form will submit via POST to the server
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initCountryDropdown();
    initPasswordStrength();
});
</script>
@endpush
