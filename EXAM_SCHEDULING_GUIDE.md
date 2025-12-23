# Exam Scheduling System - Implementation Guide

## Overview
نظام جدولة الامتحانات مع تذكيرات تلقائية بالإيميل للطلاب.

## Features Implemented ✅

### 1. Database Structure
- **exams table** - Added scheduling fields:
  - `is_scheduled` - Boolean to enable/disable scheduling
  - `scheduled_start_date` - When exam becomes available
  - `scheduled_end_date` - When exam window closes (optional)
  - `timezone` - Timezone for the exam
  - `scheduling_notes` - Admin notes about the schedule

- **exam_reminders table** - Tracks reminder emails:
  - Links to exam and student
  - Reminder type (24h, 12h, 6h, 90min, 10min)
  - Scheduled time and sent status

### 2. Admin Features

#### Create/Edit Exam Forms
- Added scheduling section with:
  - Checkbox to enable scheduling
  - Start date/time picker
  - End date/time picker (optional)
  - Timezone selector (9 Middle East timezones)
  - Scheduling notes field
  - Auto-validation (end date must be after start date)

#### Automatic Reminder Creation
- When admin creates/updates a scheduled exam:
  - System automatically creates 5 reminder records per enrolled student
  - Reminders scheduled at: 24h, 12h, 6h, 1.5h, 10min before exam
  - Only future reminders are created
  - Old reminders deleted if schedule changes

### 3. Student Features

#### Access Control
- Students **cannot** access scheduled exams before start time
- Students **cannot** access scheduled exams after end time
- Clear error messages with countdown/time information

#### Live Countdown Timer ⏰
- **Real-time countdown** displayed prominently to students
- Shows time remaining in: Days, Hours, Minutes, Seconds
- Updates every second automatically
- Beautiful animated design with gradient background
- Countdown appears in:
  - Exam details page (large prominent display)
  - Exam calendar page (on each upcoming exam card)
  - My Courses page (inside exam cards)
- When countdown reaches zero, page auto-refreshes to allow exam access

#### Exam Calendar View
- New page: `/student/exams/calendar`
- Shows all scheduled exams grouped by:
  - **Upcoming** - Not started yet (with countdown)
  - **Available Now** - Currently in exam window
  - **Past** - Exam window has closed
- Each exam card shows:
  - Course name
  - Start/end dates and times
  - Duration
  - Number of attempts used
  - Status badge

#### Email Reminders
Students receive automatic email reminders at:
- ⏰ **24 hours** before exam
- ⏰ **12 hours** before exam
- ⏰ **6 hours** before exam
- ⏰ **1.5 hours** before exam
- ⏰ **10 minutes** before exam

Email includes:
- Exam title and course
- Start date/time
- Duration and passing score
- Direct link to exam
- Motivational message

### 4. Backend Commands

#### `exams:create-reminders`
```bash
php artisan exams:create-reminders
```
- Scans for upcoming scheduled exams (next 25 hours)
- Creates reminder records for enrolled students
- Runs automatically every hour via Laravel Scheduler

#### `exams:send-reminders`
```bash
php artisan exams:send-reminders
```
- Checks for due reminders (scheduled_for <= now)
- Sends emails to students
- Marks reminders as sent
- Runs automatically every minute via Laravel Scheduler

### 5. Laravel Scheduler Configuration

Added to `app/Console/Kernel.php`:
```php
// Create reminders - runs hourly
$schedule->command('exams:create-reminders')
         ->hourly()
         ->withoutOverlapping();

// Send reminders - runs every minute
$schedule->command('exams:send-reminders')
         ->everyMinute()
         ->withoutOverlapping();
```

## Server Setup Requirements

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Configure Cron Job (Production)
Add to server crontab:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This single cron job will run all scheduled commands.

### 3. Queue Configuration (Optional but Recommended)
For better performance, configure queue workers:

**.env**
```env
QUEUE_CONNECTION=database
```

Run queue worker:
```bash
php artisan queue:work --daemon
```

Or add to supervisor (production):
```ini
[program:exam-queue-worker]
command=php /path-to-project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path-to-project/storage/logs/worker.log
```

### 4. Email Configuration
Ensure `.env` has proper email settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yoursite.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Testing

### Test Reminder Creation
```bash
php artisan exams:create-reminders
```
Check `exam_reminders` table for new records.

### Test Reminder Sending
```bash
php artisan exams:send-reminders
```
Check email inbox and `exam_reminders.sent` column.

### Test Scheduled Exam Access
1. Create exam with schedule in future
2. Try to access as student - should see "not available yet" message
3. Change schedule to past
4. Try to access - should see "exam ended" message
5. Change schedule to now
6. Try to access - should work normally

## Usage Guide for Admin

