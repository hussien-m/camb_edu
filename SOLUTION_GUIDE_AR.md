# دليل حل مشكلة إرسال البريد الإلكتروني على الاستضافة

## المشكلة
البريد الإلكتروني يعمل على localhost لكن لا يعمل على الاستضافة (السيرفر).

## الحلول المطبقة

### ✅ الحل 1: تحديث إعدادات SSL/TLS (تم التطبيق)
تم تعطيل التحقق من SSL افتراضياً للاستضافات المشتركة:
- `MAIL_VERIFY_PEER=false`
- `MAIL_VERIFY_PEER_NAME=false`
- `MAIL_SSL_ALLOW_SELF_SIGNED=true`

### ✅ الحل 2: إضافة نظام Fallback (تم التطبيق)
إذا فشل SMTP، سيتم المحاولة تلقائياً باستخدام:
1. **sendmail** (يعمل على معظم الاستضافات)
2. **PHP mail()** (كحل أخير)

### ✅ الحل 3: تحسين إعدادات Timeout (تم التطبيق)
- تقليل timeout إلى 30 ثانية (أفضل للاستضافات المشتركة)
- إضافة retry mechanism (3 محاولات)

## خطوات الحل السريع

### الخطوة 1: تحديث ملف `.env` على الاستضافة

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@domain.com
MAIL_FROM_NAME="Your Name"
MAIL_TIMEOUT=30
MAIL_VERIFY_PEER=false
MAIL_VERIFY_PEER_NAME=false
MAIL_SSL_ALLOW_SELF_SIGNED=true
```

### الخطوة 2: جرب منافذ بديلة

إذا لم يعمل المنفذ 587، جرب:

**المنفذ 465 مع SSL:**
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

**المنفذ 25 بدون تشفير:**
```env
MAIL_PORT=25
MAIL_ENCRYPTION=null
```

### الخطوة 3: استخدام SMTP الخاص بالاستضافة

معظم الاستضافات توفر SMTP خاص بها. استخدم:
```env
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### الخطوة 4: استخدام SMTP Service (الحل الأفضل)

إذا استمرت المشكلة، استخدم خدمة SMTP خارجية:

#### SendGrid (مجاني حتى 100 إيميل/يوم)
1. سجل في https://sendgrid.com
2. احصل على API Key
3. استخدم:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

#### Mailgun (مجاني حتى 5000 إيميل/شهر)
1. سجل في https://mailgun.com
2. احصل على SMTP credentials
3. استخدم:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-mailgun-username
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
```

## اختبار الحل

1. ارفع الملفات المحدثة على الاستضافة
2. مسح الكاش:
```bash
php artisan config:clear
php artisan cache:clear
```
3. استخدم خاصية "Send Test Email" من لوحة التحكم
4. إذا فشل SMTP، سيتم المحاولة تلقائياً باستخدام sendmail أو PHP mail()

## ملاحظات مهمة

### لماذا لا يعمل SMTP على الاستضافة؟
1. **Firewall يحجب المنفذ 587**: معظم الاستضافات المشتركة تحجب الاتصالات الخارجية
2. **IP محظور**: Office365 قد يحظر IPs الاستضافات المشتركة
3. **Rate Limiting**: الاستضافات المشتركة تحد من عدد الاتصالات

### الحلول الموصى بها حسب الحالة:

| الحالة | الحل الموصى به |
|--------|----------------|
| استضافة مشتركة عادية | استخدم SMTP الخاص بالاستضافة |
| استضافة VPS/Dedicated | استخدم Office365 أو Gmail SMTP |
| تطبيق تجاري | استخدم SendGrid أو Mailgun |
| تطبيق كبير | استخدم Amazon SES |

## استكشاف الأخطاء

### الخطأ: "Connection timed out"
**الحل:**
- جرب المنفذ 465 أو 25
- استخدم SMTP الخاص بالاستضافة
- استخدم SMTP service مثل SendGrid

### الخطأ: "Could not authenticate"
**الحل:**
- تحقق من صحة username/password
- لـ Office365: استخدم App Password إذا كان لديك 2FA
- تأكد من تفعيل "Less secure apps"

### الخطأ: "SSL certificate problem"
**الحل:**
- تأكد من أن `MAIL_VERIFY_PEER=false` في `.env`
- تأكد من أن `MAIL_SSL_ALLOW_SELF_SIGNED=true`

## الملفات المحدثة

1. `app/Providers/MailServiceProvider.php` - إعدادات SSL محسّنة
2. `app/Http/Controllers/Admin/SettingsController.php` - نظام Fallback
3. `app/Services/Mail/AlternativeMailService.php` - خدمة البريد البديلة
4. `config/mail.php` - إعدادات timeout محسّنة

## الدعم

إذا استمرت المشكلة بعد تطبيق جميع الحلول:
1. استخدم ملف `test_smtp_connection.php` لاختبار الاتصال
2. راجع ملف `storage/logs/laravel.log` للأخطاء التفصيلية
3. اتصل بمزود الاستضافة لفتح المنفذ 587 أو استخدام SMTP الخاص بهم

