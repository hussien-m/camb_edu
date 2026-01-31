<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'course_title',
        'exam_attempt_id',
        'certificate_number',
        'issue_date',
        'certificate_file',
        'transcript_file',
        'is_active',
        'qr_code',
    ];

    /**
     * Get display course title (from course relation or manual course_title)
     */
    public function getDisplayCourseTitleAttribute(): string
    {
        if ($this->course_id && $this->course) {
            return $this->course->title ?? '';
        }
        return $this->course_title ?? '';
    }

    protected $casts = [
        'issue_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isManual(): bool
    {
        return $this->exam_attempt_id === null;
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function examAttempt()
    {
        return $this->belongsTo(ExamAttempt::class);
    }

    public static function generateCertificateNumber()
    {
        return 'CERT-' . date('Y') . '-' . strtoupper(uniqid());
    }
}
