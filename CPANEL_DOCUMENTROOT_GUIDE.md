# 📘 دليل تغيير DocumentRoot في cPanel - خطوة بخطوة

## 🎯 **الهدف:**
تغيير DocumentRoot من `public_html` إلى `public_html/public`

---

## 📸 **الخطوات بالتفصيل:**

### Step 1: تسجيل الدخول لـ cPanel

```
URL: https://cambridgecollage.com:2083
أو: https://server-ip:2083
أو: https://yourhost.com/cpanel

Username: [your cPanel username]
Password: [your cPanel password]
```

---

### Step 2: البحث عن Domains

في الصفحة الرئيسية لـ cPanel:
```
ابحث عن قسم "Domains"
أو استخدم Search: "Domains"
أو: "Domain Manager"
```

---

### Step 3: Manage Domain

```
1. ابحث عن: cambridgecollage.com
2. اضغط "Manage" أو "Edit" بجانبه
```

---

### Step 4: تعديل Document Root

في صفحة Domain Settings:

```
1. ابحث عن حقل: "Document Root"
   
2. ستجد القيمة الحالية:
   public_html

3. غيرها إلى:
   public_html/public
   
4. أو:
   /home/k4c69o7wqcc3/public_html/public
```

**مهم جداً:**
- ✅ أضف `/public` في النهاية فقط
- ❌ لا تحذف `public_html`
- ❌ لا تغير المسار بالكامل

---

### Step 5: حفظ التغييرات

```
1. اضغط "Update" أو "Save"
2. انتظر رسالة التأكيد: "Domain updated successfully"
3. ✅ تم!
```

---

### Step 6: Clear DNS Cache (اختياري)

```
1. في cPanel → "Zone Editor"
2. أو انتظر 5-10 دقائق للتحديث التلقائي
```

---

## 🧪 **الاختبار الفوري:**

### Test 1: Homepage
```bash
# في terminal أو browser:
https://cambridgecollage.com/

# Expected:
✅ الموقع يفتح بشكل طبيعي
```

### Test 2: /public/ URL (يجب أن يعطي 404)
```bash
https://cambridgecollage.com/public/

# Expected:
❌ 404 Not Found
```

### Test 3: Sensitive Files (يجب أن تكون محمية)
```bash
https://cambridgecollage.com/.env
https://cambridgecollage.com/composer.json

# Expected:
❌ 404 Not Found (محمية!)
```

---

## 🔧 **بعد التغيير:**

### ✅ ما يجب عمله:

#### 1. حذف root .htaccess (optional)
```bash
cd /home/k4c69o7wqcc3/public_html
rm .htaccess
# أو اتركه فارغ للـ HTTPS redirect فقط
```

#### 2. حذف root index.php (إذا موجود)
```bash
rm index.php
# لم تعد تحتاجه
```

#### 3. Clear Laravel cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### 4. Test كل الصفحات
```
✅ Homepage
✅ Courses
✅ Student login
✅ Admin panel
✅ Images/assets
```

---

## ⚠️ **إذا لم تجد "Domains" في cPanel:**

### Try Alternative Paths:

#### Option A: Addon Domains
```
cPanel → "Addon Domains"
→ Manage → Document Root
```

#### Option B: Subdomains
```
cPanel → "Subdomains"
→ Manage → Document Root
```

#### Option C: File Manager
```
لا يمكن تغيير DocumentRoot من File Manager
يجب استخدام Domains section
```

---

## 🆘 **إذا لم تستطع الوصول لهذه الإعدادات:**

### الأسباب المحتملة:

1. **Shared Hosting Basic Plan:**
   - بعض الاستضافات لا تسمح بتغيير DocumentRoot
   - الحل: اتصل بالدعم الفني

2. **Restricted Access:**
   - حساب cPanel محدود
   - الحل: اطلب من صاحب الحساب الرئيسي

3. **Managed Hosting:**
   - الاستضافة تدير الإعدادات
   - الحل: افتح تذكرة support

---

## 📧 **نموذج رسالة للدعم الفني:**

```
Subject: تغيير Document Root للنطاق

الرسالة:
مرحباً،

أريد تغيير Document Root للنطاق التالي:
Domain: cambridgecollage.com

من:
/home/k4c69o7wqcc3/public_html

إلى:
/home/k4c69o7wqcc3/public_html/public

السبب:
أستخدم Laravel framework والذي يتطلب أن يشير Document Root 
إلى مجلد public/ للأمان والأداء.

شكراً لكم.
```

---

## 🔄 **Alternative: إذا لم يسمحوا بتغيير DocumentRoot:**

### Solution A: نقل الملفات (Symlink Method)

```bash
# 1. Backup:
cp -r public_html public_html_backup

# 2. نقل Laravel خارج public_html:
mv public_html laravel_app

# 3. نقل محتويات public إلى public_html:
mv laravel_app/public public_html

# 4. تعديل paths في public_html/index.php:
# قبل:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

# بعد:
require __DIR__.'/../laravel_app/vendor/autoload.php';
$app = require_once __DIR__.'/../laravel_app/bootstrap/app.php';
```

**لكن هذا ليس مثالياً!**

---

### Solution B: استخدام Subdomain

```
1. أنشئ subdomain: app.cambridgecollage.com
2. DocumentRoot: public_html/public
3. Redirect cambridgecollage.com → app.cambridgecollage.com

في .htaccess (root):
RewriteEngine On
RewriteCond %{HTTP_HOST} ^cambridgecollage\.com$ [NC]
RewriteRule ^(.*)$ https://app.cambridgecollage.com/$1 [R=301,L]
```

---

### Solution C: تغيير الاستضافة

إذا الاستضافة لا تسمح بتغيير DocumentRoot:
```
→ ابحث عن استضافة أفضل
→ Laravel يحتاج هذه المرونة
→ Shared hosting جيد يجب أن يسمح بهذا
```

**استضافات موصى بها تدعم Laravel:**
- DigitalOcean
- Cloudways
- Hostinger (Premium plans)
- SiteGround
- Vultr

---

## ✅ **Verification Checklist:**

بعد تغيير DocumentRoot، تحقق:

```
[ ] الموقع يفتح بشكل طبيعي
[ ] لا يوجد /public/ في URLs
[ ] الـ assets تظهر (CSS, JS, images)
[ ] Student login يعمل
[ ] Admin panel يعمل
[ ] Images upload يعمل
[ ] لا errors في logs
[ ] .env محمي (404 عند محاولة الوصول)
[ ] composer.json محمي (404)
[ ] Performance أفضل
[ ] No redirect loops
```

---

## 🎉 **النتيجة المتوقعة:**

### مع DocumentRoot الصحيح:

```
✅ URLs نظيفة:
  https://cambridgecollage.com/courses
  https://cambridgecollage.com/student/login
  (بدون /public/)

✅ أمان عالي:
  .env → محمي
  config/ → محمي
  storage/ → محمي

✅ أداء ممتاز:
  No .htaccess rewrites في الجذر
  Direct access to Laravel

✅ Laravel standard:
  يعمل كما صُمم
  Best practices
```

---

## 📊 **Time Estimate:**

```
cPanel access available: 5 minutes
Need support ticket: 24-48 hours
Alternative solution: 30 minutes
```

---

**Priority:** 🔥 HIGH  
**Difficulty:** ⭐ Easy (if you have cPanel access)  
**Impact:** 🚀 HUGE (fixes everything permanently)

---

**ابدأ الآن! هذا الحل الوحيد الصحيح 100%!** ✨

