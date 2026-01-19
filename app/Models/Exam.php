<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'duration',
        'passing_score',
        'max_attempts',
        'total_marks',
        'status',
        'is_scheduled',
        'scheduled_start_date',
        'scheduled_end_date',
        'timezone',
        'scheduling_notes',
        'group_assignment_enabled',
        'allow_enrolled_access',
    ];

    protected $casts = [
        'is_scheduled' => 'boolean',
        'scheduled_start_date' => 'datetime',
        'scheduled_end_date' => 'datetime',
        'group_assignment_enabled' => 'boolean',
        'allow_enrolled_access' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function assignments()
    {
        return $this->hasMany(ExamStudentAssignment::class);
    }

    public function reminders()
    {
        return $this->hasMany(ExamReminder::class);
    }

    /**
     * Check if exam is ready for students
     * Exam is ready when it has questions and total points equal total_marks
     */
    public function isReady(): bool
    {
        $questionsCount = $this->questions()->count();
        $totalPoints = $this->questions()->sum('points');
        
        return $questionsCount > 0 && $totalPoints == $this->total_marks;
    }

    /**
     * Get exam readiness status details
     */
    public function getReadinessStatus(): array
    {
        $questionsCount = $this->questions()->count();
        $totalPoints = $this->questions()->sum('points');
        $isReady = $questionsCount > 0 && $totalPoints == $this->total_marks;
        
        return [
            'is_ready' => $isReady,
            'questions_count' => $questionsCount,
            'total_points' => $totalPoints,
            'required_points' => $this->total_marks,
            'points_difference' => $this->total_marks - $totalPoints,
        ];
    }

    /**
     * Check if exam is currently available based on schedule
     */
    public function isAvailable(): bool
    {
        if (!$this->is_scheduled) {
            return true; // Non-scheduled exams are always available
        }

        $now = now();
        
        if ($this->scheduled_start_date && $now->lt($this->scheduled_start_date)) {
            return false; // Not started yet
        }

        if ($this->scheduled_end_date && $now->gt($this->scheduled_end_date)) {
            return false; // Already ended
        }

        return true;
    }

    /**
     * Get exam status text
     */
    public function getScheduleStatus(): string
    {
        if (!$this->is_scheduled) {
            return 'Available anytime';
        }

        $now = now();

        if ($this->scheduled_start_date && $now->lt($this->scheduled_start_date)) {
            return 'Scheduled - Not started';
        }

        if ($this->scheduled_end_date && $now->gt($this->scheduled_end_date)) {
            return 'Ended';
        }

        return 'Available now';
    }

    /**
     * Get time until exam starts (in minutes)
     */
    public function getTimeUntilStart(): ?int
    {
        if (!$this->is_scheduled || !$this->scheduled_start_date) {
            return null;
        }

        $now = now();
        if ($now->gte($this->scheduled_start_date)) {
            return 0;
        }

        return $now->diffInMinutes($this->scheduled_start_date);
    }
}
