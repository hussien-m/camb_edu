@extends('student.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@push('styles')
<style>
    .profile-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 2.5rem;
    }
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2rem;
        margin: -2.5rem -2.5rem 2rem -2.5rem;
        color: white;
    }
    .profile-photo-wrapper {
        position: relative;
        display: inline-block;
    }
    .profile-photo-wrapper img,
    .profile-photo-wrapper > div {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }
    .profile-photo-wrapper:hover img,
    .profile-photo-wrapper:hover > div {
        transform: scale(1.05);
    }
    .form-control {
        height: 55px !important;
        border-radius: 12px !important;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent 0%, #667eea 50%, transparent 100%);
        margin: 2.5rem 0;
    }
    .password-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0e7ff 100%);
        border-radius: 15px;
        padding: 2rem;
        border: 2px solid rgba(102, 126, 234, 0.1);
    }
    .submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        height: 55px !important;
        border-radius: 12px !important;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
        color: white;
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
    }
    .form-label {
        font-weight: 600;
        color: #1e3a8a;
        margin-bottom: 0.75rem;
    }
    .photo-btn {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .photo-btn:hover {
        background: #667eea;
        color: white;
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="profile-container">
            <div class="profile-header">
                <div class="text-center">
                    <h2 class="mb-2 fw-bold">
                        <i class="fas fa-user-circle me-2"></i>My Profile
                    </h2>
                    <p class="mb-0 opacity-90">Manage your personal information and account settings</p>
                </div>
            </div>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card-title">
                <i class="fas fa-user-edit"></i>
                Edit Personal Information
            </div>

            <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Photo -->
                <div class="text-center mb-5">
                    <div class="profile-photo-wrapper mb-4">
                        @if($student->profile_photo)
                            <img src="{{ asset('storage/' . $student->profile_photo) }}"
                                 alt="Profile" class="rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover; border: 5px solid white;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 150px; height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 4rem; font-weight: 700; border: 5px solid white;">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <label for="profile_photo" class="photo-btn">
                            <i class="fas fa-camera me-2"></i>Change Photo
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                        @error('profile_photo')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-4">
                    <!-- First Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user text-primary me-1"></i>
                            First Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $student->first_name) }}" required>
                        @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user text-primary me-1"></i>
                            Last Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                               value="{{ old('last_name', $student->last_name) }}" required>
                        @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-envelope text-primary me-1"></i>
                            Email Address <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $student->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-phone text-primary me-1"></i>
                            Phone Number
                        </label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               value="{{ old('phone', $student->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar text-primary me-1"></i>
                            Date of Birth
                        </label>
                        <input type="date" name="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror"
                               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}">
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Country -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-globe text-primary me-1"></i>
                            Country
                        </label>
                        <select name="country" class="form-control @error('country') is-invalid @enderror">
                            <option value="">Select Country</option>
                            @foreach($countries as $code => $name)
                                <option value="{{ $name }}" {{ old('country', $student->country) == $name ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="section-divider">

                <!-- Password Section -->
                <div class="password-section">
                    <h5 class="mb-3 fw-bold" style="color: #1e3a8a;">
                        <i class="fas fa-lock me-2"></i>Change Password
                    </h5>
                    <p class="text-muted small mb-4">Leave the fields empty if you don't want to change your password</p>

                    <div class="row g-4">
                    <!-- Current Password (Optional) -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Current Password <span class="text-muted small">(Optional)</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Leave empty to skip verification">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control">
                    </div>
                </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-5">
                    <button type="submit" class="submit-btn px-5">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/profile.js')
@endpush
@endsection
