@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Number Badge -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success d-flex align-items-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
                <span class="badge badge-light mr-3" style="font-size: 1.5rem; padding: 10px 15px; background: rgba(255,255,255,0.3);">3</span>
                <div class="flex-grow-1">
                    <h4 class="mb-0" style="color: white;"><i class="fas fa-clipboard-list me-2"></i>Exams Management</h4>
                    <small style="color: rgba(255,255,255,0.9);">Create, manage, and monitor all course exams</small>
                </div>
                <a href="{{ route('admin.exams.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i>Create New Exam
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-success">
                <span class="info-box-icon"><i class="fas fa-clipboard-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Exams</span>
                    <span class="info-box-number">{{ $stats['total'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Exams</span>
                    <span class="info-box-number">{{ $stats['active'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['active'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-warning">
                <span class="info-box-icon"><i class="fas fa-check-double"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ready Exams</span>
                    <span class="info-box-number">{{ $stats['ready'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['ready'] / $stats['total'] * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="info-box bg-gradient-primary">
                <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Scheduled Exams</span>
                    <span class="info-box-number">{{ $stats['scheduled'] ?? 0 }}</span>
                    <div class="progress">
                        <div class="progress-bar bg-white" style="width: {{ $stats['total'] > 0 ? ($stats['scheduled'] / $stats['total'] * 100) : 0 }}%"></div>
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
                    <h3>{{ $stats['with_questions'] ?? 0 }}</h3>
                    <p>With Questions</p>
                </div>
                <div class="icon">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['total_attempts'] ?? 0 }}</h3>
                    <p>Total Attempts</p>
                </div>
                <div class="icon">
                    <i class="fas fa-pencil-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['completed_attempts'] ?? 0 }}</h3>
                    <p>Completed Attempts</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['inactive'] ?? 0 }}</h3>
                    <p>Inactive Exams</p>
                </div>
                <div class="icon">
                    <i class="fas fa-ban"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-list me-2"></i>All Exams</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <!-- Search and Filters -->
        <div class="card-body border-bottom">
            <form method="GET" action="{{ route('admin.exams.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search exams..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="course_id" class="form-control">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
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
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                @if(request()->hasAny(['search', 'course_id', 'status', 'sort_by']))
                    <div class="col-md-2">
                        <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                @endif
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px" class="text-center">#</th>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Duration</th>
                            <th>Passing Score</th>
                            <th>Max Attempts</th>
                            <th>Total Marks</th>
                            <th>Questions</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td class="text-center">
                                    <strong class="text-primary">{{ ($exams->currentPage() - 1) * $exams->perPage() + $loop->iteration }}</strong>
                                </td>
                                <td>{{ $exam->title }}</td>
                                <td>{{ $exam->course->title }}</td>
                                <td>{{ $exam->duration }} min</td>
                                <td>{{ $exam->passing_score }}%</td>
                                <td>{{ $exam->max_attempts }}</td>
                                <td>{{ $exam->total_marks }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $exam->questions->count() }} Questions</span>
                                </td>
                                <td>
                                    @if($exam->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this exam? All questions will be deleted too.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No exams found. Create your first exam to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $exams->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
