@extends('student.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-end mb-3">
                @if($certificate->certificate_file)
                    <a href="{{ route('student.certificates.download', $certificate) }}" class="btn btn-success me-2">
                        <i class="fas fa-download me-2"></i>Download Certificate
                    </a>
                @else
                    <button type="button" class="btn btn-success me-2" id="download-certificate-as-image">
                        <i class="fas fa-image me-2"></i>Download as Image
                    </button>
                @endif
                <a href="{{ route('student.certificates.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Certificates
                </a>
            </div>

            @include('student.certificates.partials.template')

            @if($certificate->examAttempt)
            <div class="text-center mt-4">
                <a href="{{ route('student.exams.result', $certificate->examAttempt) }}" class="btn btn-info">
                    <i class="fas fa-chart-bar me-2"></i>View Exam Results
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('download-certificate-as-image');
    var el = document.getElementById('certificate-to-capture');

    function doDownload() {
        if (!el || typeof html2canvas === 'undefined') return;
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Preparing...'; }
        html2canvas(el, {
            scale: 2,
            useCORS: true,
            allowTaint: true,
            logging: false,
            backgroundColor: '#ffffff'
        }).then(function(canvas) {
            var link = document.createElement('a');
            link.download = 'certificate-{{ $certificate->certificate_number }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-image me-2"></i>Download as Image'; }
            if (window.location.search.indexOf('download=1') !== -1) {
                history.replaceState({}, '', window.location.pathname);
            }
        }).catch(function() {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fas fa-image me-2"></i>Download as Image'; }
        });
    }

    if (btn) btn.addEventListener('click', doDownload);

    if (window.location.search.indexOf('download=1') !== -1 && el) {
        setTimeout(doDownload, 800);
    }
});
</script>
@endpush
