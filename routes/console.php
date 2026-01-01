<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule verification reminders to run every 3 days at 9:00 AM UTC
Schedule::command('students:send-verification-reminders')
    ->cron('0 9 */3 * *') // Every 3 days at 9:00 AM
    ->timezone('UTC')
    ->withoutOverlapping()
    ->runInBackground();
