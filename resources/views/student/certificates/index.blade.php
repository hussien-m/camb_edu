@extends('student.layouts.app')

@section('title', 'My Certificates')
@section('page-title', 'My Certificates')

@push('styles')
<style>
    .certificate-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: none;
        margin-bottom: 2rem;
    }
    .certificate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(245, 158, 11, 0.2);
    }
    .certificate-icon {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        width: 120px;
        height: 120px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3.5rem;
        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
    }
    .cert-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 2px solid;
    }
    .page-header {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(245, 158, 11, 0.15);
        border: 2px solid rgba(245, 158, 11, 0.2);
    }
    .empty-state {
        background: white;
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="mb-2 fw-bold" style="color: #92400e;">
                    <i class="fas fa-award me-3"></i>My Certificates
                </h2>
                <p class="text-muted mb-0">Your achievements and accomplishments</p>
            </div>
            <div class="text-end">
                <span class="badge" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); font-size: 1rem; padding: 0.75rem 1.5rem;">
                    {{ count($certificates) }} {{ Str::plural('Certificate', count($certificates)) }}
                </span>
            </div>
        </div>
    </div>

    @forelse($certificates as $certificate)
        <div class="certificate-card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="certificate-icon mx-auto">
                            <i class="fas fa-certificate"></i>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h4 class="mb-3 fw-bold" style="color: #1e3a8a;">{{ $certificate->course->title }}</h4>
                        <p class="text-muted mb-3">
                            <i class="fas fa-clipboard-check me-2" style="color: #f59e0b;"></i>
                            <strong>Exam:</strong> {{ $certificate->examAttempt->exam->title }}
                        </p>
                        <div class="d-flex gap-2 flex-wrap mb-3">
                            <span class="cert-badge" style="background: #dbeafe; color: #1e40af; border-color: #93c5fd;">
                                <i class="fas fa-hashtag me-1"></i>{{ $certificate->certificate_number }}
                            </span>
                            <span class="cert-badge" style="background: #d1fae5; color: #065f46; border-color: #6ee7b7;">
                                <i class="fas fa-calendar me-1"></i>{{ $certificate->issue_date->format('M d, Y') }}
                            </span>
                            <span class="cert-badge" style="background: #fef3c7; color: #92400e; border-color: #fcd34d;">
                                <i class="fas fa-star me-1"></i>Score: {{ $certificate->examAttempt->percentage }}%
                            </span>
                        </div>
                        <p class="text-muted mb-0">
                            <i class="fas fa-user me-1"></i>{{ auth('student')->user()->name }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <div class="d-grid gap-2">
                            <a href="{{ route('student.certificates.show', $certificate) }}"
                               class="btn btn-lg"
                               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 0.875rem; font-weight: 600;">
                                <i class="fas fa-eye me-2"></i>View Certificate
                            </a>
                            <a href="{{ route('student.certificates.download', $certificate) }}"
                               class="btn btn-lg"
                               style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 12px; padding: 0.875rem; font-weight: 600;">
                                <i class="fas fa-download me-2"></i>Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="certificate-icon mx-auto mb-4" style="opacity: 0.3;">
                <i class="fas fa-certificate"></i>
            </div>
            <h4 class="mb-3 fw-bold" style="color: #1e3a8a;">No Certificates Yet</h4>
            <p class="text-muted mb-4">Complete course exams to earn your certificates and showcase your achievements!</p>
            <a href="{{ route('student.courses.index') }}"
               class="btn btn-lg"
               style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 1rem 2rem; font-weight: 600;">
                <i class="fas fa-book me-2"></i>View My Courses
            </a>
        </div>
    @endforelse
</div>
@endsection
