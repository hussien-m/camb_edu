@extends('admin.layouts.app')

@section('title', 'Exam Results Management')
@section('page-title', 'Exam Results Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Exam Results</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Exam Results</h3>
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
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px">ID</th>
                            <th>Student</th>
                            <th>Exam</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Certificate</th>
                            <th>Date</th>
                            <th style="width: 280px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attempts as $attempt)
                            <tr>
                                <td>{{ $attempt->id }}</td>
                                <td>
                                    <strong>{{ $attempt->student->name }}</strong><br>
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
@endsection
