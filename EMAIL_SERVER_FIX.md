# حل مشكلة إرسال البريد الإلكتروني على السيرفر

## المشكلة
الموقع يعمل بشكل صحيح على localhost لكن على السيرفر (الاستضافة) لا يرسل البريد الإلكتروني:
```
Error: Failed to send email: Connection could not be established with host "smtp.office365.com:587": stream_socket_client(): Unable to connect to smtp.office365.com:587 (Connection timed out)
```

## الحلول المطبقة

### 1. تحديث إعدادات البريد الإلكتروني (`config/mail.php`)
- إضافة `timeout` أطول (60 ثانية بدلاً من null)
- هذا يساعد في حالات الاتصال البطيء على السيرفر

### 2. إنشاء MailServiceProvider مخصص (`app/Providers/MailServiceProvider.php`)
- إضافة stream context options لتحسين التوافق مع السيرفر
- تعيين إعدادات SSL/TLS افتراضية مع SNI
- تعيين socket timeout أطول
- إضافة إعدادات PHP إضافية للاستضافات المشتركة

### 3. تحديث SettingsController
- إضافة retry mechanism محسّن (3 محاولات بدلاً من 2)
- إضافة معلومات تشخيصية شاملة (PHP version, OpenSSL, etc.)
- تحسين معالجة الأخطاء مع رسائل مساعدة
- إضافة اقتراحات تلقائية بناءً على نوع الخطأ

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

### 1. التحقق من Firewall على الاستضافة
تأكد من أن السيرفر يسمح بالاتصال بالمنفذ 587:
```bash
telnet smtp.office365.com 587
```
أو استخدم PHP للاختبار:
```php
$connection = @fsockopen('smtp.office365.com', 587, $errno, $errstr, 10);
if ($connection) {
    echo "Connection successful";
    fclose($connection);
} else {
    echo "Connection failed: $errstr ($errno)";
}
```

### 2. استخدام منفذ بديل (الأكثر شيوعاً على الاستضافات المشتركة)
جرب استخدام المنفذ 465 مع SSL:
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

أو المنفذ 25:
```env
MAIL_PORT=25
MAIL_ENCRYPTION=null
```

### 3. تعطيل التحقق من SSL (فقط للاختبار على الاستضافة)
```env
MAIL_VERIFY_PEER=false
MAIL_VERIFY_PEER_NAME=false
MAIL_SSL_ALLOW_SELF_SIGNED=true
```

### 4. استخدام SMTP بديل (الحل الأفضل للاستضافات المشتركة)
إذا استمرت المشكلة، جرب استخدام SMTP بديل مثل:
- **SendGrid** (مجاني حتى 100 إيميل/يوم)
- **Mailgun** (مجاني حتى 5000 إيميل/شهر)
- **Amazon SES** (رخيص جداً)
- **SMTP الخاص بالاستضافة** (عادة يعمل بشكل أفضل)

### 5. استخدام SMTP الخاص بالاستضافة
معظم الاستضافات توفر SMTP خاص بها. استخدم:
```env
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### 6. التحقق من إعدادات PHP على الاستضافة
تأكد من تفعيل:
- `allow_url_fopen = On`
- `openssl` extension مفعّل
- `socket` extension مفعّل

### 7. استخدام Queue للإرسال (للحلول طويلة المدى)
إذا كان الإرسال يستغرق وقتاً طويلاً، استخدم Queue:
```bash
php artisan queue:work
```

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

## ملاحظات مهمة للاستضافات المشتركة

### مشاكل شائعة على الاستضافات المشتركة:
1. **Firewall يحجب المنفذ 587**: استخدم المنفذ 465 أو 25
2. **IP محظور**: بعض مزودي البريد يحظرون IPs الاستضافات المشتركة
3. **Rate Limiting**: الاستضافات المشتركة قد تحد من عدد الاتصالات
4. **SSL/TLS Issues**: قد تحتاج لتعطيل التحقق من SSL

### حلول Office365 خاصة:
- تأكد من أن عنوان IP للسيرفر غير محظور من قبل Office365
- قد تحتاج إلى تفعيل "App Passwords" في Office365 إذا كان لديك تفعيل المصادقة الثنائية
- تأكد من أن حساب Office365 يسمح بالوصول من التطبيقات الخارجية
- Office365 قد يحظر بعض الاستضافات المشتركة - استخدم SMTP بديل في هذه الحالة

### أفضل الممارسات:
- استخدم SMTP الخاص بالاستضافة إذا كان متاحاً
- استخدم Queue للإرسال لتجنب timeout
- استخدم SMTP service مثل SendGrid أو Mailgun للاستضافات المشتركة
- راقب logs في `storage/logs/laravel.log` لمزيد من التفاصيل

