<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'site_name',
                'value' => 'Cambridge College',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Name',
                'description' => 'The name of your website',
            ],
            [
                'key' => 'site_title',
                'value' => 'Cambridge College - Best Education in Libya',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Site Title',
                'description' => 'Default title for SEO',
            ],
            [
                'key' => 'site_description',
                'value' => 'Cambridge College offers top-quality education and courses in Libya',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Site Description',
                'description' => 'Default meta description',
            ],
            [
                'key' => 'site_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Site Logo',
                'description' => 'Upload your website logo',
            ],
            [
                'key' => 'site_favicon',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'label' => 'Site Favicon',
                'description' => 'Upload your website favicon',
            ],

            // Contact Information
            [
                'key' => 'contact_email',
                'value' => 'info@cambridgecollege.ly',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Email Address',
                'description' => 'Main contact email',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+218 91 234 5678',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Phone Number',
                'description' => 'Main contact phone',
            ],
            [
                'key' => 'contact_phone_2',
                'value' => '+218 92 234 5678',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Phone Number 2',
                'description' => 'Secondary contact phone',
            ],
            [
                'key' => 'contact_address',
                'value' => 'Tripoli, Libya',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Address',
                'description' => 'Physical address',
            ],
            [
                'key' => 'contact_whatsapp',
                'value' => '+218912345678',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'WhatsApp Number',
                'description' => 'WhatsApp contact number',
            ],

            // Social Media
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/cambridgecollege',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Facebook URL',
                'description' => 'Facebook page link',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/cambridgecollege',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Instagram URL',
                'description' => 'Instagram profile link',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/cambridgecollege',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Twitter URL',
                'description' => 'Twitter profile link',
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/cambridgecollege',
                'type' => 'text',
                'group' => 'social',
                'label' => 'LinkedIn URL',
                'description' => 'LinkedIn page link',
            ],
            [
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/cambridgecollege',
                'type' => 'text',
                'group' => 'social',
                'label' => 'YouTube URL',
                'description' => 'YouTube channel link',
            ],

            // SEO Settings
            [
                'key' => 'seo_keywords',
                'value' => 'education, courses, libya, cambridge, college',
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Meta Keywords',
                'description' => 'Comma separated keywords',
            ],
            [
                'key' => 'google_analytics',
                'value' => null,
                'type' => 'textarea',
                'group' => 'seo',
                'label' => 'Google Analytics Code',
                'description' => 'Google Analytics tracking code',
            ],
            [
                'key' => 'google_maps',
                'value' => null,
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Google Maps Embed',
                'description' => 'Google Maps iframe code',
            ],

            // Business Hours
            [
                'key' => 'business_hours',
                'value' => 'Saturday - Thursday: 9:00 AM - 5:00 PM',
                'type' => 'textarea',
                'group' => 'contact',
                'label' => 'Business Hours',
                'description' => 'Working hours',
            ],

            // Footer Settings
            [
                'key' => 'footer_text',
                'value' => '© 2025 Cambridge College. All rights reserved.',
                'type' => 'textarea',
                'group' => 'general',
                'label' => 'Footer Copyright Text',
                'description' => 'Footer copyright notice',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
