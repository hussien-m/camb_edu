<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1e3a8a">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>@yield('title') - Student Portal</title>

    <!-- Favicon -->
    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @endif

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Student Layout Styles -->
    @vite('resources/css/student-layout.css')

    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="site-logo">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name', 'Cambridge College') }}">
                @else
                    <span class="site-logo-text">{{ substr(setting('site_name', 'CC'), 0, 1) }}</span>
                @endif
            </div>
            <h4>{{ setting('site_name', 'Cambridge College') }}</h4>
            <p>Student Portal</p>
        </div>

        <div class="sidebar-menu">
            <div class="menu-section-title">Main Menu</div>
            <a href="{{ route('student.dashboard') }}" class="menu-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('student.exams.index') }}" class="menu-item {{ request()->routeIs('student.exams.index') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i>
                <span>All Exams</span>
            </a>
            <a href="{{ route('student.courses.index') }}" class="menu-item {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i>
                <span>My Courses</span>
            </a>
            <a href="{{ route('student.courses.content') }}" class="menu-item {{ request()->routeIs('student.courses.content') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span>Course Content</span>
            </a>
            <a href="{{ route('student.exams.calendar') }}" class="menu-item {{ request()->routeIs('student.exams.calendar') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Exam Calendar</span>
            </a>
            <a href="{{ route('student.certificates.index') }}" class="menu-item {{ request()->routeIs('student.certificates.*') ? 'active' : '' }}">
                <i class="fas fa-award"></i>
                <span>Certificates</span>
            </a>

            <div class="menu-section-title">Account</div>
            <a href="{{ route('student.profile') }}" class="menu-item {{ request()->routeIs('student.profile') ? 'active' : '' }}">
                <i class="fas fa-user-circle"></i>
                <span>My Profile</span>
            </a>
            <a href="{{ route('home') }}" class="menu-item" target="_blank">
                <i class="fas fa-globe"></i>
                <span>Main Website</span>
            </a>
            <form method="POST" action="{{ route('student.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="menu-item w-100 text-start border-0 bg-transparent" style="cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <h2>@yield('page-title')</h2>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown exam-notification">
                    <button class="btn btn-light position-relative exam-bell-btn" type="button" id="examNotificationDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(!empty($examNotifications) && $examNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $examNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-0 exam-notification-menu" aria-labelledby="examNotificationDropdown">
                        <li class="dropdown-header px-3 py-2">Exam Notifications</li>
                        @if(!empty($examNotifications) && $examNotifications->count() > 0)
                            @foreach($examNotifications as $notification)
                                @php $exam = $notification['exam']; @endphp
                                <li class="px-3 py-2 border-top exam-notification-item">
                                    <div class="small fw-bold mb-1">{{ $exam->title }}</div>
                                    <div class="text-muted small mb-2">{{ $exam->course->title }}</div>
                                    <a href="{{ route('student.exams.show', $exam) }}" class="btn btn-sm btn-primary w-100">
                                        Go to Exam
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li class="text-muted small px-3 py-3">No new exam notifications.</li>
                        @endif
                    </ul>
                </div>
                <div class="user-info">
                <div class="user-avatar">
                    @if(auth()->guard('student')->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->guard('student')->user()->profile_photo) }}" alt="Profile">
                    @else
                        {{ strtoupper(substr(auth()->guard('student')->user()->first_name, 0, 1)) }}
                    @endif
                </div>
                <div class="user-details">
                    <span class="user-name">{{ auth()->guard('student')->user()->full_name }}</span>
                    <span class="user-role">Student</span>
                </div>
                </div>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Student Layout Scripts -->
    @vite('resources/js/student-layout.js')

    @stack('scripts')

@push('styles')
<style>
    .exam-notification .exam-bell-btn {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }
    .exam-notification .exam-bell-btn:hover {
        background: #f8fafc;
    }
    .exam-notification-menu {
        min-width: 380px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18);
        z-index: 2000;
    }
    .exam-notification .dropdown-menu {
        z-index: 2000;
    }
    .top-header {
        position: relative;
        z-index: 2100;
    }
    .exam-notification-item {
        background: #fff;
    }
    .exam-notification-item:hover {
        background: #f8fafc;
    }
</style>
@endpush
</body>
</html>
