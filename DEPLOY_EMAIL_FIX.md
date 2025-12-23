# 🚀 Deploy Email Fix - Quick Commands

## على السيرفر (بالترتيب):

### 1. رفع الملفات
```bash
# ارفع الملفات المُعدَّلة:
# - app/Console/Commands/SendExamReminders.php
# - app/Http/Controllers/Admin/ExamReminderController.php
# - routes/admin.php
# - resources/views/admin/exam-reminders/index.blade.php
```

### 2. تحديث الـ Cache
```bash
cd /home/k4c69o7wqcc3/public_html

php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. اختبار الإرسال فوراً
```bash
# إنشاء التذكيرات
php artisan exams:create-reminders

# عرض التذكيرات المستحقة
php artisan exams:send-reminders
```

### 4. فحص النتيجة
```bash
# فحص آخر 50 سطر من الـ Log
tail -50 storage/logs/laravel.log

# أو مشاهدة live:
tail -f storage/logs/laravel.log
```

---

## 🎯 اختبار من المتصفح:

### 1. افتح صفحة التذكيرات:
```
https://cambridgecollage.com/admin/exam-reminders
```

### 2. اختبر إرسال إيميل:
- اضغط "Test Email"
- أدخل بريدك الإلكتروني
- اضغط "Send Test Email"
- افحص البريد الوارد

### 3. إنشاء التذكيرات:
- اضغط "Create Reminders"
- انتظر الرسالة الخضراء

### 4. إرسال التذكيرات المستحقة:
- اضغط "Send Due Reminders"
- تحقق من عدد الرسائل المُرسلة

### 5. إرسال تذكير واحد:
- في جدول "Due Reminders" أو "Upcoming Reminders"
- اضغط زر ✈️ بجانب التذكير
- تأكد من الإرسال
- سيتم الإرسال فوراً!

---

## 🔍 استكشاف الأخطاء:

### إذا لم تصل الرسائل:

#### 1. تحقق من الإعدادات:
```bash
php artisan tinker
>>> config('mail.from.address')
>>> config('mail.from.name')
>>> config('mail.host')
>>> exit
```

#### 2. اختبار مباشر:
```bash
php artisan tinker
>>> use App\Services\Mail\ProfessionalMailService;
>>> ProfessionalMailService::send('your-email@gmail.com', 'Test', '<h1>Test Email</h1>');
>>> exit
```

#### 3. فحص Logs:
```bash
grep "Email sent" storage/logs/laravel.log | tail -20
grep "Failed" storage/logs/laravel.log | tail -20
```

---

## ✅ علامات النجاح:

في الـ Log يجب أن ترى:
```
✓ Sent 24h reminder to student@email.com for exam: Example Exam
Email sent successfully via SMTP to: student@email.com
```

في البريد الإلكتروني يجب أن ترى:
- رسالة جميلة بألوان gradient
- عنوان الامتحان
- الوقت المتبقي
- جميع تفاصيل الامتحان
- زر للذهاب لصفحة الامتحان

---

## 📞 إذا واجهت مشاكل:

أرسل:
1. آخر 50 سطر من `storage/logs/laravel.log`
2. لقطة شاشة من `/admin/exam-reminders`
3. نتيجة الأمر: `php artisan config:show mail`

---

**الآن النظام يستخدم نفس طريقة استعادة كلمة السر - يجب أن يعمل بشكل مثالي! ✨**

