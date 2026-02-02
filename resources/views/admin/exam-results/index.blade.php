@extends('admin.layouts.app')

@section('title', 'Exam Results Management')
@section('page-title', 'Exam Results Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Exam Results</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Number Badge -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-warning d-flex align-items-center" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none;">
                <span class="badge badge-light mr-3" style="font-size: 1.5rem; padding: 10px 15px; background: rgba(255,255,255,0.3);">2</span>
                <div class="flex-grow-1">
                    <h4 class="mb-0" style="color: white;"><i class="fas fa-chart-bar me-2"></i>Exam Results Management</h4>
                    <small style="color: rgba(255,255,255,0.9);">View, manage, and analyze all exam attempts and results</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clipboard-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Attempts</span>
                    <span class="info-box-number">{{ $stats['total'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-check-double"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Completed</span>
                    <span class="info-box-number">{{ $stats['completed'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['completed'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-trophy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Passed</span>
                    <span class="info-box-number">{{ $stats['passed'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['completed'] > 0 ? ($stats['passed'] / $stats['completed'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-certificate"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">With Certificates</span>
                    <span class="info-box-number">{{ $stats['with_certificates'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['completed'] > 0 ? ($stats['with_certificates'] / $stats['completed'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['failed'] ?? 0 }}</h3>
                    <p>Failed Attempts</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($stats['avg_score'] ?? 0, 1) }}%</h3>
                    <p>Average Score</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['today'] ?? 0 }}</h3>
                    <p>Today's Attempts</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['this_month'] ?? 0 }}</h3>
                    <p>This Month</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list me-2"></i>All Exam Results</h3>
        </div>
        <div class="card-body">
            <!-- Advanced AJAX Filters -->
            <div class="card mb-3 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Advanced Filters (AJAX)</h5>
                </div>
                <div class="card-body">
                    <form id="filterForm" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-user me-1"></i>Student Name</label>
                            <input type="text" name="student" id="filter_student" class="form-control"
                                   placeholder="Search by name or email..." value="{{ request('student') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fas fa-clipboard-list me-1"></i>Exam</label>
                            <select name="exam_id" id="filter_exam_id" class="form-control">
                                <option value="">All Exams</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-trophy me-1"></i>Result Status</label>
                            <select name="status" id="filter_status" class="form-control">
                                <option value="">All Results</option>
                                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>✅ Passed</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-check-circle me-1"></i>Attempt Status</label>
                            <select name="attempt_status" id="filter_attempt_status" class="form-control">
                                <option value="">All Attempts</option>
                                <option value="completed">✅ Completed</option>
                                <option value="not_completed">⏳ Not Completed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fas fa-sort me-1"></i>Sort by Date</label>
                            <select name="sort_date" id="filter_sort_date" class="form-control">
                                <option value="desc" {{ request('sort_date', 'desc') === 'desc' ? 'selected' : '' }}>🕐 Newest First</option>
                                <option value="asc" {{ request('sort_date') === 'asc' ? 'selected' : '' }}>🕐 Oldest First</option>
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
                        <div class="col-md-6">
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
                    <hr class="my-3">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <span class="text-muted"><i class="fas fa-certificate me-1"></i>Bulk certificate access:</span>
                        <form action="{{ route('admin.exam-results.enable-certificates') }}" method="POST" class="d-inline-flex align-items-center gap-2"
                              onsubmit="return confirm('Enable certificate access for all students who passed the selected exam?');">
                            @csrf
                            <select name="exam_id" class="form-control form-control-sm exam-select-enable" style="width: auto; min-width: 200px;" required>
                                <option value="">— Select exam —</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-check-double me-1"></i>Enable for passed
                            </button>
                        </form>
                        <form action="{{ route('admin.exam-results.disable-certificates') }}" method="POST" class="d-inline-flex align-items-center gap-2"
                              onsubmit="return confirm('Disable certificate access for ALL attempts of the selected exam?');">
                            @csrf
                            <select name="exam_id" class="form-control form-control-sm exam-select-disable" style="width: auto; min-width: 200px;" required>
                                <option value="">— Select exam —</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-ban me-1"></i>Disable for all
                            </button>
                        </form>
                    </div>
                    <div id="filterResults" class="mt-3"></div>
                </div>
            </div>

            <!-- Results Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px" class="text-center">#</th>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Certificate</th>
                            <th>Access</th>
                            <th>Date</th>
                            <th style="width: 320px">Actions</th>
                        </tr>
                    </thead>
                    @include('admin.exam-results.partials.table')
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer">
                @include('admin.exam-results.partials.pagination')
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    const filterBtn = document.getElementById('filterBtn');
    const resetBtn = document.getElementById('resetBtn');
    const filterLoading = document.getElementById('filterLoading');
    const resultsTableBody = document.getElementById('resultsTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const filterResults = document.getElementById('filterResults');

    // Filter inputs
    const filterInputs = [
        'filter_student',
        'filter_exam_id',
        'filter_status',
        'filter_attempt_status',
        'filter_sort_date',
        'filter_date_from',
        'filter_date_to'
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
        filterLoading.style.display = 'inline-block';
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
        fetch('{{ route("admin.exam-results.filter") }}', {
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
                resultsTableBody.innerHTML = result.html;
                
                // Update pagination
                paginationContainer.innerHTML = result.pagination;
                
                // Show results count
                filterResults.innerHTML = `
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Found <strong>${result.count}</strong> result(s)
                    </div>
                `;

                // Re-attach event listeners for pagination links
                attachPaginationListeners();
            } else {
                filterResults.innerHTML = `
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${result.message || 'Error filtering results'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            filterResults.innerHTML = `
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    An error occurred while filtering. Please try again.
                </div>
            `;
        })
        .finally(() => {
            filterLoading.style.display = 'none';
            filterBtn.disabled = false;
            resetBtn.disabled = false;
        });
    }

    function attachPaginationListeners() {
        // Only pagination links (with page= in href)
        const paginationLinks = paginationContainer.querySelectorAll('a[href*="page="]');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = new URL(this.href, window.location.origin);
                const page = url.searchParams.get('page');
                if (!page) return;

                // Get current filter values
                const formData = new FormData(filterForm);
                const data = {};
                formData.forEach((value, key) => {
                    if (value) {
                        data[key] = value;
                    }
                });
                data.page = page;

                filterLoading.style.display = 'inline-block';
                fetch('{{ route("admin.exam-results.filter") }}', {
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
                        resultsTableBody.innerHTML = result.html;
                        paginationContainer.innerHTML = result.pagination;
                        filterResults.innerHTML = `
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Found <strong>${result.count}</strong> result(s)
                            </div>
                        `;
                        attachPaginationListeners();
                        resultsTableBody.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        filterResults.innerHTML = `<div class="alert alert-danger mb-0">${result.message || 'Error loading page'}</div>`;
                    }
                })
                .catch(err => {
                    console.error('Pagination error:', err);
                    filterResults.innerHTML = '<div class="alert alert-danger mb-0">An error occurred. Please try again.</div>';
                })
                .finally(() => {
                    filterLoading.style.display = 'none';
                });
            });
        });
    }

    // Initial pagination listeners
    attachPaginationListeners();
});
</script>
@endpush
@endsection
