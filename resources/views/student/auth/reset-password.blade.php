@extends('frontend.layouts.app')

@section('title', 'Reset Password')

@push('styles')
    @vite('resources/css/student-auth.css')
@endpush

@section('content')
<section class="login-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="login-card">
                    <div class="login-header">
                        <i class="fas fa-lock-open fa-4x mb-3"></i>
                        <h2>Reset Password</h2>
                        <p class="mb-0">Create a new password for your account.</p>
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

                        <form method="POST" action="{{ route('student.password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-4">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ $email ?? old('email') }}" required readonly>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       required autofocus placeholder="Enter new password (min 8 characters)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                       required placeholder="Confirm your new password">
                            </div>

                            <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                <i class="fas fa-save me-2"></i> Reset Password
                            </button>

                            <div class="text-center">
                                <a href="{{ route('student.login') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
