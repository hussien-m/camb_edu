# ⚡ دليل البدء السريع - Quick Start Guide

## 🚀 خطوات سريعة لنشر الموقع (5 دقائق)

---

## الخطوة 1️⃣: رفع الملفات (2 دقيقة)

```bash
# ارفع جميع الملفات عبر FTP أو Git
git clone your-repo.git
cd your-project
```

---

## الخطوة 2️⃣: إعداد .env (1 دقيقة)

```bash
# انسخ .env.example
cp .env.example .env

# عدّل هذه السطور فقط:
nano .env
```

**غيّر هذه القيم:**
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_HOST=smtp.gmail.com
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

---

## الخطوة 3️⃣: تثبيت وإعداد (2 دقيقة)

```bash
# نفذ هذه الأوامر بالترتيب:
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ✅ تم! الموقع جاهز

افتح المتصفح: `https://yourdomain.com`

---

## 🔐 تسجيل الدخول الأول

```
Admin Panel: https://yourdomain.com/admin
Email: admin@example.com
Password: password

⚠️ غيّر كلمة السر فوراً!
```

---

## 🆘 حل مشكلة سريع

### الصفحة تعرض خطأ 500؟
```bash
chmod -R 775 storage bootstrap/cache
php artisan optimize:clear
php artisan optimize
```

### الصور لا تظهر؟
```bash
php artisan storage:link
chmod -R 775 storage/app/public
```

### Email لا يعمل؟
```bash
# اختبر:
php artisan tinker
>>> Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
```

---

## 📞 دعم إضافي

راجع الملفات التفصيلية:
- `PRODUCTION_RECOMMENDATIONS.md` - توصيات كاملة
- `DEPLOYMENT_COMMANDS.md` - جميع الأوامر
- `ENV_PRODUCTION_GUIDE.md` - دليل .env

---

## ⚡ One-Line Deploy (متقدم)

```bash
composer install --no-dev && php artisan key:generate && php artisan storage:link && php artisan migrate --force && php artisan optimize
```

**🎉 مبروك! موقعك الآن على الهواء!**

