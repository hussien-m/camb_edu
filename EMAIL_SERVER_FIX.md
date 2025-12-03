# حل مشكلة إرسال البريد الإلكتروني على السيرفر

## المشكلة
الموقع يعمل بشكل صحيح على localhost لكن على السيرفر يظهر خطأ:
```
Error: Failed to send email: Connection could not be established with host "smtp.office365.com:587": stream_socket_client(): Unable to connect to smtp.office365.com:587 (Connection timed out)
```

## الحلول المطبقة

### 1. تحديث إعدادات البريد الإلكتروني (`config/mail.php`)
- إضافة `timeout` أطول (60 ثانية بدلاً من null)
- هذا يساعد في حالات الاتصال البطيء على السيرفر

### 2. إنشاء MailServiceProvider مخصص (`app/Providers/MailServiceProvider.php`)
- إضافة stream context options لتحسين التوافق مع السيرفر
- تعيين إعدادات SSL/TLS افتراضية

### 3. تحديث SettingsController
- إضافة retry mechanism (محاولة إعادة الإرسال)
- إضافة timeout أطول للاتصالات
- تحسين معالجة الأخطاء

## الإعدادات المطلوبة في ملف `.env` على السيرفر

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Your Name"
MAIL_TIMEOUT=60
MAIL_VERIFY_PEER=true
MAIL_VERIFY_PEER_NAME=true
```

## حلول إضافية في حالة استمرار المشكلة

### 1. التحقق من Firewall
تأكد من أن السيرفر يسمح بالاتصال بالمنفذ 587:
```bash
telnet smtp.office365.com 587
```

### 2. استخدام منفذ بديل
جرب استخدام المنفذ 25 أو 465:
```env
MAIL_PORT=25
# أو
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### 3. تعطيل التحقق من SSL (فقط للاختبار)
```env
MAIL_VERIFY_PEER=false
MAIL_VERIFY_PEER_NAME=false
```

### 4. استخدام SMTP بديل
إذا استمرت المشكلة، جرب استخدام SMTP بديل مثل:
- SendGrid
- Mailgun
- Amazon SES

## خطوات التطبيق على السيرفر

1. رفع الملفات المحدثة:
   - `config/mail.php`
   - `app/Providers/MailServiceProvider.php`
   - `app/Http/Controllers/Admin/SettingsController.php`
   - `bootstrap/providers.php`

2. تحديث ملف `.env` على السيرفر بالإعدادات المذكورة أعلاه

3. مسح الكاش:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

4. اختبار إرسال البريد من لوحة التحكم

## ملاحظات مهمة

- تأكد من أن السيرفر يدعم SSL/TLS
- تأكد من أن عنوان IP للسيرفر غير محظور من قبل Office365
- قد تحتاج إلى تفعيل "App Passwords" في Office365 إذا كان لديك تفعيل المصادقة الثنائية
- تأكد من أن حساب Office365 يسمح بالوصول من التطبيقات الخارجية

