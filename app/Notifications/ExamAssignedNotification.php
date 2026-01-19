<?php

namespace App\Notifications;

use App\Models\Exam;
use App\Models\ExamStudentAssignment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Exam $exam,
        protected ExamStudentAssignment $assignment
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('New Exam Assignment: ' . $this->exam->title)
            ->greeting('Hello ' . $notifiable->full_name . '!')
            ->line('You have been assigned to a new exam.')
            ->line('**Exam:** ' . $this->exam->title)
            ->line('**Course:** ' . $this->exam->course->title)
            ->line('**Duration:** ' . $this->exam->duration . ' minutes')
            ->line('**Passing Score:** ' . $this->exam->passing_score . '%');

        if ($this->assignment->mode === 'scheduled') {
            if ($this->assignment->starts_at) {
                $mail->line('**Start Time:** ' . $this->assignment->starts_at->format('l, F j, Y \a\t g:i A'));
            }
            if ($this->assignment->ends_at) {
                $mail->line('**End Time:** ' . $this->assignment->ends_at->format('l, F j, Y \a\t g:i A'));
            }
        } else {
            $mail->line('**Access:** Available anytime');
        }

        return $mail
            ->line('Note: You can enter this exam only once.')
            ->action('View Exam', route('student.exams.show', $this->exam))
            ->salutation('Best regards, ' . config('app.name'));
    }
}
