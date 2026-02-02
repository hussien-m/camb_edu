<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Services\Student\StudentCertificateService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
        if ($certificate->exam_attempt_id && (!$certificate->examAttempt || !$certificate->examAttempt->certificate_enabled)) {
            abort(403);
        }
        if ($certificate->isManual() && !$certificate->is_active) {
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
        if ($certificate->exam_attempt_id && (!$certificate->examAttempt || !$certificate->examAttempt->certificate_enabled)) {
            abort(403);
        }
        if ($certificate->isManual() && !$certificate->is_active) {
            abort(403);
        }

        // Manual cert with uploaded file: redirect to file
        if ($certificate->isManual() && $certificate->certificate_file) {
            $path = storage_path('app/public/' . $certificate->certificate_file);
            if (file_exists($path)) {
                return response()->download($path, 'certificate-' . $certificate->certificate_number . '.' . pathinfo($path, PATHINFO_EXTENSION));
            }
            abort(404);
        }

        // Exam-linked or manual without file: generate PDF (نفس التصميم المعروض، صفحة واحدة عرضي)
        $pdf = Pdf::loadView('student.certificates.download', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }

    /**
     * Stream certificate file for viewing (inline) - bypasses 403 from direct storage access
     */
    public function viewFile(Certificate $certificate): BinaryFileResponse
    {
        $student = Auth::guard('student')->user();
        if ($certificate->student_id !== $student->id) {
            abort(403);
        }
        $certificate->load('examAttempt');
        if ($certificate->exam_attempt_id && (!$certificate->examAttempt || !$certificate->examAttempt->certificate_enabled)) {
            abort(403);
        }
        if ($certificate->isManual() && !$certificate->is_active) {
            abort(403);
        }
        if (empty($certificate->certificate_file)) {
            abort(404);
        }
        $path = storage_path('app/public/' . $certificate->certificate_file);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="certificate-' . $certificate->certificate_number . '"',
        ]);
    }

    /**
     * Stream transcript file for viewing (inline)
     */
    public function viewTranscript(Certificate $certificate): BinaryFileResponse
    {
        $student = Auth::guard('student')->user();
        if ($certificate->student_id !== $student->id) {
            abort(403);
        }
        $certificate->load('examAttempt');
        if ($certificate->exam_attempt_id && (!$certificate->examAttempt || !$certificate->examAttempt->certificate_enabled)) {
            abort(403);
        }
        if ($certificate->isManual() && !$certificate->is_active) {
            abort(403);
        }
        if (empty($certificate->transcript_file)) {
            abort(404);
        }
        $path = storage_path('app/public/' . $certificate->transcript_file);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path, [
            'Content-Disposition' => 'inline; filename="transcript-' . $certificate->certificate_number . '"',
        ]);
    }
}
