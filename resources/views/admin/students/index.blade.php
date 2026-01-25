@extends('admin.layouts.app')

@section('title', 'Students Management')
@section('page-title', 'Students Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Students</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Number Badge -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-primary d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                <span class="badge badge-light mr-3" style="font-size: 1.5rem; padding: 10px 15px; background: rgba(255,255,255,0.3);">1</span>
                <div class="flex-grow-1">
                    <h4 class="mb-0" style="color: white;"><i class="fas fa-users me-2"></i>Students Management</h4>
                    <small style="color: rgba(255,255,255,0.9);">Comprehensive student management with advanced features</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Students</span>
                    <span class="info-box-number">{{ $stats['total'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Students</span>
                    <span class="info-box-number">{{ $stats['active'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['active'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pending Verification</span>
                    <span class="info-box-number">{{ $stats['pending'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['pending'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-envelope-check"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Verified Students</span>
                    <span class="info-box-number">{{ $stats['verified'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['verified'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['with_enrollments'] ?? 0 }}</h3>
                    <p>With Enrollments</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book-reader"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
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
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['recent_month'] ?? 0 }}</h3>
                    <p>New This Month</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['inactive'] ?? 0 }}</h3>
                    <p>Inactive Students</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-slash"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list me-2"></i>All Students</h3>
            <div class="card-tools">
                <a href="{{ route('admin.export.students') }}" class="btn btn-success btn-sm mr-2">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                <form action="{{ route('admin.students.index') }}" method="GET" class="d-flex gap-2">
                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Search..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <!-- Bulk Actions -->
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk.students') }}" style="display: none;">
                @csrf
                <div class="mb-3">
                    <select name="action" class="form-control form-control-sm d-inline-block" style="width: auto;">
                        <option value="">Select Action</option>
                        <option value="delete">Delete Selected</option>
                        <option value="activate">Activate Selected</option>
                        <option value="pending">Set to Pending</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">Clear</button>
                </div>
                <input type="hidden" name="ids" id="bulkIds">
            </form>
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px" class="text-center">#</th>
                        <th style="width: 30px">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>IP Address</th>
                        <th>Registered</th>
                        <th style="width: 200px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="text-center">
                                <strong class="text-primary">{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</strong>
                            </td>
                            <td>
                                <input type="checkbox" class="row-checkbox" value="{{ $student->id }}">
                            </td>
                            <td>
                                <strong>{{ $student->full_name }}</strong>
                            </td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->phone ?? 'N/A' }}</td>
                            <td>{{ $student->country ?? 'N/A' }}</td>
                            <td>
                                @if($student->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($student->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if($student->last_login_at)
                                    <small>{{ $student->last_login_at->format('M d, Y') }}<br>{{ $student->last_login_at->format('h:i A') }}</small>
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-monospace">{{ $student->last_login_ip ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    @if($student->status === 'pending')
                                        <form action="{{ route('admin.students.update-status', $student->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this student?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">No students found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $students->links('vendor.pagination.adminlte') }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select All checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkActions();
    });

    // Individual checkboxes
    document.querySelectorAll('.row-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    function updateBulkActions() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        const bulkForm = document.getElementById('bulkActionForm');

        if (checked.length > 0) {
            bulkForm.style.display = 'block';
            const ids = Array.from(checked).map(cb => cb.value);
            document.getElementById('bulkIds').value = JSON.stringify(ids);
        } else {
            bulkForm.style.display = 'none';
        }
    }

    function clearSelection() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateBulkActions();
    }

    // Bulk form submission
    document.getElementById('bulkActionForm')?.addEventListener('submit', function(e) {
        const action = this.querySelector('select[name="action"]').value;
        if (!action) {
            e.preventDefault();
            alert('Please select an action');
            return false;
        }
        if (!confirm(`Are you sure you want to ${action} the selected items?`)) {
            e.preventDefault();
            return false;
        }
    });
</script>
@endpush
@endsection
