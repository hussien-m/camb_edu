@extends('student.layouts.app')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="dashboard-card">
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
                <div class="text-center mb-4">
                    <div class="mb-3">
                        @if($student->profile_photo)
                            <img src="{{ asset('storage/' . $student->profile_photo) }}"
                                 alt="Profile" class="rounded-circle"
                                 style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #1e3a8a;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 120px; height: 120px; background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%); color: white; font-size: 3rem; font-weight: 700;">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <label for="profile_photo" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-camera me-1"></i> Change Photo
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                        @error('profile_photo')
                            <div class="text-danger small mt-1">{{ $message }}</div>
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
                               value="{{ old('first_name', $student->first_name) }}" required
                               style="height: 50px; border-radius: 10px;">
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
                               value="{{ old('last_name', $student->last_name) }}" required
                               style="height: 50px; border-radius: 10px;">
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
                               value="{{ old('email', $student->email) }}" required
                               style="height: 50px; border-radius: 10px;">
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
                               value="{{ old('phone', $student->phone) }}"
                               style="height: 50px; border-radius: 10px;">
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
                               value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                               style="height: 50px; border-radius: 10px;">
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
                        <select name="country" class="form-control @error('country') is-invalid @enderror"
                                style="height: 50px; border-radius: 10px;">
                            <option value="">Select Country</option>
                            <option value="Libya" {{ old('country', $student->country) == 'Libya' ? 'selected' : '' }}>Libya</option>
                            <option value="Egypt" {{ old('country', $student->country) == 'Egypt' ? 'selected' : '' }}>Egypt</option>
                            <option value="Tunisia" {{ old('country', $student->country) == 'Tunisia' ? 'selected' : '' }}>Tunisia</option>
                            <option value="Algeria" {{ old('country', $student->country) == 'Algeria' ? 'selected' : '' }}>Algeria</option>
                            <option value="Morocco" {{ old('country', $student->country) == 'Morocco' ? 'selected' : '' }}>Morocco</option>
                            <option value="Other" {{ old('country', $student->country) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <!-- Password Section -->
                <h5 class="mb-3">
                    <i class="fas fa-lock text-primary me-2"></i>
                    Change Password
                </h5>
                <p class="text-muted small">Leave the fields empty if you don't want to change your password</p>

                <div class="row g-4">
                    <!-- Current Password -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror"
                               style="height: 50px; border-radius: 10px;">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror"
                               style="height: 50px; border-radius: 10px;">
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control"
                               style="height: 50px; border-radius: 10px;">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-5" style="height: 50px; border-radius: 10px; font-weight: 700;">
                        <i class="fas fa-save me-2"></i> Save Changes
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
