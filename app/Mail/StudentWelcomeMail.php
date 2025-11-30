<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public Student $student;
    public array $siteSettings;

    public function __construct(Student $student)
    {
        $this->student = $student;
        $this->siteSettings = $this->getSiteSettings();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Welcome to ' . $this->siteSettings['site_name'] . '!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student.welcome',
        );
    }

    private function getSiteSettings(): array
    {
        return [
            'site_name' => Setting::where('key', 'site_name')->value('value') ?? 'Cambridge College',
            'site_logo' => Setting::where('key', 'site_logo')->value('value'),
            'contact_email' => Setting::where('key', 'contact_email')->value('value') ?? 'info@cambridgecollege.com',
            'contact_phone' => Setting::where('key', 'contact_phone')->value('value'),
            'site_description' => Setting::where('key', 'site_description')->value('value'),
            'social_facebook' => Setting::where('key', 'social_facebook')->value('value'),
            'social_twitter' => Setting::where('key', 'social_twitter')->value('value'),
            'social_instagram' => Setting::where('key', 'social_instagram')->value('value'),
        ];
    }
}
