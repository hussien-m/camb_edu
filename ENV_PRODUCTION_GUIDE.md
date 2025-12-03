# 🔐 دليل إعدادات .env للإنتاج

## ⚠️ إعدادات ضرورية يجب تغييرها

---

## 1️⃣ الإعدادات الأساسية

```bash
APP_NAME="Cambridge British Int College"
APP_ENV=production          # ⚠️ مهم جداً
APP_KEY=base64:xxxxx...     # استخدم: php artisan key:generate
APP_DEBUG=false             # ⚠️ يجب أن يكون false في الإنتاج
APP_URL=https://yourdomain.com  # ⚠️ استخدم HTTPS
```

### ❌ أخطاء شائعة:
- **لا تترك** `APP_DEBUG=true` في الإنتاج (يكشف معلومات حساسة)
- **لا تترك** `APP_ENV=local` في الإنتاج
- **لا تنسَ** تغيير `APP_URL` للدومين الحقيقي

---

## 2️⃣ قاعدة البيانات

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_production_database
DB_USERNAME=your_db_user
DB_PASSWORD=Your_Super_Strong_Password_2024!@#  # ⚠️ كلمة سر قوية
```

### ✅ نصائح الأمان:
- استخدم كلمة سر **16+ حرف**
- اخلط بين: أحرف كبيرة، صغيرة، أرقام، رموز
- **لا تستخدم** كلمات سر سهلة مثل: `password`, `123456`, `admin`
- **لا تستخدم** نفس كلمة سر حسابك الشخصي

### 🔒 مثال كلمة سر قوية:
```
Kf9$mL2#Qx7@Zt5!Pw3&Bv8
```

---

## 3️⃣ البريد الإلكتروني (SMTP)

### أ. Gmail SMTP (موصى به)
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password  # ⚠️ استخدم App Password (ليس كلمة سر Gmail)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### 📧 كيفية الحصول على Gmail App Password:
1. اذهب إلى: https://myaccount.google.com/security
2. فعّل "2-Step Verification"
3. اذهب إلى "App passwords"
4. اختر "Mail" و "Other device"
5. انسخ الـ 16 حرف وضعها في `MAIL_PASSWORD`

### ب. SMTP آخر (مثل Mailgun, SendGrid)
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
```

---

## 4️⃣ إعدادات الأداء

```bash
# Logging
LOG_CHANNEL=daily           # حفظ logs يومياً
LOG_LEVEL=error            # تسجيل الأخطاء فقط

# Cache
CACHE_DRIVER=file          # أو redis للأداء الأفضل

# Session
SESSION_DRIVER=file        # أو redis
SESSION_LIFETIME=120       # 120 دقيقة

# Queue
QUEUE_CONNECTION=sync      # أو redis للأداء الأفضل
```

### 🚀 للأداء العالي (اختياري):
```bash
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## 5️⃣ الأمان الإضافي (اختياري)

```bash
# Force HTTPS
FORCE_HTTPS=true

# Secure Cookies
SESSION_SECURE_COOKIE=true

# HSTS
HSTS_ENABLED=true
HSTS_MAX_AGE=31536000
```

---

## 6️⃣ خطوات التطبيق على السيرفر

### 1. انسخ .env.example إلى .env
```bash
cp .env.example .env
```

### 2. عدّل الملف
```bash
nano .env
# أو
vim .env
```

### 3. غيّر جميع الإعدادات المذكورة أعلاه

### 4. ولّد Application Key
```bash
php artisan key:generate
```

### 5. امسح الـ Cache
```bash
php artisan config:cache
php artisan cache:clear
```

### 6. اختبر الإعدادات
```bash
# اختبر قاعدة البيانات
php artisan migrate:status

# اختبر Email
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

---

## 7️⃣ فحص الأمان (Security Check)

### ✅ قائمة التحقق:
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_KEY` تم توليده
- [ ] كلمة سر قاعدة البيانات قوية (16+ حرف)
- [ ] `APP_URL` يستخدم HTTPS
- [ ] SMTP يعمل بشكل صحيح
- [ ] `LOG_LEVEL=error`
- [ ] لا توجد بيانات حساسة في الملف

### ⚠️ أشياء يجب تجنبها:
- ❌ لا ترفع `.env` على Git أبداً
- ❌ لا تشارك `.env` عبر Email
- ❌ لا تترك `APP_DEBUG=true`
- ❌ لا تستخدم كلمات سر ضعيفة

---

## 8️⃣ النسخ الاحتياطي

### احتفظ بنسخة احتياطية من .env
```bash
# على السيرفر، انسخ .env إلى مكان آمن
cp .env /backup/env_backup_$(date +%Y%m%d).txt

# اجعله غير قابل للقراءة إلا من root
chmod 600 /backup/env_backup_*.txt
```

---

## 9️⃣ إعدادات محددة لـ cPanel/Shared Hosting

إذا كنت على shared hosting:

```bash
# Database
DB_HOST=localhost  # أو IP محدد من cPanel

# Mail
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com  # من cPanel
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-cpanel-email-password
MAIL_ENCRYPTION=tls

# URL
APP_URL=https://yourdomain.com  # بدون مجلد public
```

---

## 🔟 استكشاف الأخطاء

### المشكلة: "No application encryption key"
```bash
php artisan key:generate
php artisan config:cache
```

### المشكلة: البريد لا يُرسل
```bash
# اختبر الاتصال بـ SMTP
telnet smtp.gmail.com 587

# في Laravel:
php artisan tinker
>>> config('mail');  # تحقق من الإعدادات
```

### المشكلة: قاعدة البيانات لا تتصل
```bash
# اختبر الاتصال
mysql -h DB_HOST -u DB_USERNAME -p

# تحقق من الإعدادات في Laravel
php artisan tinker
>>> config('database.connections.mysql');
```

---

## 1️⃣1️⃣ Template كامل للـ .env (Production)

```bash
# APPLICATION
APP_NAME="Cambridge British Int College"
APP_ENV=production
APP_KEY=  # سيتم توليده
APP_DEBUG=false
APP_URL=https://yourdomain.com

# LOGGING
LOG_CHANNEL=daily
LOG_LEVEL=error

# DATABASE
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

# BROADCAST
BROADCAST_DRIVER=log

# CACHE
CACHE_DRIVER=file

# QUEUE
QUEUE_CONNECTION=sync

# SESSION
SESSION_DRIVER=file
SESSION_LIFETIME=120

# MAIL
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

---

## ✅ الخلاصة

### الأولويات القصوى:
1. ✅ `APP_DEBUG=false`
2. ✅ `APP_ENV=production`
3. ✅ كلمة سر قوية للـ Database
4. ✅ HTTPS في `APP_URL`
5. ✅ اختبار Email

### بعد الانتهاء:
```bash
# امسح جميع الـ cache
php artisan optimize:clear

# أعد بناء الـ cache
php artisan optimize

# اختبر الموقع
curl -I https://yourdomain.com
```

---

**الآن .env جاهز للإنتاج! 🔐**

