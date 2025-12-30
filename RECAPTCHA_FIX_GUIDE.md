# 🔧 Fix reCAPTCHA Error on Production Server

## ❌ Problem:
```json
{
  "error": "reCAPTCHA verification failed. Please refresh and try again."
}
```

**Root Cause:**
- reCAPTCHA keys are registered for `localhost` only
- Production domain `cambridgecollage.com` is NOT authorized

---

## ✅ Solution 1: Update reCAPTCHA Keys (RECOMMENDED)

### Step 1: Go to Google reCAPTCHA Admin
```
https://www.google.com/recaptcha/admin
```

### Step 2: Edit Your reCAPTCHA v3 Site

**Check your existing site:**
- Click on your site name
- Check "Domains" section

**If it only has `localhost`:**
```
❌ Domains:
   - localhost
```

**Add your production domain:**
```
✅ Domains:
   - localhost
   - cambridgecollage.com
   - www.cambridgecollage.com
```

### Step 3: Save and Wait
- Click "Save"
- Wait 1-2 minutes for changes to propagate
- Test login again

---

## ✅ Solution 2: Create New reCAPTCHA Keys for Production

### Step 1: Create New Site
```
1. Go to: https://www.google.com/recaptcha/admin/create
2. Fill form:
   - Label: Cambridge College - Production
   - reCAPTCHA type: reCAPTCHA v3
   - Domains:
     * cambridgecollage.com
     * www.cambridgecollage.com
     * localhost (for testing)
3. Click "Submit"
```

### Step 2: Copy New Keys
```
Site Key: 6Lxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
Secret Key: 6Lxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### Step 3: Update Server `.env`
```bash
# SSH to server
ssh your_server

# Edit .env
cd /home/k4c69o7wqcc3/public_html
nano .env

# Update these lines:
RECAPTCHA_SITE_KEY=YOUR_NEW_SITE_KEY_HERE
RECAPTCHA_SECRET_KEY=YOUR_NEW_SECRET_KEY_HERE
RECAPTCHA_ENABLED_LOCALLY=false

# Save: Ctrl+O, Enter, Ctrl+X
```

### Step 4: Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### Step 5: Test
```
1. Open browser (incognito mode)
2. Go to: https://cambridgecollage.com/student/login
3. Try to login
4. Should work! ✅
```

---

## ✅ Solution 3: Temporary Bypass (FOR TESTING ONLY)

**⚠️ WARNING: This disables security! Use only for testing!**

### Option A: Disable reCAPTCHA on Production

Edit `.env` on server:
```env
# Temporarily disable reCAPTCHA
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

Clear cache:
```bash
php artisan config:clear
```

**Result:** Login will work without reCAPTCHA.

**⚠️ Remember to re-enable later!**

---

## 🔍 Debug reCAPTCHA Errors

### Check Server Logs
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# Look for:
# "reCAPTCHA verification failed"
# Check "error_codes" in the log
```

### Common Error Codes

| Error Code | Meaning | Solution |
|------------|---------|----------|
| `missing-input-secret` | Secret key missing | Check `.env` file |
| `invalid-input-secret` | Secret key wrong | Get correct key from Google |
| `missing-input-response` | Token not sent | Check frontend JavaScript |
| `invalid-input-response` | Token expired/invalid | Refresh page and try again |
| `timeout-or-duplicate` | Token used twice | Refresh page |
| `bad-request` | Malformed request | Check reCAPTCHA code |
| `invalid-keys` | Keys don't match | Check Site Key & Secret Key |
| **`hostname-not-allowed`** | **Domain not authorized** | **Add domain in Google Console** |

**Most likely error: `hostname-not-allowed`**

---

## 🎯 Quick Test Commands

### Test 1: Check if keys are set
```bash
php artisan tinker
>>> config('services.recaptcha.site_key')
>>> config('services.recaptcha.secret_key')
>>> exit
```

**Expected:**
```
"6Lxxxxx..."  (should be 40+ characters)
"6Lxxxxx..."  (should be 40+ characters)
```

**If empty or null:**
```bash
# Keys not loaded, check .env
cat .env | grep RECAPTCHA
```

### Test 2: Manual API Test
```bash
# Replace with your actual values:
SECRET_KEY="your_secret_key"
TOKEN="test_token_from_browser_console"
IP="your_ip"

