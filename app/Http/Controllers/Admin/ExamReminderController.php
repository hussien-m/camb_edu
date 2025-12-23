<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ExamReminderController extends Controller
{
    /**
     * Show reminders dashboard
     */
    public function index()
    {
        $stats = [
            'total' => ExamReminder::count(),
            'pending' => ExamReminder::where('sent', 0)->count(),
            'sent' => ExamReminder::where('sent', 1)->count(),
            'due' => ExamReminder::where('sent', 0)
                ->where('scheduled_for', '<=', now())
                ->count(),
        ];

        $dueReminders = ExamReminder::with(['exam', 'student'])
            ->where('sent', 0)
            ->where('scheduled_for', '<=', now())
            ->orderBy('scheduled_for')
            ->limit(20)
            ->get();

        $upcomingReminders = ExamReminder::with(['exam', 'student'])
            ->where('sent', 0)
            ->where('scheduled_for', '>', now())
            ->orderBy('scheduled_for')
            ->limit(20)
            ->get();

        $recentSent = ExamReminder::with(['exam', 'student'])
            ->where('sent', 1)
            ->orderBy('sent_at', 'desc')
            ->limit(10)
            ->get();

        // Time diagnostics
        $timeDiagnostics = [
            'server_time' => now()->format('Y-m-d H:i:s T'),
            'laravel_timezone' => config('app.timezone'),
            'db_time' => DB::select('SELECT NOW() as time')[0]->time,
            'php_timezone' => date_default_timezone_get(),
        ];

        return view('admin.exam-reminders.index', compact(
            'stats',
            'dueReminders',
            'upcomingReminders',
            'recentSent',
            'timeDiagnostics'
        ));
    }

    /**
     * Create reminders for scheduled exams
     */
    public function create()
    {
        try {
            Artisan::call('exams:create-reminders');
            $output = Artisan::output();

            return back()->with('success', 'Reminders created successfully! ' . $output);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create reminders: ' . $e->getMessage());
        }
    }

    /**
     * Send due reminders
     */
    public function send()
    {
        try {
            Artisan::call('exams:send-reminders');
            $output = Artisan::output();

            return back()->with('success', 'Reminders sent successfully! ' . $output);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reminders: ' . $e->getMessage());
        }
    }

    /**
     * Delete all unsent reminders
     */
    public function deleteUnsent()
    {
        try {
            $count = ExamReminder::where('sent', 0)->delete();
            return back()->with('success', "Deleted {$count} unsent reminders.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete reminders: ' . $e->getMessage());
        }
    }

    /**
     * Test email sending
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            \Mail::raw('This is a test email from Exam Reminder System. Time: ' . now(), function($message) use ($request) {
                $message->to($request->email)
                        ->subject('Test Email - Exam Reminders');
            });

            return back()->with('success', 'Test email sent to ' . $request->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
