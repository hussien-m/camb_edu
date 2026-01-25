@extends('admin.layouts.app')

@section('title', 'Enrollments')
@section('page-title', 'Student Enrollments')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Enrollments</li>
@endsection

@push('styles')
<!-- Select2 for Bootstrap 4 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />

<!-- DataTables for Bootstrap 4 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css" />

<style>
/* Minor customizations to integrate AdminLTE look & feel */
.enrollment-stats .info-box {
    border-radius: 10px;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
}

.student-avatar {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}

.table-responsive {
    margin-bottom: 0;
}

/* status badges */
.badge-status {
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    font-size: 0.75rem;
}

/* improve filters card */
.filters-card .card-body {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: end;
}

.filters-card .form-group {
    margin-bottom: 0;
    min-width: 220px;
    flex: 1;
}

@media (max-width: 768px) {
    .filters-card .card-body {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- HEADER / TITLE -->
        <div class="col-12 mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <span class="badge badge-light mr-3" style="font-size: 1.5rem; padding: 10px 15px; background: rgba(255,255,255,0.3);">4</span>
                        <div class="flex-grow-1">
                            <h3 class="card-title mb-0"><i class="fas fa-book-reader mr-2"></i> Enrollments</h3>
                            <small class="text-muted">Manage and monitor student enrollments with content & exam controls</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- STATS: use AdminLTE info-box -->
                    <div class="row enrollment-stats">
                        <div class="col-lg-3 col-md-6">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Enrollments</span>
                                    <span class="info-box-number">{{ $stats['total'] ?? $enrollments->total() }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-white" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="info-box bg-gradient-success">
                                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Active Enrollments</span>
                                    <span class="info-box-number">{{ $stats['active'] ?? 0 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['active'] / $stats['total'] * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-lock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Content Disabled</span>
                                    <span class="info-box-number">{{ $stats['content_disabled'] ?? 0 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['content_disabled'] / $stats['total'] * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="info-box bg-gradient-danger">
                                <span class="info-box-icon"><i class="fas fa-ban"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Exams Disabled</span>
                                    <span class="info-box-number">{{ $stats['exam_disabled'] ?? 0 }}</span>
                                    <div class="progress">
                                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['exam_disabled'] / $stats['total'] * 100) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats Row -->
                    <div class="row mt-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $stats['content_enabled'] ?? 0 }}</h3>
                                    <p>Content Enabled</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-unlock"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['exam_enabled'] ?? 0 }}</h3>
                                    <p>Exams Enabled</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $stats['recent_week'] ?? 0 }}</h3>
                                    <p>New This Week</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>{{ $stats['completed'] ?? 0 }}</h3>
                                    <p>Completed</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Table -->
    <div class="row">
        <!-- Advanced AJAX Filters -->
        <div class="col-12">
            <div class="card filters-card border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Advanced Filters (AJAX)</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-user me-1"></i>Student Name</label>
                            <input type="text" name="student" id="filter_student" class="form-control" 
                                   placeholder="Search by name or email..." value="{{ request('student') }}">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-layer-group me-1"></i>Level / Stage</label>
                            <select name="level_id" id="filter_level_id" class="form-control">
                                <option value="">All Levels</option>
                                @foreach($levels ?? [] as $level)
                                <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-clipboard-check me-1"></i>Has Exam</label>
                            <select name="has_exam" id="filter_has_exam" class="form-control">
                                <option value="">All</option>
                                <option value="yes">✅ Has Exam</option>
                                <option value="no">❌ No Exam</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-lock me-1"></i>Content Status</label>
                            <select name="content_status" id="filter_content_status" class="form-control">
                                <option value="">All</option>
                                <option value="enabled">✅ Enabled</option>
                                <option value="disabled">🔒 Disabled</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-ban me-1"></i>Exam Status</label>
                            <select name="exam_status" id="filter_exam_status" class="form-control">
                                <option value="">All</option>
                                <option value="enabled">✅ Enabled</option>
                                <option value="disabled">🔒 Disabled</option>
                            </select>
                        </div>

                        <div class="col-md-1">
                            <label class="form-label"><i class="fas fa-sort me-1"></i>Sort Date</label>
                            <select name="sort_date" id="filter_sort_date" class="form-control">
                                <option value="desc" {{ request('sort_date', 'desc') === 'desc' ? 'selected' : '' }}>🕐 Newest</option>
                                <option value="asc" {{ request('sort_date') === 'asc' ? 'selected' : '' }}>🕐 Oldest</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-calendar-alt me-1"></i>Date From</label>
                            <input type="date" name="date_from" id="filter_date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-calendar-check me-1"></i>Date To</label>
                            <input type="date" name="date_to" id="filter_date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-info-circle me-1"></i>Enrollment Status</label>
                            <select name="enrollment_status" id="filter_enrollment_status" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">✅ Active</option>
                                <option value="completed">🏆 Completed</option>
                                <option value="cancelled">❌ Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="button" id="filterBtn" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <button type="button" id="resetBtn" class="btn btn-secondary">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                            <span id="filterLoading" class="ml-2" style="display: none;">
                                <i class="fas fa-spinner fa-spin text-primary"></i> Loading...
                            </span>
                        </div>
                    </form>
                    <div id="filterResults" class="mt-3"></div>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="col-12">
            <div class="card card-primary card-outline table-card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Enrollment List</h3>
                    <div class="card-tools">
                        <span class="text-muted me-3">Showing {{ $enrollments->count() }} of {{ $enrollments->total() }}</span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table id="enrollmentsTable" class="table table-hover table-striped table-valign-middle w-100">
                        <thead class="bg-primary">
                            <tr>
                                <th style="width: 50px" class="text-center">#</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Enrolled Date</th>
                                <th>Exam Status</th>
                                <th style="width:120px">Actions</th>
                            </tr>
                        </thead>
                        @include('admin.enrollments.partials.table')
                    </table>
                </div>

                <div id="paginationContainer">
                    @include('admin.enrollments.partials.pagination')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DataTables + Buttons -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select2 init
    $('.select2-basic').select2({
        theme: 'bootstrap4',
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: 6
    });

    // Auto-submit when selects change (keeps student input manual via button)
    $('#levelSelect, #examSelect').on('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Initialize DataTable
    if (typeof $.fn.DataTable !== 'undefined') {
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#enrollmentsTable')) {
                $('#enrollmentsTable').DataTable().destroy();
            }
            
            $('#enrollmentsTable').DataTable({
                destroy: true,
                paging: false,
                info: false,
                searching: false,
                ordering: true,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: 4 }
                ],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-sm btn-outline-secondary',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-outline-success',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-outline-danger',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-outline-primary',
                        exportOptions: { columns: [0, 1, 2, 3] }
                    }
                ],
                initComplete: function() {
                    $('.dt-buttons').addClass('float-left mt-2').prependTo('#enrollmentsTable_wrapper .row:first');
                }
            });
        }, 200);
    }

    // Toggle content disabled status
    document.querySelectorAll('.toggle-content-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const enrollmentId = this.dataset.enrollmentId;
            const isDisabled = this.dataset.disabled === '1';
            const btn = this;
            
            // Disable button during request
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/enrollments/${enrollmentId}/toggle-content-disabled`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    btn.dataset.disabled = data.content_disabled ? '1' : '0';
                    btn.className = `btn btn-sm ${data.content_disabled ? 'btn-warning' : 'btn-success'} toggle-content-btn`;
                    btn.innerHTML = `<i class="fas fa-${data.content_disabled ? 'unlock' : 'lock'}"></i>`;
                    btn.title = data.content_disabled ? 'Enable Content' : 'Disable Content';
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    } else {
                        alert(data.message);
                    }
                } else {
                    alert(data.message || 'An error occurred while updating');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating');
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });

    // Toggle exam disabled status
    document.querySelectorAll('.toggle-exam-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const enrollmentId = this.dataset.enrollmentId;
            const isDisabled = this.dataset.disabled === '1';
            const btn = this;
            
            // Disable button during request
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/enrollments/${enrollmentId}/toggle-exam-disabled`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    btn.dataset.disabled = data.exam_disabled ? '1' : '0';
                    btn.className = `btn btn-sm ${data.exam_disabled ? 'btn-danger' : 'btn-success'} toggle-exam-btn`;
                    btn.innerHTML = `<i class="fas fa-${data.exam_disabled ? 'ban' : 'check-circle'}"></i>`;
                    btn.title = data.exam_disabled ? 'Enable Exams' : 'Disable Exams';
                    
                    // Show success message
                    if (typeof toastr !== 'undefined') {
                        toastr.success(data.message);
                    } else {
                        alert(data.message);
                    }
                } else {
                    alert(data.message || 'An error occurred while updating');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating');
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });
});

