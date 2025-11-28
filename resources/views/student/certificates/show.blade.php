@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="text-end mb-3">
                <a href="{{ route('student.certificates.download', $certificate) }}" class="btn btn-success me-2">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
                <a href="{{ route('student.certificates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Certificates
                </a>
            </div>

            <div class="card certificate-card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-5 text-white">
                    <div class="text-center mb-4">
                        <i class="fas fa-award fa-5x mb-3" style="opacity: 0.9;"></i>
                        <h1 class="display-4 fw-bold mb-0" style="font-family: 'Georgia', serif;">Certificate of Completion</h1>
                    </div>

                    <div class="certificate-content bg-white text-dark p-5 rounded shadow-sm">
                        <div class="text-center mb-4">
                            <p class="lead mb-4" style="font-size: 1.1rem;">This is to certify that</p>
                            <h2 class="display-5 fw-bold mb-4" style="color: #667eea; font-family: 'Georgia', serif;">
                                {{ $certificate->student->name }}
                            </h2>
                            <p class="lead mb-4">has successfully completed the course</p>
                            <h3 class="fw-bold mb-4" style="color: #764ba2;">
                                {{ $certificate->course->title }}
                            </h3>
                        </div>

                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-calendar text-primary fa-2x mb-2"></i>
                                    <h6 class="text-muted mb-1">Issue Date</h6>
                                    <p class="mb-0 fw-bold">{{ $certificate->issue_date->format('F d, Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-star text-warning fa-2x mb-2"></i>
                                    <h6 class="text-muted mb-1">Exam Score</h6>
                                    <p class="mb-0 fw-bold">{{ $certificate->examAttempt->percentage }}%</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <i class="fas fa-hashtag text-success fa-2x mb-2"></i>
                                    <h6 class="text-muted mb-1">Certificate No.</h6>
                                    <p class="mb-0 fw-bold">{{ $certificate->certificate_number }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-5 pt-4 border-top">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="qr-code-placeholder mb-3">
                                        @if($certificate->qr_code)
                                            <img src="{{ asset('storage/' . $certificate->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width: 120px;">
                                        @else
                                            <div class="border rounded p-4 d-inline-block bg-light">
                                                <i class="fas fa-qrcode fa-4x text-muted"></i>
                                                <p class="small text-muted mb-0 mt-2">QR Code</p>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="small text-muted mb-0">Scan to verify</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="signature-line mb-2">
                                        <div class="border-bottom border-dark mb-2" style="width: 250px; margin: 0 auto;"></div>
                                        <p class="mb-0 fw-bold">Authorized Signature</p>
                                        <p class="small text-muted mb-0">Training Camp</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">
                                This certificate validates the successful completion of {{ $certificate->course->title }}
                            </p>
                            <p class="small text-muted">
                                Certificate issued on {{ $certificate->issue_date->format('F d, Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <p class="small mb-0" style="opacity: 0.8;">
                            <i class="fas fa-shield-alt me-1"></i>
                            This is an official certificate issued by Training Camp
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('student.exams.result', $certificate->examAttempt) }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-2"></i>View Exam Results
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.certificate-card {
    page-break-inside: avoid;
}

@media print {
    .btn, .text-end.mb-3, .text-center.mt-4 {
        display: none !important;
    }

    .certificate-card {
        box-shadow: none !important;
        border: 2px solid #667eea !important;
    }
}
</style>
@endsection
