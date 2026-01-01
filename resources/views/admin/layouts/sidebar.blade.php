<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        @if(setting('site_logo'))
            <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="{{ setting('site_name', 'Cambridge') }} Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @else
            <img src="https://via.placeholder.com/33x33?text=CC" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light">{{ setting('site_name', 'Cambridge') }} Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://ui-avatars.com/api/?name={{ Auth::guard('admin')->user()->name }}&size=160&background=random" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::guard('admin')->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Analytics -->
                <li class="nav-item">
                    <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Analytics</p>
                    </a>
                </li>

                <!-- Courses Management -->
                <li class="nav-item {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.levels.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.levels.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Courses
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Courses</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.levels.index') }}" class="nav-link {{ request()->routeIs('admin.levels.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Levels</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Pages -->
                <li class="nav-item">
                    <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Pages</p>
                    </a>
                </li>

                <!-- Success Stories -->
                <li class="nav-item">
                    <a href="{{ route('admin.stories.index') }}" class="nav-link {{ request()->routeIs('admin.stories.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Success Stories</p>
                    </a>
                </li>

                <!-- Contacts -->
                <li class="nav-item">
                    <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Messages
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span class="badge badge-warning right">{{ $unreadCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <!-- Course Inquiries -->
                <li class="nav-item">
                    <a href="{{ route('admin.inquiries.index') }}" class="nav-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>
                            Course Inquiries
                            @if(isset($newInquiriesCount) && $newInquiriesCount > 0)
                                <span class="badge badge-info right">{{ $newInquiriesCount }}</span>
                            @endif
                        </p>
                    </a>
                </li>

                <!-- Banners -->
                <li class="nav-item">
                    <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-images"></i>
                        <p>Banners</p>
                    </a>
                </li>

                <!-- Features -->
                <li class="nav-item">
                    <a href="{{ route('admin.features.index') }}" class="nav-link {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Features</p>
                    </a>
                </li>

                <!-- Newsletter Subscribers -->
                <li class="nav-item">
                    <a href="{{ route('admin.newsletter.index') }}" class="nav-link {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>Newsletter Subscribers</p>
                    </a>
                </li>

                <!-- Students Management -->
                <li class="nav-item {{ request()->routeIs('admin.students.*') || request()->routeIs('admin.verification-reminders.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.students.*') || request()->routeIs('admin.verification-reminders.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Students
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="badge badge-warning right">{{ $pendingCount }}</span>
                            @endif
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.students.index') }}" class="nav-link {{ request()->routeIs('admin.students.*') && !request()->routeIs('admin.verification-reminders.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Students</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.verification-reminders.index') }}" class="nav-link {{ request()->routeIs('admin.verification-reminders.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Verification Reminders
                                    @if(isset($totalUnverified) && $totalUnverified > 0)
                                        <span class="badge badge-warning right">{{ $totalUnverified }}</span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Exams Management -->
                <li class="nav-item {{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.questions.*') || request()->routeIs('admin.exam-results.*') || request()->routeIs('admin.exam-reminders.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.questions.*') || request()->routeIs('admin.exam-results.*') || request()->routeIs('admin.exam-reminders.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>
                            Exams
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.exams.index') }}" class="nav-link {{ request()->routeIs('admin.exams.*') || request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Exams & Questions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.enrollments.index') }}" class="nav-link {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Student Enrollments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.exam-results.index') }}" class="nav-link {{ request()->routeIs('admin.exam-results.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Exam Results</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.exam-reminders.index') }}" class="nav-link {{ request()->routeIs('admin.exam-reminders.*') ? 'active' : '' }}">
                                <i class="far fa-bell nav-icon"></i>
                                <p>Exam Reminders</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Activity Log -->
                <li class="nav-item">
                    <a href="{{ route('admin.activity-log.index') }}" class="nav-link {{ request()->routeIs('admin.activity-log.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Activity Log</p>
                    </a>
                </li>

                <!-- Divider -->
                <li class="nav-header">SYSTEM</li>

                <!-- Site Settings -->
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Site Settings</p>
                    </a>
                </li>

                <!-- Email Settings -->
                <li class="nav-item">
                    <a href="{{ route('admin.settings.email') }}" class="nav-link {{ request()->routeIs('admin.settings.email') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Email Configuration</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
