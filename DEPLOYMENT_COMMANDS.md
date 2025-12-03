# 🚀 أوامر النشر السريعة - Quick Deployment Commands

## 📋 الأوامر الأساسية للسيرفر

---

## 1️⃣ بعد رفع الملفات على السيرفر

```bash
# 1. تثبيت Dependencies (بدون dev packages)
composer install --optimize-autoloader --no-dev

# 2. توليد Application Key (إذا لم يكن موجود)
php artisan key:generate

# 3. إنشاء Storage Link
php artisan storage:link

# 4. تشغيل Migrations
php artisan migrate --force

# 5. تشغيل Seeders (اختياري - أول مرة فقط)
php artisan db:seed --force

# 6. تشغيل جميع الـ Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. مسح old cache (إذا كان هناك مشاكل)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 2️⃣ عند التحديث (Update)

```bash
# 1. تفعيل Maintenance Mode
php artisan down

# 2. سحب آخر تحديثات (Git)
git pull origin main

# 3. تحديث Dependencies
composer install --optimize-autoloader --no-dev

# 4. تشغيل Migrations الجديدة
php artisan migrate --force

# 5. إعادة بناء Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. إلغاء Maintenance Mode
php artisan up
```

---

## 3️⃣ حل المشاكل الشائعة (Troubleshooting)

### مشكلة: "500 Internal Server Error"
```bash
# مسح جميع الـ Cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# تأكد من الصلاحيات
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### مشكلة: "No application encryption key"
```bash
php artisan key:generate
php artisan config:cache
```

### مشكلة: "The storage link does not exist"
```bash
php artisan storage:link
```

### مشكلة: الصور لا تظهر
```bash
# تأكد من Storage Link
ls -la public/storage

# إذا لم يكن موجود، أنشئه
php artisan storage:link

# تأكد من الصلاحيات
chmod -R 775 storage/app/public
```

---

## 4️⃣ Cron Jobs (المهام المجدولة)

### إضافة Cron Job للـ Laravel Scheduler
```bash
# افتح crontab
crontab -e

# أضف هذا السطر:
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 5️⃣ صلاحيات الملفات (File Permissions)

```bash
# صلاحيات المجلدات
find /path/to/project -type d -exec chmod 755 {} \;

# صلاحيات الملفات
find /path/to/project -type f -exec chmod 644 {} \;

# صلاحيات خاصة لـ storage و bootstrap/cache
chmod -R 775 storage bootstrap/cache

# تغيير المالك (استبدل www-data بمستخدم Apache/Nginx)
chown -R www-data:www-data storage bootstrap/cache
```

---

## 6️⃣ Nginx Configuration (إذا كنت تستخدم Nginx)

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /path/to/your/project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 7️⃣ Apache Configuration (إذا كنت تستخدم Apache)

### تأكد من تفعيل mod_rewrite
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Virtual Host Configuration
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /path/to/your/project/public

    <Directory /path/to/your/project/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

---

## 8️⃣ SSL Configuration (Let's Encrypt - مجاني)

```bash
# تثبيت Certbot
sudo apt update
sudo apt install certbot python3-certbot-apache

# توليد SSL Certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# تجديد تلقائي
sudo certbot renew --dry-run
```

---

## 9️⃣ Database Backup (نسخ احتياطي)

### Backup يدوي
```bash
# MySQL/MariaDB
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# مع ضغط
mysqldump -u username -p database_name | gzip > backup_$(date +%Y%m%d_%H%M%S).sql.gz
```

### Backup تلقائي (Cron)
```bash
# أضف هذا في crontab
0 2 * * * mysqldump -u username -p'password' database_name | gzip > /backups/db_$(date +\%Y\%m\%d).sql.gz
```

### Restore من Backup
```bash
# من ملف عادي
mysql -u username -p database_name < backup.sql

# من ملف مضغوط
gunzip < backup.sql.gz | mysql -u username -p database_name
```

---

## 🔟 Monitoring & Logs

### عرض آخر 100 سطر من الـ Logs
```bash
tail -n 100 storage/logs/laravel.log
```

### متابعة الـ Logs بشكل حي
```bash
tail -f storage/logs/laravel.log
```

### مسح الـ Logs القديمة
```bash
# مسح logs أقدم من 30 يوم
find storage/logs -name "*.log" -type f -mtime +30 -delete
```

### فحص حجم الـ Logs
```bash
du -sh storage/logs/
```

---

## 1️⃣1️⃣ Performance Optimization

### Optimize Composer Autoload
```bash
composer dump-autoload --optimize
```

### OPcache Configuration (php.ini)
```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### Laravel Optimization
```bash
# تشغيل جميع optimizations دفعة واحدة
php artisan optimize

# مسح جميع optimizations (للتطوير)
php artisan optimize:clear
```

---

## 1️⃣2️⃣ Security Checklist

```bash
# 1. تأكد من .env غير قابل للوصول
curl https://yourdomain.com/.env
# يجب أن يرجع 404 أو 403

# 2. تأكد من composer.json غير قابل للوصول
curl https://yourdomain.com/composer.json
# يجب أن يرجع 404 أو 403

# 3. فحص صلاحيات الملفات
ls -la storage/
ls -la bootstrap/cache/

# 4. تأكد من APP_DEBUG=false
php artisan tinker
>>> config('app.debug');
# يجب أن يرجع false
```

---

## 1️⃣3️⃣ Quick Commands Reference

| الأمر | الوصف |
|------|-------|
| `php artisan down` | تفعيل maintenance mode |
| `php artisan up` | إلغاء maintenance mode |
| `php artisan optimize` | تحسين الأداء (cache all) |
| `php artisan optimize:clear` | مسح جميع الـ cache |
| `php artisan migrate:status` | فحص حالة migrations |
| `php artisan db:seed --class=ClassName` | تشغيل seeder معين |
| `php artisan queue:work` | تشغيل queue worker |
| `php artisan schedule:run` | تشغيل scheduled tasks |

---

## ⚡ One-Line Deployment Script

```bash
# نسخ هذا السطر واحد وتنفيذه بعد كل update
git pull && composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan optimize && php artisan queue:restart
```

---

## 🔄 Rollback Plan (خطة العودة للنسخة السابقة)

```bash
# 1. تفعيل maintenance mode
php artisan down

# 2. العودة للـ commit السابق
git reset --hard HEAD~1

# 3. إعادة تثبيت dependencies
composer install --no-dev

# 4. العودة لـ migration سابق (إذا لزم)
php artisan migrate:rollback --step=1

# 5. إعادة بناء cache
php artisan optimize

# 6. إلغاء maintenance mode
php artisan up
```

---

## 📞 مشاكل شائعة وحلولها

### المشكلة: "Class not found"
**الحل:**
```bash
composer dump-autoload
php artisan optimize:clear
```

### المشكلة: "Too many connections"
**الحل:**
```bash
# في .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### المشكلة: "CSRF token mismatch"
**الحل:**
```bash
php artisan config:clear
php artisan cache:clear
# امسح cookies المتصفح
```

### المشكلة: "Storage link already exists"
**الحل:**
```bash
rm public/storage
php artisan storage:link
```

---

## ✅ Final Checklist Before Going Live

- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] SSL Certificate installed
- [ ] Database backup configured
- [ ] All cache commands executed
- [ ] File permissions correct (755/644)
- [ ] Storage link exists
- [ ] Cron jobs configured
- [ ] Error logs monitored
- [ ] Performance tested

---

**حظاً موفقاً في الإطلاق! 🚀**

