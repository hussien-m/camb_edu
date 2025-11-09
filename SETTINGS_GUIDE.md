# Settings System - Usage Guide

## Overview
نظام إعدادات شامل للموقع يسمح بتخزين واسترجاع الإعدادات بسهولة مع دعم الـ Cache.

## Features
- ✅ Key-Value storage
- ✅ Multiple types: text, textarea, image, boolean, number
- ✅ Grouped settings: general, contact, social, seo
- ✅ Automatic caching
- ✅ Easy to use helper functions
- ✅ Admin panel interface

## Usage

### في الـ Blade Templates:

```blade
<!-- استخدام Helper Function -->
{{ setting('site_name') }}
{{ setting('contact_email', 'default@email.com') }}

<!-- استخدام الـ settings المشتركة -->
{{ $settings['site_name'] ?? 'Default Name' }}

<!-- عرض صورة -->
@if(setting('site_logo'))
    <img src="{{ asset('storage/' . setting('site_logo')) }}" alt="Logo">
@endif
```

### في الـ Controllers:

```php
use App\Models\Setting;

// Get single setting
$siteName = Setting::get('site_name', 'Default Name');
// Or using helper
$siteName = setting('site_name', 'Default Name');

// Set setting
Setting::set('site_name', 'Cambridge College', 'text', 'general');

// Get settings by group
$contactSettings = Setting::getByGroup('contact');

// Get all settings
$allSettings = Setting::getAllSettings();

// Clear cache
Setting::clearCache();
```

## Available Settings Groups

### General
- site_name
- site_title
- site_description
- site_logo
- site_favicon
- footer_text

### Contact
- contact_email
- contact_phone
- contact_phone_2
- contact_address
- contact_whatsapp
- business_hours
- google_maps

### Social
- social_facebook
- social_instagram
- social_twitter
- social_linkedin
- social_youtube

### SEO
- seo_keywords
- google_analytics

## Admin Panel
الوصول للإعدادات من لوحة التحكم:
`http://yoursite.com/admin/settings`

## Adding New Settings

### من الكود:
```php
Setting::create([
    'key' => 'new_setting',
    'value' => 'value',
    'type' => 'text',
    'group' => 'general',
    'label' => 'Setting Label',
    'description' => 'Setting description',
]);
```

### من الـ Seeder:
أضف في `database/seeders/SettingSeeder.php`
