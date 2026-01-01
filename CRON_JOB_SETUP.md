# إعداد Cron Job - Cron Job Setup

## 📍 المسار على الاستضافة
```
/home/k4c69o7wqcc3/public_html/cambridgecollage.com
```

---

## ⚙️ إعداد Cron Job في cPanel

### الخطوات:

1. **اذهب إلى cPanel → Cron Jobs**

2. **اختر Standard (cPanel v54)**

3. **أضف السطر التالي:**

```bash
* * * * * cd /home/k4c69o7wqcc3/public_html/cambridgecollage.com && php artisan schedule:run >> /dev/null 2>&1
```

### شرح الأمر:
- `* * * * *` - يعمل كل دقيقة
- `cd /home/k4c69o7wqcc3/public_html/cambridgecollage.com` - الانتقال إلى مجلد المشروع
- `php artisan schedule:run` - تشغيل Laravel Scheduler
- `>> /dev/null 2>&1` - تجاهل المخرجات والأخطاء

---

## 📋 المهام المجدولة (Scheduled Tasks)

### 1. إرسال تذكيرات التحقق من البريد
- **التكرار:** كل 3 أيام في الساعة 9:00 AM UTC
- **الأمر:** `students:send-verification-reminders`
- **الوظيفة:** إرسال تذكيرات للطلاب غير المفعلين

### 2. إغلاق المحاولات المنتهية
- **التكرار:** كل 5 دقائق
- **الوظيفة:** `CloseExpiredExamAttempts` Job
- **الوظيفة:** إغلاق المحاولات الجارية المنتهية

---

## ✅ التحقق من عمل Cron Job

### طريقة 1: عبر Logs
```bash
cd /home/k4c69o7wqcc3/public_html/cambridgecollage.com
tail -f storage/logs/laravel.log
```

### طريقة 2: اختبار يدوي
```bash
cd /home/k4c69o7wqcc3/public_html/cambridgecollage.com
php artisan schedule:run
php artisan schedule:list
```

### طريقة 3: التحقق من Cron Logs في cPanel
- اذهب إلى **cPanel → Cron Jobs → Cron History**
- تحقق من آخر تنفيذ

---

## 🔧 استكشاف الأخطاء

### المشكلة: Cron Job لا يعمل
**الحل:**
1. تحقق من المسار الصحيح
2. تحقق من صلاحيات الملفات
3. تحقق من أن PHP في المسار الصحيح:
   ```bash
   which php
   ```

### المشكلة: الأخطاء في Logs
**الحل:**
```bash
cd /home/k4c69o7wqcc3/public_html/cambridgecollage.com
php artisan config:clear
php artisan cache:clear
```

---

## 📝 ملاحظات مهمة

1. **المسار:** تأكد من أن المسار `/home/k4c69o7wqcc3/public_html/cambridgecollage.com` صحيح
2. **PHP:** تأكد من استخدام PHP الصحيح (عادة `/usr/bin/php` أو `/opt/cpanel/ea-php81/root/usr/bin/php`)
3. **الصلاحيات:** تأكد من صلاحيات القراءة والكتابة للمجلدات

---

## 🚀 الأمر الكامل للنسخ واللصق

```bash
* * * * * cd /home/k4c69o7wqcc3/public_html/cambridgecollage.com && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

**أو إذا كان PHP في مسار مختلف:**
```bash
* * * * * cd /home/k4c69o7wqcc3/public_html/cambridgecollage.com && /opt/cpanel/ea-php81/root/usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

---

**تاريخ التحديث:** {{ date('Y-m-d') }}

