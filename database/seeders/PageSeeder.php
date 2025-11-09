<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'is_published' => true,
            ],
            [
                'title' => 'Why choose CIC',
                'slug' => 'why-choose-cic',
                'is_published' => true,
            ],
            [
                'title' => 'Study Advice',
                'slug' => 'study-advice',
                'is_published' => true,
            ],
            [
                'title' => 'News & Information',
                'slug' => 'news-information',
                'is_published' => true,
            ],
            [
                'title' => 'Affiliates',
                'slug' => 'affiliates',
                'is_published' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'is_published' => true,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}
