<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Queue worker - process emails in background
        $schedule->command('queue:work --stop-when-empty --tries=3')
                 ->everyMinute()
                 ->withoutOverlapping();

        // Create exam reminders for upcoming scheduled exams
        // Runs every hour to create reminders for newly scheduled exams
        $schedule->command('exams:create-reminders')
                 ->hourly()
                 ->withoutOverlapping();

        // Send due exam reminders
        // Runs every minute to check for due reminders
        $schedule->command('exams:send-reminders')
                 ->everyMinute()
                 ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
