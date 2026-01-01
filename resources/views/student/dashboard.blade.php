@extends('student.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('scripts')
<script>
    @if(session('success') && str_contains(session('success'), 'verified'))
        Swal.fire({
            icon: 'success',
            title: 'Account Verified Successfully!',
            html: '<p style="color: #4a5568; font-size: 16px; margin: 15px 0;">{{ session("success") }}</p><p style="color: #718096; font-size: 14px; margin-top: 10px;">You can now access all features and courses.</p>',
            confirmButtonText: 'Get Started',
            confirmButtonColor: '#667eea',
            background: '#ffffff',
            color: '#2d3748',
            iconColor: '#10b981',
            width: '500px',
            padding: '2.5rem',
            borderRadius: '20px',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            customClass: {
                confirmButton: 'swal2-confirm-custom',
                title: 'swal2-title-custom',
                htmlContainer: 'swal2-html-custom',
                popup: 'swal2-popup-custom'
            }
        });
    @endif
</script>
<style>
    .swal2-confirm-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border: none !important;
        border-radius: 50px !important;
        padding: 12px 30px !important;
        font-weight: 600 !important;
        font-size: 16px !important;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;
        transition: all 0.3s ease !important;
    }
    .swal2-confirm-custom:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5) !important;
    }
    .swal2-title-custom {
        color: #2d3748 !important;
        font-weight: 700 !important;
        font-size: 28px !important;
    }
    .swal2-html-custom {
        color: #4a5568 !important;
        font-size: 16px !important;
        line-height: 1.6 !important;
    }
    .swal2-popup-custom {
        border-radius: 20px !important;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
    }
    @keyframes animate__fadeInDown {
        from {
            opacity: 0;
            transform: translate3d(0, -100%, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }
    @keyframes animate__fadeOutUp {
        from {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        to {
            opacity: 0;
            transform: translate3d(0, -100%, 0);
        }
    }
    .animate__animated {
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }
    .animate__fadeInDown {
        animation-name: animate__fadeInDown;
    }
    .animate__fadeOutUp {
        animation-name: animate__fadeOutUp;
    }
</style>
@endpush

@push('styles')
<style>
    /* Updated: December 12, 2025 - Enhanced spacing */
    .welcome-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 3rem 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.3);
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
    }
    .stat-card {
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        border: none;
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .info-box {
        background: white;
        border-radius: 12px;
        padding: 1.75rem;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .info-box:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }
    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        padding: 1.1rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        background: white;
        border: 2px solid #e5e7eb;
        color: #334155;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .action-btn:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    .dashboard-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }
    .dashboard-card.transparent {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
    .sidebar-section {
        margin-bottom: 2.5rem;
    }
    .sidebar-section:last-child {
        margin-bottom: 0;
    }
    .alert-box {
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 0;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .alert-box.alert-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #f59e0b;
    }
    .alert-box.alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #dc2626;
    }
    .progress-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.2);
        border: 2px solid rgba(245, 158, 11, 0.2);
    }
    .progress-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.75rem;
        margin: 0 auto 1.25rem;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
    }
    .custom-progress {
        height: 14px;
        background: rgba(255,255,255,0.7);
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
    .custom-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #f59e0b 0%, #d97706 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px;
        transition: width 0.6s ease;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }
    .progress-label {
        font-size: 0.75rem;
        font-weight: bold;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
</style>
@endpush

@section('content')
<div class="row g-4">
    <!-- Welcome Banner -->
    <div class="col-12">
        <div class="welcome-banner position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="mb-3 fw-bold" style="font-size: 2rem;">
                        👋 Welcome back, {{ $student->first_name }}!
                    </h2>
                    <p class="mb-0 lead opacity-90">
                        Ready to continue your learning journey? Let's achieve greatness together at {{ setting('site_name', 'Cambridge College') }}
                    </p>
                </div>
                <div class="col-lg-4 text-end d-none d-lg-block">
                    <i class="fas fa-graduation-cap" style="font-size: 6rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
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

    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3 class="mb-1 fw-bold">
                @if($student->status === 'active')
                    ✓ Active
                @elseif($student->status === 'pending')
                    ⏳ Pending
                @else
                    ⚠ Inactive
                @endif
            </h3>
            <p class="mb-0 opacity-75">Account Status</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-user-check"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $percentage }}%</h3>
            <p class="mb-0 opacity-75">Profile Complete</p>
            <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.3);">
                <div class="progress-bar" style="width: {{ $percentage }}%; background: rgba(255,255,255,0.9);"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $student->last_login_at ? $student->last_login_at->format('M d') : 'Today' }}</h3>
            <p class="mb-0 opacity-75">Last Login</p>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); color: white;">
            <div class="stat-icon" style="background: rgba(255,255,255,0.2);">
                <i class="fas fa-star"></i>
            </div>
            <h3 class="mb-1 fw-bold">{{ $student->created_at->diffForHumans(null, true) }}</h3>
            <p class="mb-0 opacity-75">Member Since</p>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="col-lg-8">
        <div class="dashboard-card transparent">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-circle me-3">
                    <i class="fas fa-id-card"></i>
                </div>
                <h5 class="mb-0 fw-bold">Personal Information</h5>
            </div>
            <div class="row g-5">
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="fas fa-user fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Full Name</small>
                            <strong>{{ $student->full_name }}</strong>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="fas fa-envelope fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Email Address</small>
                            <strong class="text-break">{{ $student->email }}</strong>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="fas fa-phone fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Phone Number</small>
                            <strong>{{ $student->phone ?? 'Not specified' }}</strong>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="fas fa-calendar fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Date of Birth</small>
                            <strong>{{ $student->date_of_birth ? $student->date_of_birth->format('M d, Y') : 'Not specified' }}</strong>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="fas fa-globe fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Country</small>
                            <strong>{{ $student->country ?? 'Not specified' }}</strong>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <i class="fas fa-network-wired fa-2x text-primary me-3"></i>
                        <div class="flex-grow-1">
                            <small class="text-muted d-block">Last IP Address</small>
                            <strong class="text-monospace small">{{ $student->last_login_ip ?? 'N/A' }}</strong>
                        </div>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="sidebar-section">
            <div class="dashboard-card">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-circle me-3">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Quick Actions</h5>
                </div>
                <div class="d-grid gap-3">
                    <a href="{{ route('student.profile') }}" class="action-btn">
                        <i class="fas fa-user-edit me-2"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a href="{{ route('courses.index') }}" class="action-btn">
                        <i class="fas fa-book me-2"></i>
                        <span>Browse Courses</span>
                    </a>
                    <a href="{{ route('home') }}#contact" class="action-btn">
                        <i class="fas fa-envelope me-2"></i>
                        <span>Contact Us</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Account Status Alert -->
        <div class="sidebar-section">
            @if($student->status === 'pending')
                <div class="alert-box alert-warning">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-2 fw-bold">⏳ Pending Approval</h6>
                            <p class="mb-0 small">Your account is pending administrative approval. You will be notified upon activation.</p>
                        </div>
                    </div>
                </div>
            @elseif($student->status === 'inactive')
                <div class="alert-box alert-danger">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-ban fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-2 fw-bold">⚠ Account Inactive</h6>
                            <p class="mb-0 small">Your account is inactive. Please contact administration for more information.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="sidebar-section">
            @if($percentage < 100)
                <div class="progress-card">
                    <div class="text-center">
                        <div class="progress-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h6 class="mb-3 fw-bold">Complete Your Profile</h6>
                        <div class="custom-progress mb-3">
                            <div class="custom-progress-bar" style="width: {{ $percentage }}%">
                                <span class="progress-label">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <p class="small text-muted mb-3">{{ $completedFields }}/{{ $totalFields }} fields completed</p>
                        <a href="{{ route('student.profile') }}" class="action-btn w-100">
                            <i class="fas fa-edit me-2"></i>
                            <span>Complete Now</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
