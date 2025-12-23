<?php
/**
 * Debug Exam Reminders
 * رفع هذا الملف على: /home/k4c69o7wqcc3/public_html/debug_reminders.php
 * واذهب لـ: https://cambridgecollage.com/debug_reminders.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "<h1>Exam Reminders Debug</h1>";
echo "<hr>";

// 1. Check database connection
echo "<h2>1. Database Connection</h2>";
try {
    DB::connection()->getPdo();
    echo "✅ Connected to database: " . DB::connection()->getDatabaseName() . "<br>";
} catch (\Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// 2. Check reminders table
echo "<h2>2. Exam Reminders Table</h2>";
$totalReminders = DB::table('exam_reminders')->count();
$pendingReminders = DB::table('exam_reminders')->where('sent', 0)->count();
$sentReminders = DB::table('exam_reminders')->where('sent', 1)->count();

echo "Total Reminders: <strong>{$totalReminders}</strong><br>";
echo "Pending (not sent): <strong style='color: orange;'>{$pendingReminders}</strong><br>";
echo "Sent: <strong style='color: green;'>{$sentReminders}</strong><br>";
echo "<hr>";

// 3. Check due reminders
echo "<h2>3. Due Reminders (should be sent now)</h2>";
$dueReminders = DB::table('exam_reminders')
    ->select('exam_reminders.*', 'students.full_name', 'students.email', 'exams.title as exam_title')
    ->join('students', 'exam_reminders.student_id', '=', 'students.id')
    ->join('exams', 'exam_reminders.exam_id', '=', 'exams.id')
    ->where('exam_reminders.sent', 0)
    ->where('exam_reminders.scheduled_for', '<=', now())
    ->get();

if ($dueReminders->isEmpty()) {
    echo "<p style='color: orange;'>⚠️ No reminders are due right now.</p>";
} else {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Student</th><th>Email</th><th>Exam</th><th>Type</th><th>Scheduled For</th></tr>";
    foreach ($dueReminders as $reminder) {
        echo "<tr>";
        echo "<td>{$reminder->id}</td>";
        echo "<td>{$reminder->full_name}</td>";
        echo "<td>{$reminder->email}</td>";
        echo "<td>{$reminder->exam_title}</td>";
        echo "<td>{$reminder->reminder_type}</td>";
        echo "<td>{$reminder->scheduled_for}</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<br><strong style='color: red;'>These reminders should be sent!</strong>";
}
echo "<hr>";

// 4. Check upcoming reminders
echo "<h2>4. Upcoming Reminders (not due yet)</h2>";
$upcomingReminders = DB::table('exam_reminders')
    ->select('exam_reminders.*', 'students.full_name', 'exams.title as exam_title')
    ->join('students', 'exam_reminders.student_id', '=', 'students.id')
    ->join('exams', 'exam_reminders.exam_id', '=', 'exams.id')
    ->where('exam_reminders.sent', 0)
    ->where('exam_reminders.scheduled_for', '>', now())
    ->orderBy('exam_reminders.scheduled_for')
    ->limit(10)
    ->get();

if ($upcomingReminders->isEmpty()) {
    echo "<p>No upcoming reminders.</p>";
} else {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Student</th><th>Exam</th><th>Type</th><th>Scheduled For</th><th>Time Until</th></tr>";
    foreach ($upcomingReminders as $reminder) {
        $scheduledTime = new DateTime($reminder->scheduled_for);
        $now = new DateTime();
        $diff = $now->diff($scheduledTime);
        $timeUntil = $diff->format('%a days, %h hours, %i minutes');
        
        echo "<tr>";
        echo "<td>{$reminder->id}</td>";
        echo "<td>{$reminder->full_name}</td>";
        echo "<td>{$reminder->exam_title}</td>";
        echo "<td>{$reminder->reminder_type}</td>";
        echo "<td>{$reminder->scheduled_for}</td>";
        echo "<td style='color: blue;'>{$timeUntil}</td>";
        echo "</tr>";
    }
    echo "</table>";
}
echo "<hr>";

// 5. Check mail configuration
echo "<h2>5. Mail Configuration</h2>";
echo "MAIL_MAILER: <strong>" . config('mail.default') . "</strong><br>";
echo "MAIL_HOST: <strong>" . config('mail.mailers.smtp.host') . "</strong><br>";
echo "MAIL_PORT: <strong>" . config('mail.mailers.smtp.port') . "</strong><br>";
echo "MAIL_USERNAME: <strong>" . config('mail.mailers.smtp.username') . "</strong><br>";
echo "MAIL_ENCRYPTION: <strong>" . config('mail.mailers.smtp.encryption') . "</strong><br>";
echo "MAIL_FROM_ADDRESS: <strong>" . config('mail.from.address') . "</strong><br>";
echo "<hr>";

// 6. Check students with no email
echo "<h2>6. Students Without Email</h2>";
$studentsNoEmail = DB::table('students')
    ->whereNull('email')
    ->orWhere('email', '')
    ->count();
echo "Students with no email: <strong>{$studentsNoEmail}</strong><br>";
echo "<hr>";

// 7. Check scheduled exams
echo "<h2>7. Scheduled Exams</h2>";
$scheduledExams = DB::table('exams')
    ->where('is_scheduled', 1)
    ->where('scheduled_start_date', '>', now())
    ->get(['id', 'title', 'scheduled_start_date']);

if ($scheduledExams->isEmpty()) {
    echo "<p>No scheduled exams found.</p>";
} else {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Title</th><th>Start Date</th></tr>";
    foreach ($scheduledExams as $exam) {
        echo "<tr>";
        echo "<td>{$exam->id}</td>";
        echo "<td>{$exam->title}</td>";
        echo "<td>{$exam->scheduled_start_date}</td>";
        echo "</tr>";
    }
    echo "</table>";
}
echo "<hr>";

echo "<h2>✅ Debug Complete</h2>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>If 'Due Reminders' shows records → Run: <code>php artisan exams:send-reminders</code></li>";
echo "<li>If no due reminders → They are scheduled for future times</li>";
echo "<li>Check your Cron Job is running every minute</li>";
echo "<li>Check mail configuration is correct</li>";
echo "</ul>";

echo "<hr>";
echo "<p style='color: red;'><strong>⚠️ Security Warning:</strong> Delete this file after debugging!</p>";
echo "<p><code>rm /home/k4c69o7wqcc3/public_html/debug_reminders.php</code></p>";

