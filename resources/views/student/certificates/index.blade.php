@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">My Certificates</h2>

    @forelse($certificates as $certificate)
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <i class="fas fa-certificate fa-5x text-warning"></i>
                    </div>
                    <div class="col-md-7">
                        <h4>{{ $certificate->course->title }}</h4>
                        <p class="text-muted mb-2">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Exam: {{ $certificate->examAttempt->exam->title }}
                        </p>
                        <div class="mb-2">
                            <span class="badge bg-primary me-2">
                                <i class="fas fa-hashtag me-1"></i>{{ $certificate->certificate_number }}
                            </span>
                            <span class="badge bg-success me-2">
                                <i class="fas fa-calendar me-1"></i>Issued: {{ $certificate->issue_date->format('M d, Y') }}
                            </span>
                            <span class="badge bg-info">
                                <i class="fas fa-star me-1"></i>Score: {{ $certificate->examAttempt->percentage }}%
                            </span>
                        </div>
                        <p class="text-muted mb-0">
                            <small>
                                <i class="fas fa-user me-1"></i>{{ auth('student')->user()->name }}
                            </small>
                        </p>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('student.certificates.show', $certificate) }}" class="btn btn-primary mb-2 w-100">
                            <i class="fas fa-eye me-2"></i>View Certificate
                        </a>
                        <a href="{{ route('student.certificates.download', $certificate) }}" class="btn btn-success w-100">
                            <i class="fas fa-download me-2"></i>Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-certificate fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No Certificates Yet</h4>
                <p class="text-muted">Complete course exams to earn certificates!</p>
                <a href="{{ route('student.courses.index') }}" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i>View My Courses
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection
