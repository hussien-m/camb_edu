# جرب هذه الإعدادات البديلة لـ Office365

# التكوين 1: Port 465 مع SSL
MAIL_MAILER="smtp"
MAIL_HOST="smtp.office365.com"
MAIL_PORT="465"
MAIL_USERNAME="info@cambridgecollage.com"
MAIL_PASSWORD="Hussien@app.com91"
MAIL_ENCRYPTION="ssl"
MAIL_FROM_ADDRESS="info@cambridgecollage.com"
MAIL_FROM_NAME="Cambridge International College in UK"
MAIL_TIMEOUT=60
MAIL_VERIFY_PEER=false
MAIL_VERIFY_PEER_NAME=false

# أو التكوين 2: Port 25 بدون تشفير
MAIL_MAILER="smtp"
MAIL_HOST="smtp.office365.com"
MAIL_PORT="25"
MAIL_USERNAME="info@cambridgecollage.com"
MAIL_PASSWORD="Hussien@app.com91"
MAIL_ENCRYPTION="tls"
MAIL_FROM_ADDRESS="info@cambridgecollage.com"
MAIL_FROM_NAME="Cambridge International College in UK"
MAIL_TIMEOUT=60
MAIL_VERIFY_PEER=false
MAIL_VERIFY_PEER_NAME=false
