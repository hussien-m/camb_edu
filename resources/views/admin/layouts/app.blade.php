<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | {{ setting('site_name', 'Cambridge College') }} Admin</title>

    @if(setting('site_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @endif

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Admin Custom CSS -->
    <style>
        .brand-link .brand-image {
            max-height: 33px;
            width: auto;
        }
        .brand-text {
            font-weight: 300 !important;
            font-size: 1.1rem;
        }
        /* Loading Spinner */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }
        /* Form Loading State */
        form.processing {
            opacity: 0.7;
            pointer-events: none;
        }
        form.processing button[type="submit"] {
            cursor: not-allowed;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        body.dark-mode .main-header,
        body.dark-mode .main-sidebar,
        body.dark-mode .content-wrapper {
            background-color: #1a1a1a;
            color: #e0e0e0;
        }
        body.dark-mode .card {
            background-color: #2d2d2d;
            border-color: #444;
        }
        body.dark-mode .table {
            color: #e0e0e0;
        }
        body.dark-mode .form-control {
            background-color: #2d2d2d;
            border-color: #444;
            color: #e0e0e0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }
            .card-header .card-tools {
                float: none;
                margin-top: 10px;
            }
            .btn-group {
                display: flex;
                flex-direction: column;
            }
            .btn-group .btn {
                margin-bottom: 5px;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    @include('admin.layouts.navbar')
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    @include('admin.layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Toast Notifications will be shown via JavaScript -->

                @yield('content')
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer">
        <strong>{{ setting('footer_text', 'Copyright &copy; ' . date('Y') . ' Cambridge College. All rights reserved.') }}</strong>
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function() {
    // Toast Notifications
    @if(session('success'))
        toastr.success({!! json_encode(session('success')) !!}, 'Success', {
            timeOut: 3000,
            progressBar: true,
            closeButton: true
        });
    @endif

    @if(session('error'))
        toastr.error({!! json_encode(session('error')) !!}, 'Error', {
            timeOut: 5000,
            progressBar: true,
            closeButton: true
        });
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            toastr.error({!! json_encode($error) !!}, 'Validation Error', {
                timeOut: 5000,
                progressBar: true
            });
        @endforeach
    @endif

    // Loading States for Forms
    $('form').on('submit', function() {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"], input[type="submit"]');

        if ($submitBtn.length && !$form.hasClass('no-loading')) {
            $form.addClass('processing');
            const originalText = $submitBtn.html();
            $submitBtn.prop('disabled', true);
            $submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');

            // Restore after 10 seconds (in case of failure)
            setTimeout(function() {
                $form.removeClass('processing');
                $submitBtn.prop('disabled', false);
                $submitBtn.html(originalText);
            }, 10000);
        }
    });

    // Confirmation Dialogs for Delete Actions
    $('form[method="POST"][action*="destroy"], form[method="DELETE"]').on('submit', function(e) {
        if (!$(this).hasClass('no-confirm')) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Confirmation for dangerous actions
    $('.danger-action, .btn-danger').on('click', function(e) {
        const confirmMsg = $(this).data('confirm') || 'Are you sure you want to perform this action?';
        if (!confirm(confirmMsg)) {
            e.preventDefault();
            return false;
        }
    });

    // Dark Mode Toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const isDarkMode = localStorage.getItem('darkMode') === 'true';

    if (isDarkMode) {
        document.body.classList.add('dark-mode');
        if (darkModeIcon) {
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
        }
    }

    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            localStorage.setItem('darkMode', isDark);

            if (darkModeIcon) {
                if (isDark) {
                    darkModeIcon.classList.remove('fa-moon');
                    darkModeIcon.classList.add('fa-sun');
                } else {
                    darkModeIcon.classList.remove('fa-sun');
                    darkModeIcon.classList.add('fa-moon');
                }
            }
        });
    }
});
</script>

@stack('scripts')
</body>
</html>
