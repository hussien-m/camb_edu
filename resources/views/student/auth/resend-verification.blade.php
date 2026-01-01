@extends('frontend.layouts.app')

@section('title', 'Resend Verification Email')

@section('content')
<div class="verify-email-container">
    <div class="verify-card">
        <!-- Icon -->
        <div class="verify-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="verify-title">Resend Verification Email</h1>

        <!-- Message -->
        <p class="verify-message">
            Enter your email address and we'll send you a new verification link.
        </p>

        <!-- Alerts -->
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {!! session('error') !!}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Resend Form -->
        <form method="POST" action="{{ route('student.verify.resend.email') }}" class="resend-form">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email', $email ?? '') }}" 
                    placeholder="Enter your email address"
                    required
                    autofocus
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-resend">
                <i class="fas fa-paper-plane"></i> Send Verification Link
            </button>
        </form>

        <!-- Back to Login -->
        <div class="verify-footer">
            <a href="{{ route('student.login') }}">
                <i class="fas fa-arrow-left"></i> Back to Login
            </a>
        </div>
    </div>
</div>

<style>
.verify-email-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
}

.verify-card {
    background: white;
    border-radius: 20px;
    padding: 50px 40px;
    max-width: 480px;
    width: 100%;
    text-align: center;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.verify-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 30px;
    animation: pulse 2s infinite;
}

.verify-icon svg {
    width: 50px;
    height: 50px;
    color: white;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.verify-title {
    font-size: 28px;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 15px;
}

.verify-message {
    font-size: 16px;
    color: #4a5568;
    margin-bottom: 30px;
    line-height: 1.6;
}

.form-group {
    margin-bottom: 25px;
    text-align: left;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 16px;
    transition: all 0.3s;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
    border-color: #e53e3e;
}

.invalid-feedback {
    display: block;
    color: #e53e3e;
    font-size: 14px;
    margin-top: 5px;
    text-align: left;
}

.btn-resend {
    width: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 16px 32px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-resend:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

.btn-resend:active {
    transform: translateY(0);
}

.resend-form {
    margin: 30px 0;
}

.verify-footer {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.verify-footer a {
    color: #667eea;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}

.verify-footer a:hover {
    color: #764ba2;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    text-align: left;
}

.alert-danger {
    background: #fed7d7;
    color: #c53030;
    border-left: 4px solid #e53e3e;
}

.alert-success {
    background: #c6f6d5;
    color: #22543d;
    border-left: 4px solid #38a169;
}

@media (max-width: 480px) {
    .verify-card {
        padding: 30px 20px;
    }
    
    .verify-title {
        font-size: 24px;
    }
}
</style>
@endsection

