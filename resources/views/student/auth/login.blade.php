@extends('frontend.layouts.app')

@section('title', 'Student Login')

@push('styles')
<style>
    .login-section {
        min-height: 100vh;
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        padding: 120px 0;
        display: flex;
        align-items: center;
        position: relative;
    }
    .login-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        pointer-events: none;
    }
    .login-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    .login-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        color: white;
        padding: 50px;
        text-align: center;
    }
    .login-header h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .form-control {
        height: 55px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        padding: 0 20px;
        font-size: 1rem;
    }
    .form-control:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
    }
    .btn-login {
        height: 55px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        border: none;
        transition: all 0.3s;
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.4);
    }
</style>
@endpush

@section('content')
<section class="login-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-graduation-cap fa-4x mb-3"></i>
                        <h2>Student Login</h2>
                        <p class="mb-0">Welcome Back!</p>
                    </div>

                    <div class="p-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('student.login') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>

                            <div class="text-center">
                                <p class="mb-0">Don't have an account?
                                    <a href="{{ route('student.register') }}" class="fw-bold text-primary">Register here</a>
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