### Creating a Scheduled Exam

1. Go to **Exams** → **Create New Exam**
2. Fill in basic exam details (title, course, duration, etc.)
3. Scroll to **Exam Scheduling** section
4. Check **"Schedule this exam"**
5. Set **Start Date & Time** (when students can begin)
6. Optionally set **End Date & Time** (when exam window closes)
7. Select appropriate **Timezone**
8. Add any **Scheduling Notes** if needed
9. Click **Create Exam**

✅ System will automatically:
- Create reminder records for all enrolled students
- Schedule emails at 5 different intervals
- Prevent students from accessing before start time

### Editing a Scheduled Exam

1. Go to exam details page
2. Click **Edit Exam**
3. Modify scheduling fields as needed
4. Click **Update Exam**

✅ System will automatically:
- Delete old unsent reminders
- Create new reminders based on updated schedule

### Disabling Scheduling

1. Edit the exam
2. Uncheck **"Schedule this exam"**
3. Save

✅ System will:
- Delete all unsent reminders
- Make exam available anytime (non-scheduled)

## Important Notes

⚠️ **Timezone Handling**
- All times stored in database are UTC
- Display times converted to exam's timezone
- Student sees times in exam's configured timezone

⚠️ **Reminder Timing**
- Reminders only created if scheduled time is in future
- If you schedule exam in 2 hours, only 10min and 90min reminders will be created
- Past reminder times are automatically skipped

⚠️ **Exam Readiness**
- Scheduled exams still require:
  - At least 1 question
  - Total points = total marks (100)
- Students won't see unready exams even if scheduled

⚠️ **Enrollment Timing**
- Reminders created for students enrolled at time of exam creation
- If student enrolls after exam is scheduled, run:
  ```bash
  php artisan exams:create-reminders
  ```

## Troubleshooting

### Reminders Not Sending
1. Check cron job is running: `crontab -l`
2. Check Laravel scheduler: `php artisan schedule:list`
3. Check email configuration in `.env`
4. Check `exam_reminders` table for `sent=0` records
5. Manually run: `php artisan exams:send-reminders`
6. Check logs: `storage/logs/laravel.log`

### Students Can't Access Scheduled Exam
1. Check exam `is_scheduled` = 1
2. Check `scheduled_start_date` is in past
3. Check `scheduled_end_date` is in future (or null)
4. Check exam `isReady()` returns true
5. Check student is enrolled in course

### Reminders Not Created
1. Check exam `is_scheduled` = 1
2. Check `scheduled_start_date` is in future
3. Check students are enrolled in course
4. Manually run: `php artisan exams:create-reminders`
5. Check `exam_reminders` table

## Files Modified/Created

### New Files
- `database/migrations/2025_12_23_180648_add_scheduling_fields_to_exams_table.php`
- `database/migrations/2025_12_23_180707_create_exam_reminders_table.php`
- `app/Models/ExamReminder.php`
- `app/Notifications/ExamReminderNotification.php`
- `app/Console/Commands/CreateExamReminders.php`
- `app/Console/Commands/SendExamReminders.php`
- `resources/views/student/exams/calendar.blade.php`

### Modified Files
- `app/Models/Exam.php` - Added scheduling methods
- `app/Services/Admin/ExamService.php` - Auto-create reminders
- `app/Services/Student/StudentExamService.php` - Check schedule access
- `app/Services/Student/StudentCourseService.php` - Filter ready exams
- `app/Http/Requests/Admin/StoreExamRequest.php` - Validate scheduling
- `app/Http/Requests/Admin/UpdateExamRequest.php` - Validate scheduling
- `app/Http/Controllers/Student/ExamController.php` - Added calendar method
- `app/Console/Kernel.php` - Added scheduler commands
- `resources/views/admin/exams/create.blade.php` - Added scheduling UI
- `resources/views/admin/exams/edit.blade.php` - Added scheduling UI
- `resources/views/admin/exams/show.blade.php` - Added readiness alert
- `resources/views/student/layouts/app.blade.php` - Added calendar link
- `resources/views/student/exams/show.blade.php` - Added countdown timer
- `resources/views/student/exams/calendar.blade.php` - Added countdown timer
- `resources/views/student/courses/index.blade.php` - Added countdown timer
- `routes/student.php` - Added calendar route

## Future Enhancements (Not Implemented)

- SMS reminders in addition to email
- Push notifications for mobile app
- Customizable reminder intervals per exam
- Bulk schedule multiple exams
- Recurring scheduled exams
- Exam schedule conflicts detection
- Student timezone preference
- Calendar export (iCal format)
- Reminder preview before sending

---

**Version:** 2.0  
**Date:** December 23, 2025  
**Status:** ✅ Complete and Ready for Production

