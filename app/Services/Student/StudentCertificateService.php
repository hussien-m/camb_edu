<?php

namespace App\Services\Student;

use App\Models\Certificate;
use App\Models\Student;

class StudentCertificateService
{
    /**
     * Get certificates for a student (exam-linked + manual)
     * @param Student $student
     * @return \Illuminate\Support\Collection
     */
    public function getCertificates(Student $student)
    {
        return Certificate::with(['course', 'examAttempt.exam'])
            ->where('student_id', $student->id)
            ->where(function ($query) {
                $query->whereHas('examAttempt', function ($q) {
                    $q->where('certificate_enabled', true);
                })->orWhere(function ($q) {
                    $q->whereNull('exam_attempt_id')->where('is_active', true);
                });
            })
            ->latest('issue_date')
            ->get();
    }

    /**
     * Get certificate details for a student
     * @param Student $student
     * @param Certificate $certificate
     * @return Certificate|null
     */
    public function getCertificateDetails(Student $student, Certificate $certificate)
    {
        if ($certificate->student_id !== $student->id) {
            return null;
        }
        if ($certificate->isManual() && !$certificate->is_active) {
            return null;
        }
        $certificate->load(['course', 'examAttempt.exam']);
        return $certificate;
    }

    /**
     * Get certificate for download
     * @param Student $student
     * @param Certificate $certificate
     * @return Certificate|null
     */
    public function getCertificateForDownload(Student $student, Certificate $certificate)
    {
        if ($certificate->student_id !== $student->id) {
            return null;
        }
        if ($certificate->isManual() && !$certificate->is_active) {
            return null;
        }
        $certificate->load(['course', 'examAttempt.exam', 'student']);
        return $certificate;
    }
}
