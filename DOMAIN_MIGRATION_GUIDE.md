# دليل نقل الموقع من cambridgecollage.com إلى cambridge-college.uk

## 📋 نظرة عامة
هذا الدليل يوضح كيفية نقل الموقع من `https://cambridgecollage.com/` إلى `https://cambridge-college.uk/` مع الحفاظ على SEO والفهرسة في محركات البحث.

---

## ✅ الخطوات المطلوبة

### 1. إعدادات Laravel (.env)

#### على الاستضافة (Production):
```env
APP_URL=https://cambridge-college.uk
APP_NAME="Cambridge International College in UK"
```

#### على GitHub (للمستقبل):
- لا تقم بتغيير `.env` في GitHub (يجب أن يبقى في `.gitignore`)
- فقط قم بتحديث `.env.example` إذا لزم الأمر

---

### 2. إعادة التوجيه 301 (301 Redirects)

#### أ. في ملف `.htaccess` في الدومين القديم (cambridgecollage.com)

**ملاحظة مهمة:** يجب إضافة هذا الكود في ملف `.htaccess` الخاص بالدومين القديم على الاستضافة.

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect all traffic from old domain to new domain
    RewriteCond %{HTTP_HOST} ^(www\.)?cambridgecollage\.com$ [NC]
    RewriteRule ^(.*)$ https://cambridge-college.uk/$1 [R=301,L]
    
    # Redirect www to non-www (optional, adjust based on your preference)
    RewriteCond %{HTTP_HOST} ^www\.cambridge-college\.uk$ [NC]
    RewriteRule ^(.*)$ https://cambridge-college.uk/$1 [R=301,L]
</IfModule>
```

#### ب. في ملف `public/.htaccess` في المشروع الجديد

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

### 3. تحديث robots.txt

```txt
# Allow all search engines
User-agent: *
Allow: /

# Disallow admin and private areas
Disallow: /admin/
Disallow: /student/dashboard
Disallow: /student/profile
Disallow: /student/exams
Disallow: /student/certificates
Disallow: /login
Disallow: /register
Disallow: /password/
Disallow: /storage-link

# Disallow vendor and system files
Disallow: /vendor/
Disallow: /.env
Disallow: /bootstrap/cache/

# Allow important pages
Allow: /courses
Allow: /course/
Allow: /page/
Allow: /success-stories

# Sitemap location
Sitemap: https://cambridge-college.uk/sitemap.xml
```

---

### 4. تحديث Google Search Console

#### أ. إضافة الموقع الجديد:
1. اذهب إلى [Google Search Console](https://search.google.com/search-console)
2. أضف الموقع الجديد: `https://cambridge-college.uk`
3. قم بالتحقق من الملكية (Verification)

#### ب. إعداد Change of Address:
1. في الموقع القديم (`cambridgecollage.com`):
   - Settings → Change of Address
   - اختر الموقع الجديد: `cambridge-college.uk`
   - تأكد من أن جميع إعادة التوجيهات 301 تعمل بشكل صحيح

#### ج. إرسال Sitemap الجديد:
1. في الموقع الجديد:
   - Sitemaps → Add a new sitemap
   - أدخل: `https://cambridge-college.uk/sitemap.xml`

---

### 5. تحديث Google reCAPTCHA

1. اذهب إلى [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin)
2. اختر الموقع الحالي
3. أضف الدومين الجديد في قائمة "Domains":
   - `cambridge-college.uk`
   - `www.cambridge-college.uk`
4. احفظ التغييرات

---

### 6. تحديث ملفات الكود

#### أ. تحديث RecaptchaMiddleware.php:
```php
\Log::error('reCAPTCHA: Domain not authorized! Add cambridge-college.uk to Google reCAPTCHA Console.');
```

#### ب. تحديث SettingsController.php:
```php
<a href="https://www.cambridge-college.uk">Visit Website</a> |
<a href="mailto:info@cambridge-college.uk">Contact Support</a>
```

#### ج. تحديث CookiePolicySeeder.php:
```php
<p class="mb-2"><i class="fas fa-envelope text-primary"></i> <strong>Email:</strong> info@cambridge-college.uk</p>
```

---

### 7. تحديث قاعدة البيانات

#### أ. تحديث URLs المخزنة:
```sql
-- تحديث أي URLs مخزنة في قاعدة البيانات (إن وجدت)
UPDATE settings SET value = REPLACE(value, 'cambridgecollage.com', 'cambridge-college.uk') WHERE value LIKE '%cambridgecollage.com%';
UPDATE pages SET content = REPLACE(content, 'cambridgecollage.com', 'cambridge-college.uk') WHERE content LIKE '%cambridgecollage.com%';
```

