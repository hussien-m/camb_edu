# دليل إصلاح مشكلة وصول البريد إلى Spam

## المشكلة:
البريد الإلكتروني يصل في مجلد Spam (البريد المزعج) بدلاً من Inbox.

---

## الحلول المطبقة في الكود:

### 1. تحسين Email Headers:
- ✅ إضافة `Reply-To` header
- ✅ إضافة `Return-Path` header
- ✅ إضافة `Message-ID` header
- ✅ إضافة `List-Unsubscribe` headers
- ✅ إضافة `X-Priority` و `Importance` headers

---

## الحلول المطلوبة في DNS (مهم جداً):

### 1. SPF Record (Sender Policy Framework):

أضف هذا السجل في DNS للدومين `cambridge-college.uk`:

```
Type: TXT
Name: @ (أو cambridge-college.uk)
Value: v=spf1 mx a ip4:YOUR_SERVER_IP include:mail.cambridge-college.uk ~all
```

**مثال:**
```
v=spf1 mx a ip4:123.45.67.89 include:mail.cambridge-college.uk ~all
```

---

### 2. DKIM Record (DomainKeys Identified Mail):

إذا كان لديك DKIM key من مزود البريد، أضفه:

```
Type: TXT
Name: default._domainkey (أو selector._domainkey)
Value: v=DKIM1; k=rsa; p=YOUR_PUBLIC_KEY
```

**ملاحظة:** إذا لم يكن لديك DKIM key، يمكنك إنشاؤه أو طلبه من مزود الاستضافة.

---

### 3. DMARC Record (Domain-based Message Authentication):

أضف هذا السجل:

```
Type: TXT
Name: _dmarc
Value: v=DMARC1; p=quarantine; rua=mailto:info@cambridge-college.uk; ruf=mailto:info@cambridge-college.uk; fo=1
```

**أو للأمان الأقل (للاختبار):**
```
v=DMARC1; p=none; rua=mailto:info@cambridge-college.uk
```

---

## خطوات إضافة DNS Records:

### في cPanel:
1. اذهب إلى **Zone Editor** أو **DNS Management**
2. أضف السجلات المذكورة أعلاه
3. انتظر 24-48 ساعة للانتشار

### في Cloudflare أو أي DNS Provider:
1. اذهب إلى DNS Settings
2. أضف السجلات المذكورة
3. انتظر الانتشار

---

## التحقق من السجلات:

### استخدام أدوات التحقق:
1. **MXToolbox SPF Checker:**
   - https://mxtoolbox.com/spf.aspx
   - أدخل: `cambridge-college.uk`

2. **DKIM Validator:**
   - https://mxtoolbox.com/dkim.aspx

3. **DMARC Analyzer:**
   - https://mxtoolbox.com/dmarc.aspx

---

## تحسينات إضافية:

### 1. تحسين Subject Line:
- تجنب استخدام كلمات مثل: "Free", "Click here", "Urgent"
- استخدم أسماء واضحة ومهنية

### 2. محتوى البريد:
- ✅ تجنب استخدام الكثير من الروابط
- ✅ استخدم نص واضح ومهني
- ✅ أضف معلومات الاتصال في Footer

### 3. From Address:
- ✅ تأكد من أن From Address مطابق للدومين
- ✅ استخدم: `info@cambridge-college.uk` (وليس Gmail أو Yahoo)

---

## اختبار البريد:

### 1. Mail-Tester:
- اذهب إلى: https://www.mail-tester.com/
- أرسل بريد إلى العنوان المعطى
- تحقق من النتيجة (يجب أن تكون 8/10 أو أعلى)

### 2. Gmail Postmaster Tools:
- سجّل الدومين في: https://postmaster.google.com/
- راقب إحصائيات التسليم

---

## إذا استمرت المشكلة:

### 1. استخدم SendGrid أو Mailgun:
- هذه الخدمات توفر SPF/DKIM/DMARC تلقائياً
- تحسين معدل التسليم بشكل كبير

### 2. تحقق من Blacklists:
- افحص إذا كان الدومين في قائمة سوداء:
  - https://mxtoolbox.com/blacklists.aspx

### 3. تحقق من Logs:
```bash
tail -f storage/logs/laravel.log | grep -i mail
```

---

## Checklist:

- [ ] إضافة SPF Record في DNS
- [ ] إضافة DKIM Record (إن أمكن)
- [ ] إضافة DMARC Record
- [ ] التحقق من السجلات باستخدام MXToolbox
- [ ] اختبار البريد باستخدام Mail-Tester
- [ ] التأكد من أن From Address مطابق للدومين
- [ ] مراقبة Logs للأخطاء

---

**ملاحظة:** بعد إضافة DNS Records، قد يستغرق الأمر 24-48 ساعة حتى تبدأ محركات البريد في الثقة بالدومين.
