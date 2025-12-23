<?php

namespace App\Notifications;

use App\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExamReminderNotification extends Notification
{
    use Queueable;

    protected $exam;
    protected $reminderType;
    protected $timeRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(Exam $exam, string $reminderType, string $timeRemaining)
    {
        $this->exam = $exam;
        $this->reminderType = $reminderType;
        $this->timeRemaining = $timeRemaining;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $examUrl = route('student.exams.show', $this->exam);
        
        return (new MailMessage)
            ->subject('Exam Reminder: ' . $this->exam->title)
            ->greeting('Hello ' . $notifiable->full_name . '!')
            ->line('This is a reminder that your exam is starting soon.')
            ->line('**Exam:** ' . $this->exam->title)
            ->line('**Course:** ' . $this->exam->course->title)
            ->line('**Time Remaining:** ' . $this->timeRemaining)
            ->line('**Exam Start:** ' . $this->exam->scheduled_start_date->format('l, F j, Y \a\t g:i A'))
            ->line('**Duration:** ' . $this->exam->duration . ' minutes')
            ->line('**Passing Score:** ' . $this->exam->passing_score . '%')
            ->action('Go to Exam', $examUrl)
            ->line('Make sure you are ready and have a stable internet connection.')
            ->line('Good luck!')
            ->salutation('Best regards, ' . config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'exam_id' => $this->exam->id,
            'exam_title' => $this->exam->title,
            'reminder_type' => $this->reminderType,
            'time_remaining' => $this->timeRemaining,
            'scheduled_start' => $this->exam->scheduled_start_date,
        ];
    }
}
