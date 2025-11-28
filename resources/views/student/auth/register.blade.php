@extends('frontend.layouts.app')

@section('title', 'Student Registration')

@push('styles')
<style>
    .register-section {
        min-height: 100vh;
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        padding: 80px 0 60px;
        position: relative;
    }
    .register-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        pointer-events: none;
    }
    .register-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        position: relative;
        z-index: 1;
    }
    .register-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        color: white;
        padding: 40px;
        text-align: center;
    }
    .register-header h2 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .form-control {
        height: 50px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        padding: 0 20px;
        font-size: 1rem;
    }
    .form-control:focus {
        border-color: #1e3a8a;
        box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
    }
    .btn-register {
        height: 55px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%);
        border: none;
        transition: all 0.3s;
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.4);
    }
</style>
@endpush

@section('content')
<section class="register-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="register-card">
                    <div class="register-header">
                        <i class="fas fa-user-graduate fa-3x mb-3"></i>
                        <h2>Student Registration</h2>
                        <p class="mb-0">Join Cambridge College Today!</p>
                    </div>

                    <div class="p-5">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('student.register') }}">
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                           value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                           value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                                           value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Country</label>
                                    <select name="country" class="form-control @error('country') is-invalid @enderror">
                                        <option value="">Select Country</option>
                                        <option value="Libya" {{ old('country') == 'Libya' ? 'selected' : '' }}>Libya</option>
                                        <option value="Egypt" {{ old('country') == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                                        <option value="Tunisia" {{ old('country') == 'Tunisia' ? 'selected' : '' }}>Tunisia</option>
                                        <option value="Algeria" {{ old('country') == 'Algeria' ? 'selected' : '' }}>Algeria</option>
                                        <option value="Morocco" {{ old('country') == 'Morocco' ? 'selected' : '' }}>Morocco</option>
                                        <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-register w-100">
                                        <i class="fas fa-user-plus me-2"></i> Register Now
                                    </button>
                                </div>

                                <div class="col-12 text-center mt-3">
                                    <p class="mb-0">Already have an account?
                                        <a href="{{ route('student.login') }}" class="fw-bold text-primary">Login here</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
