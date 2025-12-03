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
}
