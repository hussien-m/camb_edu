# إعداد نظام التذكيرات على السيرفر
## Setup Exam Reminders on Server

---

## 📋 الخطوات المطلوبة:

### 1️⃣ **رفع الملفات على السيرفر**

تأكد أن هذه الملفات موجودة على السيرفر:

```bash
/home/k4c69o7wqcc3/public_html/
├── app/
│   ├── Models/ExamReminder.php
│   ├── Notifications/ExamReminderNotification.php
│   ├── Console/Commands/CreateExamReminders.php
│   ├── Console/Commands/SendExamReminders.php
│   └── Console/Kernel.php
└── database/migrations/
    ├── 2025_12_23_180648_add_scheduling_fields_to_exams_table.php
    └── 2025_12_23_180707_create_exam_reminders_table.php
```

---

### 2️⃣ **تشغيل الـ Migrations**

اتصل بالسيرفر عبر SSH أو Terminal في cPanel:

```bash
cd /home/k4c69o7wqcc3/public_html

# تشغيل migrations
php artisan migrate

# مسح الكاش
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

---

### 3️⃣ **إعداد إعدادات البريد الإلكتروني**

في ملف `.env` على السيرفر:

```env
# إعدادات البريد الإلكتروني
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com          # أو smtp السيرفر
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password    # استخدم App Password من Google
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yoursite.com
MAIL_FROM_NAME="${APP_NAME}"

# Queue (اختياري لكن موصى به)
QUEUE_CONNECTION=database
```

**ملاحظة:** إذا كنت تستخدم Gmail، اتبع هذه الخطوات:
1. افتح [Google Account Security](https://myaccount.google.com/security)
2. فعّل "2-Step Verification"
3. اذهب لـ "App Passwords"
4. أنشئ App Password جديد واستخدمه في `MAIL_PASSWORD`

---

### 4️⃣ **إعداد Cron Job** ⏰

#### الطريقة الأولى: عبر cPanel

1. **افتح cPanel**
2. **اذهب لـ "Cron Jobs"**
3. **أضف Cron Job جديد:**

```bash
# التردد: كل دقيقة (* * * * *)
* * * * * cd /home/k4c69o7wqcc3/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**ملاحظة:** تأكد من استخدام المسار الكامل لـ PHP إذا لزم الأمر:
```bash
* * * * * cd /home/k4c69o7wqcc3/public_html && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

**لمعرفة مسار PHP على السيرفر:**
```bash
which php
# أو
whereis php
```

---

#### الطريقة الثانية: عبر SSH (لو عندك صلاحيات)

```bash
# افتح crontab
crontab -e

# أضف هذا السطر:
* * * * * cd /home/k4c69o7wqcc3/public_html && php artisan schedule:run >> /dev/null 2>&1

# احفظ واخرج
# في vim: اضغط ESC ثم :wq
# في nano: اضغط Ctrl+X ثم Y ثم Enter
```

---

### 5️⃣ **اختبار النظام** 🧪

#### الخطوة 1: اختبار إنشاء التذكيرات
```bash
cd /home/k4c69o7wqcc3/public_html
php artisan exams:create-reminders
```

**النتيجة المتوقعة:**
```
Creating reminders for upcoming scheduled exams...

Processing exam: English Exam
  ✓ Created 24h reminder for John Doe
  ✓ Created 12h reminder for John Doe
  ...

====================================
Total reminders created: 15
====================================
```

#### الخطوة 2: التحقق من قاعدة البيانات
```bash
# افتح phpMyAdmin أو MySQL
SELECT * FROM exam_reminders WHERE sent = 0;
```

يجب أن تشاهد سجلات للتذكيرات.

#### الخطوة 3: اختبار إرسال التذكيرات (للاختبار فقط)
```bash
php artisan exams:send-reminders
```

**ملاحظة:** هذا الأمر سيرسل فقط التذكيرات التي حان وقتها (`scheduled_for <= now`)

---

### 6️⃣ **التحقق من عمل Cron Job**

#### مراقبة Logs
```bash
# إنشاء ملف log للتأكد
* * * * * cd /home/k4c69o7wqcc3/public_html && php artisan schedule:run >> /home/k4c69o7wqcc3/cron.log 2>&1
```

ثم راقب الملف:
```bash
tail -f /home/k4c69o7wqcc3/cron.log
```

#### التحقق من تشغيل Cron
```bash
# عرض Cron Jobs المفعلة
crontab -l
```

---

### 7️⃣ **إعداد Queue Workers (اختياري لكن موصى به)** 🔄

لتحسين الأداء وإرسال الإيميلات بشكل أسرع:

#### إنشاء جدول queue
```bash
php artisan queue:table
php artisan migrate
```

#### تشغيل Queue Worker
إذا كان لديك صلاحيات Supervisor:

**ملف Supervisor: `/etc/supervisor/conf.d/laravel-worker.conf`**
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/k4c69o7wqcc3/public_html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=k4c69o7wqcc3
numprocs=2
redirect_stderr=true
stdout_logfile=/home/k4c69o7wqcc3/worker.log
stopwaitsecs=3600
```

