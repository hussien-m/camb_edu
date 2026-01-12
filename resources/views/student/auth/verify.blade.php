@extends('frontend.layouts.app')

@section('title', 'Verify Your Email')

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
        <h1 class="verify-title">Verify Your Email</h1>

        <!-- Message -->
        <p class="verify-message">
            We've sent a verification link to<br>
            <strong>{{ Auth::guard('student')->user()->email }}</strong>
        </p>

        <!-- Instructions -->
        <div class="verify-instructions">
            <p>Please check your inbox and click the verification link to activate your account.</p>
            <p class="text-muted">The link will expire in 24 hours.</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> {{ session('info') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Resend Button -->
        <form method="POST" action="{{ route('student.verify.resend') }}" class="resend-form">
            @csrf
            <p class="resend-text">Didn't receive the email?</p>
            <button type="submit" class="btn-resend">
                <i class="fas fa-paper-plane"></i> Resend Verification Email
            </button>
        </form>

        <!-- Tips -->
        <div class="verify-tips">
            <h4>📧 Can't find the email?</h4>
            <ul>
                <li>Check your spam or junk folder</li>
                <li>Make sure your email address is correct</li>
                <li>Wait a few minutes and try again</li>
            </ul>
        </div>

        <!-- Back to Login -->
        <div class="verify-footer">
            <a href="{{ route('student.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-arrow-left"></i> Sign in with a different account
            </a>
            <form id="logout-form" action="{{ route('student.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
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
    margin-bottom: 20px;
    line-height: 1.6;
}

.verify-message strong {
    color: #667eea;
}

.verify-instructions {
    background: #f7fafc;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
}

.verify-instructions p {
    margin: 0 0 10px;
    color: #4a5568;
    font-size: 14px;
}

.verify-instructions p:last-child {
    margin: 0;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
}

.alert-info {
    background: #dbeafe;
    color: #1e40af;
}

.alert-warning {
    background: #fef3c7;
    color: #92400e;
}

.resend-form {
    margin-bottom: 25px;
}

.resend-text {
    color: #718096;
    font-size: 14px;
    margin-bottom: 15px;
}

.btn-resend {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 14px 30px;
    border-radius: 50px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-resend:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.verify-tips {
    background: #fffbeb;
    border: 1px solid #fcd34d;
    border-radius: 12px;
    padding: 20px;
    text-align: left;
    margin-bottom: 25px;
}

.verify-tips h4 {
    color: #92400e;
    font-size: 15px;
    margin: 0 0 12px;
}

.verify-tips ul {
    margin: 0;
    padding-left: 20px;
}

.verify-tips li {
    color: #a16207;
    font-size: 13px;
    margin-bottom: 5px;
}

.verify-footer a {
    color: #667eea;
    text-decoration: none;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s;
}

.verify-footer a:hover {
    color: #764ba2;
}
</style>
@endsection
