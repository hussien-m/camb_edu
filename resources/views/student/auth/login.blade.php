@extends('frontend.layouts.app')

@section('title', 'Student Login')

@push('styles')
    @vite('resources/css/student-auth.css')
@endpush

@section('content')
<section class="login-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-card">
                    <div class="login-header">
                        <div class="icon">🎓</div>
                        <h2>Student Login</h2>
                        <p class="mb-0">Welcome back! Please login to your account</p>
                    </div>

                    <div class="login-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('student.login') }}" id="student-login-form">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       required placeholder="Enter your password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>
                                <a href="{{ route('student.password.request') }}">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 mb-3" id="login-submit-btn">
                                <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
                            </button>

                            <div class="text-center">
                                <p class="mb-0 text-muted">Don't have an account?
                                    <a href="{{ route('student.register') }}" class="fw-bold">Create new account</a>
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
document.getElementById('student-login-form').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent default form submission
    
    const submitBtn = document.getElementById('login-submit-btn');
    const originalHtml = submitBtn.innerHTML;
    const form = this;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Verifying...';
    
    try {
        // Get reCAPTCHA token if available
        if (typeof executeRecaptcha === 'function') {
            const token = await executeRecaptcha('student_login');
            
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
</script>
@endpush
