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
        'exam_attempt_id',
        'certificate_number',
        'issue_date',
        'certificate_file',
        'qr_code',
    ];

    protected $casts = [
        'issue_date' => 'datetime',
    ];

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
