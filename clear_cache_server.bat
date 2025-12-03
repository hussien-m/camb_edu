@echo off
REM ==================================
REM Clear All Cache on Server (Windows)
REM ==================================

echo Clearing all Laravel cache...

REM Clear all cache
php artisan optimize:clear

REM Clear individual caches
php artisan view:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear

REM Rebuild cache for production
echo Rebuilding cache for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo Cache cleared successfully!
echo Please refresh the browser (Ctrl+F5 or Incognito Mode)
pause

