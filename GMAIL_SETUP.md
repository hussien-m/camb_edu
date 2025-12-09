# استخدام Gmail بدلاً من Office365

1. افتح حساب Gmail
2. اذهب إلى: https://myaccount.google.com/apppasswords
3. أنشئ App Password جديد
4. استخدم هذه الإعدادات:

MAIL_MAILER="smtp"
MAIL_HOST="smtp.gmail.com"
MAIL_PORT="587"
MAIL_USERNAME="your-email@gmail.com"
MAIL_PASSWORD="your-app-password-here"
MAIL_ENCRYPTION="tls"
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="Cambridge International College in UK"
