<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'reminder_type',
        'scheduled_for',
        'sent',
        'sent_at',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
        'sent' => 'boolean',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get reminder label
     */
    public function getReminderLabel(): string
    {
        return match($this->reminder_type) {
            '24h' => '24 hours',
            '12h' => '12 hours',
            '6h' => '6 hours',
            '90min' => '1.5 hours',
            '10min' => '10 minutes',
            default => $this->reminder_type,
        };
    }

    /**
     * Mark reminder as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'sent' => true,
            'sent_at' => now(),
        ]);
    }
}
