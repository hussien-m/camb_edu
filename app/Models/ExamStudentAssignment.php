<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStudentAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'assigned_by_admin_id',
        'exam_attempt_id',
        'mode',
        'status',
        'starts_at',
        'ends_at',
        'assigned_at',
        'started_at',
        'submitted_at',
        'last_activity_at',
        'score',
        'percentage',
        'passed',
        'grade',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'percentage' => 'decimal:2',
        'passed' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(Admin::class, 'assigned_by_admin_id');
    }

    public function attempt()
    {
        return $this->belongsTo(ExamAttempt::class, 'exam_attempt_id');
    }
}
