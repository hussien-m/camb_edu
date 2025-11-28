@extends('student.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="dashboard-card" style="background: linear-gradient(135deg, #1e3a8a 0%, #003366 100%); color: white;">
            <h3 class="mb-3">
                <i class="fas fa-hand-sparkles me-2"></i>
                Welcome, {{ $student->first_name }}!
            </h3>
            <p class="mb-0 lead">We wish you an enjoyable and beneficial educational experience at {{ setting('site_name', 'Cambridge College') }}</p>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card text-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
            <i class="fas fa-check-circle fa-3x mb-3" style="opacity: 0.9;"></i>
            <h4 class="mb-1">
                @if($student->status === 'active')
                    Active
                @elseif($student->status === 'pending')
                    Pending
                @else
                    Inactive
                @endif
            </h4>
            <p class="mb-0 small">Account Status</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card text-center" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
            @php
                $completedFields = 0;
                $totalFields = 7;
                if($student->first_name) $completedFields++;
                if($student->last_name) $completedFields++;
                if($student->email) $completedFields++;
                if($student->phone) $completedFields++;
                if($student->date_of_birth) $completedFields++;
                if($student->country) $completedFields++;
                if($student->profile_photo) $completedFields++;
                $percentage = round(($completedFields / $totalFields) * 100);
            @endphp
            <i class="fas fa-user-check fa-3x mb-3" style="opacity: 0.9;"></i>
            <h4 class="mb-1">{{ $percentage }}%</h4>
            <p class="mb-0 small">Profile Complete</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card text-center" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
            <i class="fas fa-calendar-alt fa-3x mb-3" style="opacity: 0.9;"></i>
            <h4 class="mb-1">{{ $student->last_login_at ? $student->last_login_at->format('M d') : 'Today' }}</h4>
            <p class="mb-0 small">Last Login</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card text-center" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
            <i class="fas fa-clock fa-3x mb-3" style="opacity: 0.9;"></i>
            <h4 class="mb-1">{{ $student->created_at->diffForHumans(null, true) }}</h4>
            <p class="mb-0 small">Member Since</p>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-id-card"></i>
                Personal Information
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3" style="background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-user fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Full Name</small>
                            <strong>{{ $student->full_name }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3" style="background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-envelope fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Email Address</small>
                            <strong class="text-break">{{ $student->email }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3" style="background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-phone fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Phone Number</small>
                            <strong>{{ $student->phone ?? 'Not specified' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3" style="background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-calendar fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Date of Birth</small>
                            <strong>{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'Not specified' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3" style="background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-globe fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Country</small>
                            <strong>{{ $student->country ?? 'Not specified' }}</strong>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center p-3" style="background: #f8f9fa; border-radius: 10px;">
                        <i class="fas fa-network-wired fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Last IP Address</small>
                            <strong class="text-monospace small">{{ $student->last_login_ip ?? 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="dashboard-card">
            <div class="card-title">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </div>
            <div class="d-grid gap-2">
                <a href="{{ route('student.profile') }}" class="btn btn-outline-primary">
                    <i class="fas fa-user-edit me-2"></i> Edit Profile
                </a>
                <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-book me-2"></i> Browse Courses
                </a>
                <a href="{{ route('home') }}#contact" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2"></i> Contact Us
                </a>
            </div>
        </div>

        <!-- Account Status Alert -->
        @if($student->status === 'pending')
            <div class="dashboard-card" style="background: #fef3c7; border-left: 4px solid #f59e0b;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-exclamation-triangle fa-2x text-warning me-3"></i>
                    <div>
                        <h6 class="mb-2 fw-bold">Pending Approval</h6>
                        <p class="mb-0 small">Your account is pending administrative approval. You will be notified upon activation.</p>
                    </div>
                </div>
            </div>
        @elseif($student->status === 'inactive')
            <div class="dashboard-card" style="background: #fee2e2; border-left: 4px solid #dc2626;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-ban fa-2x text-danger me-3"></i>
                    <div>
                        <h6 class="mb-2 fw-bold">Account Inactive</h6>
                        <p class="mb-0 small">Your account is inactive. Please contact administration for more information.</p>
                    </div>
                </div>
            </div>
        @endif

        @if($percentage < 100)
            <div class="dashboard-card" style="background: linear-gradient(135deg, #e0e7ff 0%, #dbeafe 100%);">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-2x text-primary mb-3"></i>
                    <h6 class="mb-2">Complete Your Profile</h6>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="small text-muted mb-3">{{ $completedFields }}/{{ $totalFields }} fields completed ({{ $percentage }}%)</p>
                    <a href="{{ route('student.profile') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit me-1"></i> Complete Now
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
