@extends('admin.layouts.app')

@section('title', 'Courses')
@section('page-title', 'Courses Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Courses</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Courses</h3>
            <div class="card-tools">
                <a href="{{ route('admin.export.courses') }}" class="btn btn-success btn-sm mr-2">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Course
                </a>
            </div>
        </div>
        <!-- Search and Filters -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search courses..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category_id" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="level_id" class="form-control">
                        <option value="">All Levels</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-control">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="fee" {{ request('sort_by') == 'fee' ? 'selected' : '' }}>Fee</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                @if(request()->hasAny(['search', 'category_id', 'level_id', 'status', 'sort_by']))
                    <div class="col-12">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body">
            <!-- Bulk Actions -->
            <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk.courses') }}" style="display: none;">
                @csrf
                <div class="mb-3">
                    <select name="action" class="form-control form-control-sm d-inline-block" style="width: auto;">
                        <option value="">Select Action</option>
                        <option value="delete">Delete Selected</option>
                        <option value="activate">Activate Selected</option>
                        <option value="deactivate">Deactivate Selected</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">Clear</button>
                </div>
                <input type="hidden" name="ids" id="bulkIds">
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 30px">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th style="width: 50px">Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Level</th>
                        <th>Duration</th>
                        <th>Fee</th>
                        <th>Status</th>
                        <th style="width: 150px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>
                                <input type="checkbox" class="row-checkbox" value="{{ $course->id }}">
                            </td>
                            <td>
                                @if($course->image)
                                    <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}" class="img-thumbnail" style="max-height: 50px;">
                                @else
                                    <span class="badge badge-secondary">No Image</span>
                                @endif
                            </td>
                            <td>
                                {{ $course->title }}
                                @if($course->is_featured)
                                    <span class="badge badge-warning">Featured</span>
                                @endif
                            </td>
                            <td>{{ $course->category->name ?? 'N/A' }}</td>
                            <td>{{ $course->level->name ?? 'N/A' }}</td>
                            <td>{{ $course->duration ?? 'N/A' }}</td>
                            <td>{{ $course->fee ? '$' . number_format($course->fee, 2) : 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" 
                                            class="btn btn-sm {{ $course->content_disabled ? 'btn-warning' : 'btn-success' }} toggle-content-btn" 
                                            data-course-id="{{ $course->id }}"
                                            data-disabled="{{ $course->content_disabled ? '1' : '0' }}"
                                            title="{{ $course->content_disabled ? 'Enable Content' : 'Disable Content' }}">
                                        <i class="fas fa-{{ $course->content_disabled ? 'unlock' : 'lock' }}"></i>
                                    </button>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline">
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
                            <td colspan="8" class="text-center">No courses found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $courses->links('vendor.pagination.adminlte') }}
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

    // Toggle content disabled status
    document.querySelectorAll('.toggle-content-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const courseId = this.dataset.courseId;
            const isDisabled = this.dataset.disabled === '1';
            const btn = this;
            
            // Disable button during request
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/admin/courses/${courseId}/toggle-content-disabled`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
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
                alert('An error occurred while updating. Please check the console for details.');
            })
            .finally(() => {
                btn.disabled = false;
            });
        });
    });
</script>
@endpush
@endsection