#### ب. تحديث إعدادات البريد الإلكتروني:
- تأكد من تحديث `contact_email` في جدول `settings` إلى `info@cambridge-college.uk`

---

### 8. تحديث DNS وإعدادات الاستضافة

#### أ. DNS Records:
- أضف A Record يشير إلى IP الخادم
- أضف CNAME Record للـ www إذا لزم الأمر

#### ب. SSL Certificate:
- تأكد من تثبيت شهادة SSL للدومين الجديد
- استخدم Let's Encrypt أو شهادة مدفوعة

#### ج. Document Root:
- تأكد من أن Document Root يشير إلى مجلد `public` في Laravel

---

### 9. اختبار إعادة التوجيه

#### أ. اختبار 301 Redirect:
```bash
curl -I https://cambridgecollage.com/
# يجب أن يعيد: HTTP/1.1 301 Moved Permanently
# Location: https://cambridge-college.uk/
```

#### ب. اختبار الصفحات:
```bash
curl -I https://cambridgecollage.com/courses
# يجب أن يعيد: Location: https://cambridge-college.uk/courses

curl -I https://cambridgecollage.com/page/accreditations
# يجب أن يعيد: Location: https://cambridge-college.uk/page/accreditations
```

---

### 10. تحديث الروابط الخارجية

#### أ. Social Media:
- قم بتحديث الروابط في جميع حسابات التواصل الاجتماعي

#### ب. Google My Business:
- قم بتحديث الموقع في Google My Business

#### ج. Directories:
- قم بتحديث الروابط في جميع الدلائل والمواقع الخارجية

---

### 11. مراقبة النتائج

#### أ. Google Search Console:
- راقب "Coverage" للتأكد من أن جميع الصفحات مفهرسة
- راقب "Performance" لمعرفة تأثير النقل على الترتيب

#### ب. Google Analytics:
- تأكد من تحديث Domain في Google Analytics
- راقب الزيارات والتحويلات

#### ج. Server Logs:
- راقب سجلات الخادم للتأكد من أن إعادة التوجيه تعمل بشكل صحيح

---

## ⚠️ ملاحظات مهمة

1. **لا تحذف الدومين القديم فوراً:**
   - اترك إعادة التوجيه 301 تعمل لمدة 6-12 شهر على الأقل
   - هذا يضمن أن محركات البحث والزوار يجدون الموقع الجديد

2. **احتفظ بنسخة احتياطية:**
   - قبل أي تغييرات، قم بعمل نسخة احتياطية كاملة من قاعدة البيانات والملفات

3. **اختبار شامل:**
   - اختبر جميع الصفحات والوظائف بعد النقل
   - تأكد من أن جميع الروابط الداخلية تعمل

4. **SEO Best Practices:**
   - استخدم 301 Redirects فقط (وليس 302)
   - تأكد من أن جميع الصفحات تعيد التوجيه بشكل صحيح
   - أرسل Sitemap جديد إلى Google Search Console

---

## 📅 جدول زمني مقترح

### الأسبوع الأول:
- [ ] تحديث `.env` على الاستضافة
- [ ] إضافة إعادة التوجيه 301
- [ ] تحديث `robots.txt`
- [ ] تحديث ملفات الكود

### الأسبوع الثاني:
- [ ] إضافة الموقع الجديد في Google Search Console
- [ ] إعداد Change of Address
- [ ] تحديث Google reCAPTCHA
- [ ] اختبار شامل

### الأسبوع الثالث:
- [ ] مراقبة النتائج
- [ ] تحديث الروابط الخارجية
- [ ] إرسال Sitemap جديد

---

## 🔗 روابط مفيدة

- [Google Search Console](https://search.google.com/search-console)
- [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin)
- [301 Redirect Tester](https://www.redirect-checker.org/)

---

## ✅ Checklist النهائي

- [ ] تحديث `.env` على الاستضافة
- [ ] إضافة إعادة التوجيه 301 في `.htaccess`
- [ ] تحديث `robots.txt`
- [ ] تحديث ملفات الكود (RecaptchaMiddleware, SettingsController, CookiePolicySeeder)
- [ ] تحديث قاعدة البيانات (URLs و emails)
- [ ] إضافة الموقع الجديد في Google Search Console
- [ ] إعداد Change of Address
- [ ] تحديث Google reCAPTCHA
- [ ] اختبار إعادة التوجيه
- [ ] اختبار جميع الصفحات
- [ ] إرسال Sitemap جديد
- [ ] تحديث الروابط الخارجية
- [ ] مراقبة النتائج

---

**تاريخ الإنشاء:** 2026-01-03  
**آخر تحديث:** 2026-01-03
