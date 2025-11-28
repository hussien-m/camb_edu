# Fix Routes on Server

## المشكلة
```
Route [student.login] not defined
```

## الحلول بالترتيب

### 1. مسح الـ Cache (الحل الأسرع)
اتصل بالسيرفر عبر SSH وشغّل:

```bash
cd /path/to/your/project
php artisan route:clear
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 2. إعادة تحميل الـ Routes
```bash
php artisan route:cache
php artisan config:cache
```

### 3. التحقق من وجود الملفات
تأكد أن هذه الملفات موجودة على السيرفر:

✅ `routes/student.php`
✅ `bootstrap/app.php`
✅ `app/Http/Middleware/StudentMiddleware.php`
✅ `app/Http/Controllers/Student/Auth/LoginController.php`

### 4. التحقق من Permissions
```bash
chmod -R 755 routes/
chmod -R 755 bootstrap/cache/
chmod -R 775 storage/
```

### 5. إعادة تحميل Autoload
```bash
composer dump-autoload
php artisan optimize:clear
```

### 6. التحقق من الـ Routes
```bash
php artisan route:list --name=student
```

يجب أن ترى:
```
student.login
student.register
student.dashboard
student.logout
...الخ
```

### 7. إذا استخدمت Deployment Script

أضف هذه الأوامر في السكريبت:

```bash
#!/bin/bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan route:cache
php artisan config:cache
php artisan view:cache
php artisan optimize
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 8. تحقق من .env في السيرفر

تأكد أن:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# لا تستخدم route:cache في development
```

### 9. إعادة تشغيل الـ Web Server

إذا كنت تستخدم:

**Nginx:**
```bash
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm  # أو الإصدار المناسب
```

**Apache:**
```bash
sudo systemctl restart apache2
```

### 10. للتأكد 100%

شغّل هذا الأمر على السيرفر:
```bash
php artisan tinker
>>> Route::has('student.login')
# يجب أن يرجع true
```

---

## أمر واحد لحل كل شيء

```bash
cd /path/to/project && \
php artisan optimize:clear && \
composer dump-autoload && \
php artisan optimize && \
php artisan route:list | grep student
```

---

## ملاحظات مهمة

⚠️ **لا تستخدم `route:cache` في development** - فقط في production
⚠️ **تأكد من رفع جميع الملفات** - خاصة routes/student.php
⚠️ **تحقق من composer.json** - أن جميع packages مثبتة
⚠️ **راجع logs** في `storage/logs/laravel.log`

---

## إذا لم يحل أي من السابق

ارسل لي محتوى هذه الملفات من السيرفر:
1. `php artisan route:list`
2. `cat storage/logs/laravel.log | tail -50`
3. `ls -la routes/`
