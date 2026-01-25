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
            <!-- Filters -->
            <div class="card mb-3">
                <div class="card-body">
                    <form action="{{ route('admin.exam-results.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Student Name</label>
                            <input type="text" name="student" class="form-control"
                                   placeholder="Search by student name..." value="{{ request('student') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Exam</label>
                            <select name="exam_id" class="form-control">
                                <option value="">All Exams</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                        {{ $exam->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <a href="{{ route('admin.exam-results.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                            @if(request('exam_id'))
                                <form action="{{ route('admin.exam-results.enable-certificates') }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Enable certificates for all completed attempts of this exam?');">
                                    @csrf
                                    <input type="hidden" name="exam_id" value="{{ request('exam_id') }}">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-certificate"></i> Enable Certificates for Exam
                                    </button>
                                </form>
                            @endif
                        </div>
                    </form>
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
                    <tbody>
                        @forelse($attempts as $attempt)
                            <tr>
                                <td class="text-center">
                                    <strong class="text-primary">{{ ($attempts->currentPage() - 1) * $attempts->perPage() + $loop->iteration }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $attempt->student->full_name ?? ($attempt->student->first_name . ' ' . $attempt->student->last_name) }}</strong><br>
                                    <small class="text-muted">{{ $attempt->student->email }}</small>
                                </td>
                                <td>
                                    <strong>{{ $attempt->exam->title }}</strong><br>
                                    <small class="text-muted">Attempt #{{ $attempt->attempt_number }}</small>
                                </td>
                                <td>
                                    <strong>{{ $attempt->score }}</strong> / {{ $attempt->exam->total_marks }}
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $attempt->passed ? 'bg-success' : 'bg-danger' }}"
                                             role="progressbar"
                                             style="width: {{ $attempt->percentage }}%;"
                                             aria-valuenow="{{ $attempt->percentage }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            {{ number_format($attempt->percentage, 2) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($attempt->passed)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Passed
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times"></i> Failed
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($attempt->certificate)
                                        <span class="badge bg-primary">
                                            <i class="fas fa-certificate"></i> Issued
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-minus"></i> None
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($attempt->certificate_enabled)
                                        <span class="badge bg-success">Enabled</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $attempt->created_at->format('Y-m-d') }}<br>
                                    <small class="text-muted">{{ $attempt->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.exam-results.show', $attempt->id) }}"
                                           class="btn btn-sm btn-info" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.exam-results.edit', $attempt->id) }}"
                                           class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.exam-results.recalculate', $attempt->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من إعادة حساب الدرجة؟');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" title="إعادة حساب">
                                                <i class="fas fa-calculator"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.exam-results.toggle-certificate', $attempt->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Toggle certificate access for this student?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Toggle Certificate Access">
                                                <i class="fas fa-certificate"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.exam-results.destroy', $attempt->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذه المحاولة؟ سيتم حذف جميع الإجابات والشهادة المرتبطة بها.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No results available</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $attempts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
