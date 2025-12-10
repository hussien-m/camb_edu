# 🚀 Production Setup Guide for GoDaddy Hosting

## 📋 After Git Pull - Follow These Steps:

---

## 1️⃣ Update `.env` File on Server

After pulling from Git, edit `.env` file on the production server:

```env
# Environment
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cambridgecollage.com

# Database - Update with your production credentials
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Email Configuration for Production
MAIL_PROVIDER=smtp
QUEUE_CONNECTION=database

# GoDaddy cPanel Email SMTP
MAIL_MAILER=smtp
MAIL_HOST=mail.cambridgecollage.com
MAIL_PORT=587
MAIL_USERNAME=info@cambridgecollage.com
MAIL_PASSWORD=yP}rX?uJ6@1}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@cambridgecollage.com
MAIL_FROM_NAME="Cambridge International College"
MAIL_TIMEOUT=5
```

---

## 2️⃣ Run Migrations & Cache

```bash
cd /home/k4c69o7wqcc3/public_html
php artisan migrate --force
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 3️⃣ Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R your_user:your_user storage bootstrap/cache
```

---

## 4️⃣ Setup Cron Job (IMPORTANT!)

Go to **cPanel** → **Cron Jobs** → Add:

### Option 1 (Recommended):
```bash
* * * * * /usr/local/bin/php /home/k4c69o7wqcc3/public_html/artisan queue:work --stop-when-empty --tries=3 --max-time=50 >> /dev/null 2>&1
```

### Option 2 (Alternative):
```bash
* * * * * /usr/local/bin/php /home/k4c69o7wqcc3/public_html/artisan schedule:run >> /dev/null 2>&1
```

**Explanation:**
- `* * * * *` = Every minute
- `--stop-when-empty` = Stop when no jobs in queue
- `--tries=3` = Retry failed jobs 3 times
- `--max-time=50` = Run maximum 50 seconds (prevents overlap)
- `>> /dev/null 2>&1` = Hide output

---

## 5️⃣ Test Email System

1. Go to: `https://cambridgecollage.com/admin/email-settings`
2. Enter test email
3. Click "Send Test Email"
4. Should respond instantly ⚡
5. Email should arrive in 30-60 seconds 📧

---

## 6️⃣ Verify Queue is Working

Check jobs table is empty (means queue is processing):
```sql
SELECT COUNT(*) FROM jobs;
```

Should return `0` if cron job is working properly.

---

## 📊 Production vs Development Settings

| Setting | Development (localhost) | Production (GoDaddy) |
|---------|------------------------|----------------------|
| **APP_ENV** | local | production |
| **APP_DEBUG** | true | false |
| **QUEUE_CONNECTION** | sync | database |
| **MAIL_HOST** | smtp.office365.com | mail.cambridgecollage.com |
| **MAIL_PORT** | 587 | 587 |
| **Cron Job** | Not needed | Required! |

---

## ✅ Verification Checklist

- [ ] `.env` updated with production settings
- [ ] Database credentials correct
- [ ] `php artisan migrate` executed
- [ ] `php artisan config:cache` executed
- [ ] Cron job added in cPanel
- [ ] Test email sent successfully
- [ ] Email received in inbox (not spam)
- [ ] Queue jobs table is empty
- [ ] Website loads without errors

---

## 🆘 Troubleshooting

### Email not sending?
1. Check `storage/logs/laravel.log`
2. Verify email password in `.env`
3. Check cron job is running: `grep CRON /var/log/syslog`
4. Verify `jobs` table: `SELECT * FROM jobs`

### Cron job not working?
1. Check cPanel → Cron Jobs → Status
2. Verify PHP path: `/usr/local/bin/php -v`
3. Test manually: `php artisan queue:work --once`

### Emails going to spam?
1. Add "Not Spam" in Gmail
2. Consider SendGrid Domain Authentication
3. Check email content for spam keywords

---

## 📞 Support

- GoDaddy Support: For server/cPanel issues
- Laravel Docs: https://laravel.com/docs
- SendGrid Setup: See `SENDGRID_SETUP_GUIDE.md`

---

**Last Updated:** December 9, 2025
