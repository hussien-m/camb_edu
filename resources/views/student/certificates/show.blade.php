@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-end mb-3">
                <a href="{{ route('student.certificates.download', $certificate) }}" class="btn btn-success me-2">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
                <a href="{{ route('student.certificates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Certificates
                </a>
            </div>

            @include('student.certificates.partials.template')

            <div class="text-center mt-4">
                <a href="{{ route('student.exams.result', $certificate->examAttempt) }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-2"></i>View Exam Results
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
