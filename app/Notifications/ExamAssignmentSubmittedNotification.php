<?php

namespace App\Notifications;

use App\Models\Exam;
use App\Models\ExamStudentAssignment;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamAssignmentSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Exam $exam,
        protected Student $student,
        protected ExamStudentAssignment $assignment
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $score = $this->assignment->percentage ?? 0;

        return (new MailMessage)
            ->subject('Exam Submitted: ' . $this->exam->title)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A student has submitted an assigned exam.')
            ->line('**Student:** ' . $this->student->full_name . ' (' . $this->student->email . ')')
            ->line('**Exam:** ' . $this->exam->title)
            ->line('**Score:** ' . number_format($score, 2) . '%')
            ->line('**Submitted At:** ' . $this->assignment->submitted_at?->format('l, F j, Y \a\t g:i A'))
            ->action('View Exam', route('admin.exams.show', $this->exam))
            ->salutation('Best regards, ' . config('app.name'));
    }
}
