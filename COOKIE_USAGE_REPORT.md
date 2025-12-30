# 🍪 تقرير استخدام Cookies في الموقع

## ✅ الكوكيز المستخدمة:

### 1. **Session Cookie** (الأساسي)
```
الاسم: laravel_session أو cambridge-international-college-in-uk-session
الهدف: تتبع جلسة المستخدم (Login status, shopping cart, etc.)
المدة: 120 دقيقة (2 ساعة)
النوع: Essential (ضروري)
```

**يُستخدم لـ:**
- ✅ تسجيل دخول Admin
- ✅ تسجيل دخول Student
- ✅ حفظ بيانات النموذج مؤقتاً
- ✅ Flash messages (رسائل النجاح/الخطأ)

### 2. **CSRF Token Cookie**
```
الاسم: XSRF-TOKEN
الهدف: الحماية من CSRF attacks
المدة: حتى نهاية الجلسة
النوع: Essential (ضروري)
```

**يُستخدم لـ:**
- ✅ حماية النماذج من الهجمات
- ✅ التحقق من صحة الطلبات

### 3. **Remember Me Cookie** (اختياري)
```
الاسم: remember_web_{guard}
الهدف: تذكر تسجيل الدخول
المدة: 5 سنوات (إذا اختار المستخدم "تذكرني")
النوع: Functional (وظيفي)
```

**يُستخدم لـ:**
- ✅ البقاء مسجل دخول بعد إغلاق المتصفح
- ✅ تجربة مستخدم أفضل

---

## 🔒 إعدادات الأمان:

### ✅ **HTTP Only:**
```php
'http_only' => true
```
**الفائدة:** JavaScript لا يمكنه قراءة الكوكيز (حماية من XSS)

### ✅ **Same Site:**
```php
'same_site' => 'lax'
```
**الفائدة:** حماية من CSRF attacks

### ⚠️ **Secure (HTTPS):**
```php
'secure' => env('SESSION_SECURE_COOKIE')
```
**الحالة:** يعتمد على إعداد .env
**التوصية:** يجب أن يكون `true` في الإنتاج (مع HTTPS)

### ✅ **Session Encryption:**
```php
'encrypt' => false
```
**الحالة:** البيانات مخزنة في database (آمن)

---

## 📊 تفاصيل التخزين:

### Session Driver:
```
Driver: database
Storage: sessions table في قاعدة البيانات
```

**البيانات المخزنة:**
- User ID (إذا مسجل دخول)
- CSRF Token
- Flash data (رسائل مؤقتة)
- Form old input
- Cart data (إذا موجود)

---

## 🌍 نطاق الكوكيز:

```
Domain: cambridgecollage.com
Path: /
Lifetime: 120 minutes (2 hours)
```

**يعني:**
- الكوكيز تعمل على كل صفحات الموقع
- تنتهي بعد ساعتين من آخر نشاط
- تُحذف عند Logout

---

## 📜 متطلبات GDPR/القانونية:

### ⚠️ **يجب إضافة Cookie Consent Banner!**

موقعك يستخدم cookies، لذلك قانونياً يجب:

#### 1. **Cookie Notice/Banner:**
```
يجب إخبار الزوار أن الموقع يستخدم cookies
قبل تخزين أي cookie (عدا الضرورية)
```

#### 2. **Cookie Policy Page:**
```
صفحة توضح:
- ما هي الكوكيز المستخدمة
- لماذا نستخدمها
- كيف يمكن تعطيلها
```

#### 3. **User Consent:**
```
للكوكيز غير الضرورية (Analytics, Marketing)
يجب أخذ موافقة المستخدم أولاً
```

---

## 🎯 التصنيف القانوني:

### Essential Cookies (ضرورية - لا تحتاج موافقة):
```
✅ Session Cookie - تسجيل الدخول
✅ CSRF Token - الأمان
✅ Load Balancer - تقنية
```

### Functional Cookies (وظيفية - تحتاج موافقة):
```
⚠️ Remember Me - تذكر تسجيل الدخول
```

### Analytics Cookies (تحليلية - تحتاج موافقة):
```
❌ لا توجد حالياً (Google Analytics إذا أضفته)
```

### Marketing Cookies (تسويقية - تحتاج موافقة):
```
❌ لا توجد حالياً
```

---

## ✅ الحل: إضافة Cookie Consent

### الخيار 1: Cookie Consent Banner بسيط