ثم:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

**بدون Supervisor (استخدم Cron):**
```bash
# أضف هذا للـ crontab
* * * * * cd /home/k4c69o7wqcc3/public_html && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

---

## 🎯 الأوامر التي سيشغلها Cron تلقائياً:

### 1. `exams:create-reminders`
- **يشتغل:** كل ساعة
- **الوظيفة:** يبحث عن امتحانات مجدولة جديدة وينشئ التذكيرات

### 2. `exams:send-reminders`
- **يشتغل:** كل دقيقة
- **الوظيفة:** يرسل التذكيرات التي حان وقتها

---

## 🔍 استكشاف الأخطاء

### المشكلة: Cron لا يعمل
**الحل:**
```bash
# تحقق من مسار PHP
which php
# استخدم المسار الكامل في Cron
/usr/local/bin/php artisan schedule:run

# تحقق من صلاحيات الملفات
chmod -R 755 /home/k4c69o7wqcc3/public_html
chown -R k4c69o7wqcc3:k4c69o7wqcc3 /home/k4c69o7wqcc3/public_html
```

### المشكلة: الإيميلات لا ترسل
**الحل:**
```bash
# اختبر إعدادات البريد
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
>>> exit

# تحقق من Laravel logs
tail -f storage/logs/laravel.log
```

### المشكلة: "Class 'ExamReminder' not found"
**الحل:**
```bash
composer dump-autoload
php artisan cache:clear
php artisan config:clear
```

---

## 📊 مراقبة النظام

### التحقق من التذكيرات المرسلة
```sql
SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN sent = 1 THEN 1 ELSE 0 END) as sent,
    SUM(CASE WHEN sent = 0 THEN 1 ELSE 0 END) as pending
FROM exam_reminders;
```

### مراجعة آخر التذكيرات
```sql
SELECT 
    er.*,
    e.title as exam_title,
    s.full_name as student_name
FROM exam_reminders er
JOIN exams e ON er.exam_id = e.id
JOIN students s ON er.student_id = s.id
ORDER BY er.scheduled_for DESC
LIMIT 20;
```

---

## ✅ Checklist النهائي

- [ ] تم رفع جميع الملفات على السيرفر
- [ ] تم تشغيل `php artisan migrate`
- [ ] تم إعداد `.env` مع بيانات البريد الصحيحة
- [ ] تم إنشاء Cron Job يشتغل كل دقيقة
- [ ] تم اختبار `php artisan exams:create-reminders`
- [ ] تم اختبار إرسال إيميل تجريبي
- [ ] تم مراقبة Logs للتأكد من عدم وجود أخطاء
- [ ] (اختياري) تم إعداد Queue Workers

---

## 🎉 بعد الإعداد

النظام سيعمل تلقائياً:

✅ **كل ساعة:** يفحص الامتحانات المجدولة وينشئ تذكيرات جديدة
✅ **كل دقيقة:** يرسل التذكيرات التي حان وقتها

**التذكيرات ترسل تلقائياً:**
- قبل 24 ساعة ⏰
- قبل 12 ساعة ⏰
- قبل 6 ساعات ⏰
- قبل 90 دقيقة ⏰
- قبل 10 دقائق ⏰

---

## 📞 للدعم

إذا واجهت مشاكل، تحقق من:
1. `storage/logs/laravel.log`
2. Cron logs في `/home/k4c69o7wqcc3/cron.log`
3. إعدادات البريد في `.env`

---

**تم! 🎊 نظام التذكيرات جاهز للعمل!**

