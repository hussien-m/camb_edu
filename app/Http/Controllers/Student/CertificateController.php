<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\Student\StudentCertificateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(StudentCertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index():View
    {
        $student = Auth::guard('student')->user();
        $certificates = $this->certificateService->getCertificates($student);

        return view('student.certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate):View
    {
        $student = Auth::guard('student')->user();

        if ($certificate->student_id !== $student->id) {
            abort(403);
        }

        $certificate->load(['course', 'examAttempt.exam']);
        if (!$certificate->examAttempt || !$certificate->examAttempt->certificate_enabled) {
            abort(403);
        }

        return view('student.certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        $student = Auth::guard('student')->user();

        if ($certificate->student_id !== $student->id) {
            abort(403);
        }

        $certificate->load(['course', 'examAttempt.exam', 'student']);
        if (!$certificate->examAttempt || !$certificate->examAttempt->certificate_enabled) {
            abort(403);
        }
        $backgroundPath = public_path('certificates/certificate-template.png');
        $backgroundImage = null;
        if (file_exists($backgroundPath)) {
            $backgroundImage = 'data:image/png;base64,' . base64_encode(file_get_contents($backgroundPath));
        }

        $pdf = Pdf::loadView('student.certificates.download', compact('certificate', 'backgroundImage'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);

        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }
}
