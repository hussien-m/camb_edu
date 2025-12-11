# Redirect all requests to the internal public folder
RewriteEngine On

# Set the base to root
RewriteBase /

# Redirect everything to the public folder
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ public/$1 [L]

# Deny access to .env files
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# Deny access to composer and git files
<FilesMatch "(composer|artisan|package(-lock)?\.json|vendor|git)">
    Order allow,deny
    Deny from all
</FilesMatch>

# ========================================
# Image Optimization & Compression
# ========================================

# Enable GZIP Compression
<IfModule mod_deflate.c>
    # Compress HTML, CSS, JavaScript, Text, XML and fonts
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/json
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE application/x-font-ttf
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE font/opentype
    AddOutputFilterByType DEFLATE font/otf
    AddOutputFilterByType DEFLATE font/ttf
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE image/x-icon
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/xml

    # Remove browser bugs (only needed for really old browsers)
    BrowserMatch ^Mozilla/4 gzip-only-text/html
    BrowserMatch ^Mozilla/4\.0[678] no-gzip
    BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
    Header append Vary User-Agent
</IfModule>

# Browser Caching (Leverage Browser Cache)
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresDefault "access plus 1 month"

    # Images (1 year)
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresByType image/vnd.microsoft.icon "access plus 1 year"

    # CSS and JavaScript (1 month)
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"

    # Fonts (1 year)
    ExpiresByType font/woff "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType font/ttf "access plus 1 year"
    ExpiresByType font/otf "access plus 1 year"
    ExpiresByType application/font-woff "access plus 1 year"
    ExpiresByType application/font-woff2 "access plus 1 year"
    ExpiresByType application/x-font-ttf "access plus 1 year"
    ExpiresByType application/x-font-woff "access plus 1 year"

    # Documents
    ExpiresByType application/pdf "access plus 1 month"

    # HTML (10 minutes)
    ExpiresByType text/html "access plus 600 seconds"
</IfModule>

# Cache-Control Headers
<IfModule mod_headers.c>
    # Images - Cache for 1 year
    <FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|ico)$">
        Header set Cache-Control "public, max-age=31536000, immutable"
        Header unset ETag
        Header unset Last-Modified
    </FilesMatch>

    # CSS and JS - Cache for 1 month
    <FilesMatch "\.(css|js)$">
        Header set Cache-Control "public, max-age=2592000"
    </FilesMatch>

    # Fonts - Cache for 1 year
    <FilesMatch "\.(woff|woff2|ttf|otf|eot)$">
        Header set Cache-Control "public, max-age=31536000, immutable"
    </FilesMatch>

    # Disable ETags (use Cache-Control instead)
    Header unset ETag
    FileETag None
</IfModule>

# ========================================
# Security Headers
# ========================================

<IfModule mod_headers.c>
    # Prevent clickjacking
    Header set X-Frame-Options "SAMEORIGIN"

    # XSS Protection
    Header set X-XSS-Protection "1; mode=block"

    # Prevent MIME sniffing
    Header set X-Content-Type-Options "nosniff"

    # Referrer Policy
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# Disable directory browsing
Options -Indexes
