# 🔐 Google reCAPTCHA v3 - دليل التفعيل الكامل

## 📋 الخطوات

### الخطوة 1: احصل على API Keys من Google

#### 1. اذهب إلى:
```
https://www.google.com/recaptcha/admin/create
```

#### 2. سجل دخول بحساب Google

#### 3. املأ النموذج:
```
Label: Cambridge College Website
reCAPTCHA type: ✅ reCAPTCHA v3
Domains: 
  - cambridgecollage.com
  - www.cambridgecollage.com
  - localhost (للتطوير)

✅ Accept the reCAPTCHA Terms of Service
```

#### 4. اضغط Submit

#### 5. ستحصل على:
- **Site Key** (للاستخدام في Frontend)
- **Secret Key** (للاستخدام في Backend)

---

### الخطوة 2: أضف المفاتيح في `.env`

```bash
# في .env على السيرفر:
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
RECAPTCHA_ENABLED_LOCALLY=false
```

**مثال:**
```bash
RECAPTCHA_SITE_KEY=6LcXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
RECAPTCHA_SECRET_KEY=6LcYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY
```

---

### الخطوة 3: أضف السكريبت في `resources/views/frontend/layouts/app.blade.php`

**في الـ `<head>` section:**

```blade
<!-- Google reCAPTCHA v3 -->
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
@endif
```

**قبل `</body>` tag:**

```blade
<!-- reCAPTCHA v3 Functions -->
@if(config('services.recaptcha.site_key'))
<script>
// Global reCAPTCHA function
function executeRecaptcha(action) {
    return new Promise((resolve, reject) => {
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: action})
                .then(function(token) {
                    resolve(token);
                })
                .catch(function(error) {
                    reject(error);
                });
        });
    });
}
</script>
@endif
```

---

### الخطوة 4: تطبيق الحماية على الروتات

الآن سنضيف الـ middleware للروتات المطلوبة!

سأكمل في الملف التالي...