```html
<!-- في resources/views/frontend/layouts/app.blade.php -->
<div id="cookie-consent" style="display:none;">
    <div class="cookie-banner">
        <p>
            We use cookies to ensure you get the best experience on our website.
            By continuing to use this site, you accept our use of cookies.
            <a href="/privacy-policy">Learn more</a>
        </p>
        <button onclick="acceptCookies()">Accept</button>
    </div>
</div>

<script>
function acceptCookies() {
    localStorage.setItem('cookie-consent', 'accepted');
    document.getElementById('cookie-consent').style.display = 'none';
}

// Show banner if not accepted
if (!localStorage.getItem('cookie-consent')) {
    document.getElementById('cookie-consent').style.display = 'block';
}
</script>

<style>
.cookie-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #2c3e50;
    color: white;
    padding: 20px;
    text-align: center;
    z-index: 9999;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
}

.cookie-banner button {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 30px;
    margin-left: 20px;
    cursor: pointer;
    border-radius: 5px;
}

.cookie-banner button:hover {
    background: #2980b9;
}
</style>
```

### الخيار 2: Package احترافي

```bash
# استخدم Laravel Cookie Consent package
composer require spatie/laravel-cookie-consent

# ثم اتبع التوثيق:
# https://github.com/spatie/laravel-cookie-consent
```

---

## 📄 صفحة Cookie Policy

يجب إنشاء صفحة `/cookie-policy` تحتوي على:

### محتوى الصفحة:

```markdown
# Cookie Policy

## What are cookies?
Cookies are small text files stored on your device...

## Cookies we use:

### Essential Cookies:
- **Session Cookie**: Required for login and security
- **CSRF Token**: Required for form security

### Functional Cookies:
- **Remember Me**: Keeps you logged in (optional)

## How to control cookies:
You can control cookies through your browser settings...

## Contact us:
If you have questions about our cookie policy...
```

---

## 🔍 فحص الكوكيز الحالية:

### في Chrome DevTools:
```
1. افتح الموقع
2. اضغط F12
3. Application → Cookies → cambridgecollage.com
4. شوف الكوكيز الموجودة
```

**ستجد:**
- `laravel_session` أو `cambridge-international-college-in-uk-session`
- `XSRF-TOKEN`
- `remember_web_guard` (إذا اخترت تذكرني)

---

## ⚖️ الامتثال القانوني:

### GDPR (أوروبا):
```
✅ يجب Cookie Banner
✅ يجب Cookie Policy page
✅ يجب موافقة المستخدم (للكوكيز غير الضرورية)
✅ يجب إمكانية الرفض
```

### CCPA (كاليفورنيا):
```
✅ يجب الإفصاح عن استخدام الكوكيز
✅ يجب حق الاعتراض (opt-out)
```

### القوانين العربية:
```
⚠️ معظم الدول العربية لا تطلب Cookie Consent
لكن من الأفضل إضافته (best practice عالمي)
```

---

## ✅ التوصيات:

### الآن (ضروري):
```
1. أضف Cookie Consent Banner
2. أنشئ صفحة Cookie Policy
3. أضف رابط للـ Policy في الـ footer
```

### قريباً (مهم):
```
1. فعّل SESSION_SECURE_COOKIE=true (مع HTTPS)
2. راجع Privacy Policy
3. أضف "Cookie Settings" للمستخدم
```

### مستقبلاً (تحسين):
```
1. استخدم Cookie Consent Management Platform
2. تتبع Analytics بموافقة المستخدم
3. مراجعة دورية للامتثال القانوني
```

---

## 📊 الخلاصة:

### ✅ **نعم، موقعك يستخدم Cookies**

**الكوكيز المستخدمة:**
- 🍪 Session Cookie (ضروري)
- 🍪 CSRF Token (ضروري)
- 🍪 Remember Me (اختياري)

**الأمان:**
- ✅ HTTP Only
- ✅ Same Site protection
- ⚠️ Secure (يحتاج HTTPS)

**القانونية:**
- ⚠️ يحتاج Cookie Consent Banner
- ⚠️ يحتاج Cookie Policy page

**التقييم:**
- 🔒 الأمان: 8/10
- ⚖️ الامتثال القانوني: 4/10 (بدون Cookie Banner)
- 📊 الشفافية: 5/10 (بدون Cookie Policy)

---

## 🚀 الخطوة التالية:

**أضف Cookie Consent Banner خلال يوم!**

هذا:
- ✅ مطلوب قانونياً (GDPR)
- ✅ يزيد الثقة
- ✅ Best practice عالمي
- ✅ سهل التطبيق (15 دقيقة)

---

**هل تريد مني إنشاء Cookie Consent Banner كامل لك؟** 🍪

