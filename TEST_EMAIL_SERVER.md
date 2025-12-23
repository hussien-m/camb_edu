# اختبار إرسال الإيميلات على السيرفر
## Test Email Sending on Server

---

## ✅ الإصلاح الذي تم:

تم إزالة `ShouldQueue` من `ExamReminderNotification.php` لأنها كانت تسبب مشاكل مع إعدادات Queue.

---

## 🧪 اختبار إرسال الإيميلات:

### 1️⃣ **اختبار بسيط من Terminal:**

```bash
cd /home/k4c69o7wqcc3/public_html

# اختبار إرسال إيميل تجريبي
php artisan tinker
```

**داخل Tinker:**
```php
// أرسل إيميل تجريبي
Mail::raw('This is a test email from Laravel', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});

// اضغط Enter

// اخرج من tinker
exit
```

---

### 2️⃣ **تحقق من إعدادات `.env`:**

تأكد أن الإعدادات التالية موجودة وصحيحة:

```env
# إعدادات البريد
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yoursite.com
MAIL_FROM_NAME="${APP_NAME}"

# Queue - استخدم sync للتبسيط
QUEUE_CONNECTION=sync
```

---

### 3️⃣ **إعداد Gmail App Password:**

إذا كنت تستخدم Gmail، **يجب** استخدام App Password:

1. اذهب لـ: https://myaccount.google.com/security
2. فعّل **2-Step Verification** (مهم جداً!)
3. اذهب لـ: https://myaccount.google.com/apppasswords
4. اختر App: **Mail**
5. اختر Device: **Other** → اكتب: "Laravel App"
6. انسخ الـ **16-digit password**
7. استخدمه في `MAIL_PASSWORD` بدون مسافات

**مثال:**
```env
MAIL_PASSWORD=abcd efgh ijkl mnop  # ❌ خطأ
MAIL_PASSWORD=abcdefghijklmnop     # ✅ صح
```

---

### 4️⃣ **بدائل Gmail (إذا لم يعمل):**

#### استخدام SMTP الخاص بالسيرفر:
```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### استخدام Mailtrap (للاختبار فقط):
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

سجل حساب في: https://mailtrap.io

---

### 5️⃣ **اختبار التذكيرات بعد التعديل:**

```bash
cd /home/k4c69o7wqcc3/public_html

# امسح الكاش
php artisan cache:clear
php artisan config:clear

# اختبر إرسال التذكيرات
php artisan exams:send-reminders
```

**النتيجة المتوقعة:**
```
Checking for pending exam reminders...
✓ Sent 24h reminder to John Doe for exam: English Exam
✓ Sent 12h reminder to Jane Smith for exam: Math Exam
====================================
Summary:
Sent: 2
====================================
```

---

### 6️⃣ **مراقبة Logs:**

```bash
# شاهد آخر 50 سطر من الـ logs
tail -n 50 /home/k4c69o7wqcc3/public_html/storage/logs/laravel.log

# راقب الـ logs مباشرة (live)
tail -f /home/k4c69o7wqcc3/public_html/storage/logs/laravel.log
```

---

## 🔍 استكشاف الأخطاء الشائعة:

### الخطأ: "Connection could not be established"
**الحل:**
```env
# جرب port 465 بدلاً من 587
MAIL_PORT=465
MAIL_ENCRYPTION=ssl

# أو جرب بدون encryption
MAIL_PORT=587
MAIL_ENCRYPTION=null
```

### الخطأ: "Username and Password not accepted"
**الحل:**
- تأكد من App Password صحيح (16 رقم بدون مسافات)
- تأكد من تفعيل 2-Step Verification في Gmail
- جرب مسح الكاش: `php artisan config:clear`

### الخطأ: "Failed to authenticate"
**الحل:**
```bash
# تأكد من إعدادات .env
php artisan config:show mail

# امسح كل الكاش
php artisan optimize:clear
```

### الإيميل لا يصل لكن لا يوجد أخطاء
**الحل:**
1. تحقق من **Spam/Junk** folder
2. تحقق من Gmail **Sent** folder
3. استخدم Mailtrap للاختبار أولاً
4. تحقق من `storage/logs/laravel.log`

---

## 🎯 اختبار شامل:

### سكريبت اختبار سريع:

```bash
cd /home/k4c69o7wqcc3/public_html

# اختبر كل شيء
php artisan config:clear
php artisan cache:clear

# اختبر إعدادات البريد
php artisan tinker

# داخل tinker:
Mail::raw('Test from Laravel', function($msg) { 
    $msg->to('YOUR_EMAIL@gmail.com')->subject('Test'); 
});

# إذا نجح، اختبر التذكيرات
exit

php artisan exams:send-reminders
```

---

## 📊 التحقق من حالة التذكيرات:

### من phpMyAdmin أو MySQL:

```sql
-- شاهد التذكيرات المعلقة
SELECT 
    er.*,
    e.title as exam_title,
    s.full_name as student_name,
    s.email as student_email
FROM exam_reminders er
JOIN exams e ON er.exam_id = e.id
JOIN students s ON er.student_id = s.id
WHERE er.sent = 0
AND er.scheduled_for <= NOW()
ORDER BY er.scheduled_for;

-- شاهد التذكيرات المرسلة
SELECT 
    er.*,
    e.title,
    s.email
FROM exam_reminders er
JOIN exams e ON er.exam_id = e.id
JOIN students s ON er.student_id = s.id
WHERE er.sent = 1
ORDER BY er.sent_at DESC
LIMIT 10;
```

---

## ✅ Checklist:

- [ ] تم تعديل `ExamReminderNotification.php` (إزالة ShouldQueue)
- [ ] رفع الملف المعدل على السيرفر
- [ ] `php artisan config:clear`
- [ ] `php artisan cache:clear`
- [ ] إعدادات `.env` صحيحة
- [ ] Gmail App Password مفعّل (إذا تستخدم Gmail)
- [ ] اختبار إرسال إيميل بسيط نجح
- [ ] `php artisan exams:send-reminders` اشتغل بدون أخطاء
- [ ] الإيميل وصل فعلاً

---

## 🆘 إذا ما زالت المشكلة موجودة:

شارك معي:
1. محتوى `.env` (بدون كلمات السر)
2. آخر 20 سطر من `storage/logs/laravel.log`
3. نتيجة `php artisan config:show mail`

---

**بالتوفيق! 🚀**

