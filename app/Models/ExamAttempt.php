<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_id',
        'enrollment_id',
        'start_time',
        'end_time',
        'score',
        'percentage',
        'passed',
        'certificate_enabled',
        'attempt_number',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'passed' => 'boolean',
        'percentage' => 'decimal:2',
        'certificate_enabled' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class, 'attempt_id');
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class);
    }
}