curl -X POST "https://www.google.com/recaptcha/api/siteverify" \
  -d "secret=$SECRET_KEY" \
  -d "response=$TOKEN" \
  -d "remoteip=$IP"
```

**Expected Response:**
```json
{
  "success": true,
  "challenge_ts": "2025-12-30T...",
  "hostname": "cambridgecollage.com",
  "score": 0.9,
  "action": "student_login"
}
```

**If `success: false`:**
```json
{
  "success": false,
  "error-codes": ["hostname-not-allowed"]  ← This is the problem!
}
```

**Solution:** Add `cambridgecollage.com` to authorized domains in Google Console.

---

## 📋 Verification Checklist

After fixing, verify all forms work:

### Student Forms:
- [ ] Student Login
- [ ] Student Registration

### Admin Forms:
- [ ] Admin Login

### Frontend Forms:
- [ ] Newsletter Subscription
- [ ] Contact Form
- [ ] Course Inquiry Form

**Each form should:**
- ✅ Submit successfully
- ✅ No reCAPTCHA errors
- ✅ No console errors in browser

---

## 🔐 Production Best Practices

### Recommended Settings:

**`.env` (Production):**
```env
RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here
RECAPTCHA_SCORE_THRESHOLD=0.5
RECAPTCHA_ENABLED_LOCALLY=false  ← Important!
```

**Google reCAPTCHA Settings:**
```
✅ Type: reCAPTCHA v3
✅ Domains:
   - cambridgecollage.com
   - www.cambridgecollage.com
   - localhost (for local development)
✅ Security: High
```

### Score Thresholds:
```
Score >= 0.9  : Very likely human  ✅
Score >= 0.7  : Probably human     ✅
Score >= 0.5  : Neutral            ⚠️
Score >= 0.3  : Suspicious         ⚠️
Score <  0.3  : Likely bot         ❌

Recommended: 0.5 (balanced)
```

---

## 🚨 Emergency: Completely Disable reCAPTCHA

**If you need to disable completely (not recommended):**

### Option 1: Via .env (Temporary)
```env
RECAPTCHA_SITE_KEY=
RECAPTCHA_SECRET_KEY=
```

### Option 2: Remove from Routes (Permanent)
Edit route files and remove `->middleware(['recaptcha:0.7'])`

**Example:**
```php
// Before:
Route::post('login', [LoginController::class, 'login'])
    ->middleware(['throttle:5,5', 'recaptcha:0.7']); // ← Remove recaptcha

// After:
Route::post('login', [LoginController::class, 'login'])
    ->middleware(['throttle:5,5']);
```

**⚠️ Warning:** This leaves your site vulnerable to bots!

---

## 📞 Need Help?

### Check Browser Console
```
1. Open browser DevTools (F12)
2. Go to "Console" tab
3. Try to login
4. Look for errors like:
   - "reCAPTCHA error: ..."
   - "Failed to load reCAPTCHA"
   - Network errors
```

### Check Network Tab
```
1. Open DevTools → Network
2. Filter: "siteverify"
3. Try to login
4. Check the response from Google:
   - Status: 200 (good) or 400 (bad)
   - Response body: shows error codes
```

### Still Not Working?

**Most likely cause:** Domain not authorized in Google reCAPTCHA Console.

**Solution:** Go to https://www.google.com/recaptcha/admin and add `cambridgecollage.com` to your site's authorized domains.

---

## ✅ Final Verification

After fixing, test everything:

```bash
# 1. Test Student Login
curl -X POST https://cambridgecollage.com/student/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","password":"test123","recaptcha_token":"test"}'

# 2. Test Admin Login
curl -X POST https://cambridgecollage.com/admin/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@test.com","password":"test123","recaptcha_token":"test"}'

# Should return 422 with specific error, NOT 500
```

**Expected:** Proper error messages (wrong password, etc.) - NOT reCAPTCHA errors.

---

**Date:** 30 ديسمبر 2025  
**Issue:** reCAPTCHA hostname-not-allowed  
**Solution:** Add cambridgecollage.com to Google reCAPTCHA Console  
**Status:** 🔧 Action Required