// AJAX Filtering Script
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const filterBtn = document.getElementById('filterBtn');
    const resetBtn = document.getElementById('resetBtn');
    const filterLoading = document.getElementById('filterLoading');
    const enrollmentsTableBody = document.getElementById('enrollmentsTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const filterResults = document.getElementById('filterResults');

    if (!filterForm || !filterBtn) return; // Exit if elements don't exist

    // Filter inputs
    const filterInputs = [
        'filter_student',
        'filter_level_id',
        'filter_has_exam',
        'filter_content_status',
        'filter_exam_status',
        'filter_sort_date',
        'filter_date_from',
        'filter_date_to',
        'filter_enrollment_status'
    ];

    let filterTimeout;

    // Auto-filter on input change (with debounce)
    filterInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function() {
                clearTimeout(filterTimeout);
                filterTimeout = setTimeout(() => {
                    performFilter();
                }, 500);
            });
        }
    });

    // Manual filter button
    filterBtn.addEventListener('click', function() {
        performFilter();
    });

    // Reset button
    resetBtn.addEventListener('click', function() {
        // Clear all filters
        filterInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            }
        });
        performFilter();
    });

    function performFilter() {
        // Show loading
        if (filterLoading) filterLoading.style.display = 'inline-block';
        filterBtn.disabled = true;
        resetBtn.disabled = true;

        // Get form data
        const formData = new FormData(filterForm);
        const data = {};
        formData.forEach((value, key) => {
            if (value) {
                data[key] = value;
            }
        });

        // Make AJAX request
        fetch('{{ route("admin.enrollments.filter") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(result => {
            if (result.success) {
                // Update table body
                if (enrollmentsTableBody) {
                    enrollmentsTableBody.innerHTML = result.html;
                }
                
                // Update pagination
                if (paginationContainer) {
                    paginationContainer.innerHTML = result.pagination;
                }
                
                // Show results count
                if (filterResults) {
                    filterResults.innerHTML = `
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Found <strong>${result.count}</strong> enrollment(s)
                        </div>
                    `;
                }

                // Re-attach event listeners
                attachPaginationListeners();
                attachToggleListeners();
            } else {
                if (filterResults) {
                    filterResults.innerHTML = `
                        <div class="alert alert-danger mb-0">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            ${result.message || 'Error filtering enrollments'}
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            if (filterResults) {
                filterResults.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        An error occurred while filtering. Please try again.
                    </div>
                `;
            }
        })
        .finally(() => {
            if (filterLoading) filterLoading.style.display = 'none';
            filterBtn.disabled = false;
            resetBtn.disabled = false;
        });
    }

    function attachPaginationListeners() {
        const paginationLinks = paginationContainer?.querySelectorAll('a[href]');
        if (paginationLinks) {
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page');
                    
                    const formData = new FormData(filterForm);
                    const data = {};
                    formData.forEach((value, key) => {
                        if (value) data[key] = value;
                    });
                    data.page = page;

                    if (filterLoading) filterLoading.style.display = 'inline-block';
                    fetch('{{ route("admin.enrollments.filter") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            if (enrollmentsTableBody) enrollmentsTableBody.innerHTML = result.html;
                            if (paginationContainer) paginationContainer.innerHTML = result.pagination;
                            attachPaginationListeners();
                            attachToggleListeners();
                            if (enrollmentsTableBody) {
                                enrollmentsTableBody.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        }
                    })
                    .finally(() => {
                        if (filterLoading) filterLoading.style.display = 'none';
                    });
                });
            });
        }
    }

    function attachToggleListeners() {
        // Re-attach toggle content listeners
        document.querySelectorAll('.toggle-content-btn').forEach(btn => {
            if (btn.dataset.listenerAttached) return;
            btn.dataset.listenerAttached = 'true';
            btn.addEventListener('click', function() {
                const enrollmentId = this.dataset.enrollmentId;
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(`/admin/enrollments/${enrollmentId}/toggle-content-disabled`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.dataset.disabled = data.content_disabled ? '1' : '0';
                        btn.className = `btn btn-sm ${data.content_disabled ? 'btn-warning' : 'btn-success'} toggle-content-btn`;
                        btn.innerHTML = `<i class="fas fa-${data.content_disabled ? 'unlock' : 'lock'}"></i>`;
                        btn.title = data.content_disabled ? 'Enable Content' : 'Disable Content';
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message);
                        } else {
                            alert(data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating');
                })
                .finally(() => {
                    btn.disabled = false;
                });
            });
        });

        // Re-attach toggle exam listeners
        document.querySelectorAll('.toggle-exam-btn').forEach(btn => {
            if (btn.dataset.listenerAttached) return;
            btn.dataset.listenerAttached = 'true';
            btn.addEventListener('click', function() {
                const enrollmentId = this.dataset.enrollmentId;
                const btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(`/admin/enrollments/${enrollmentId}/toggle-exam-disabled`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.dataset.disabled = data.exam_disabled ? '1' : '0';
                        btn.className = `btn btn-sm ${data.exam_disabled ? 'btn-danger' : 'btn-success'} toggle-exam-btn`;
                        btn.innerHTML = `<i class="fas fa-${data.exam_disabled ? 'ban' : 'check-circle' }}"></i>`;
                        btn.title = data.exam_disabled ? 'Enable Exams' : 'Disable Exams';
                        if (typeof toastr !== 'undefined') {
                            toastr.success(data.message);
                        } else {
                            alert(data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating');
                })
                .finally(() => {
                    btn.disabled = false;
                });
            });
        });
    }

    // Initial listeners
    attachPaginationListeners();
    attachToggleListeners();
});
</script>
@endpush
