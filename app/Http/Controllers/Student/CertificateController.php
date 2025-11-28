<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\Student\StudentCertificateService;
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
        $certificates = $this->certificateService->getStudentCertificates($student);

        return view('student.certificates.index', compact('certificates'));
    }

    public function show(Certificate $certificate):View
    {
        $student = Auth::guard('student')->user();

        if ($certificate->student_id !== $student->id) {
            abort(403);
        }

        $certificate->load(['course', 'examAttempt.exam']);

        return view('student.certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate):View
    {
        $student = Auth::guard('student')->user();

        if ($certificate->student_id !== $student->id) {
            abort(403);
        }

        $certificate->load(['course', 'examAttempt.exam', 'student']);

        return view('student.certificates.download', compact('certificate'));
    }
}
