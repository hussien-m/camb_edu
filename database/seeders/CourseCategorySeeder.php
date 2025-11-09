<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Accounting, Finance, Banking',
                'slug' => 'accounting-finance-banking',
            ],
            [
                'name' => 'Business Studies, Insurance, Law',
                'slug' => 'business-studies-insurance-law',
            ],
            [
                'name' => 'Economics, Commerce, Trade',
                'slug' => 'economics-commerce-trade',
            ],
            [
                'name' => 'English, Secretarial, Communication',
                'slug' => 'english-secretarial-communication',
            ],
            [
                'name' => 'Hotel, Tourism, Travel, Hospitality, Events',
                'slug' => 'hotel-tourism-travel-hospitality-events',
            ],
            [
                'name' => 'Management, Administration, Leadership',
                'slug' => 'management-administration-leadership',
            ],
            [
                'name' => 'Marketing, Sales, Advertising',
                'slug' => 'marketing-sales-advertising',
            ],
            [
                'name' => 'HR, Organisation, Education & Teaching',
                'slug' => 'hr-organisation-education-teaching',
            ],
            [
                'name' => 'Stores, Logistics, Purchasing, Materials',
                'slug' => 'stores-logistics-purchasing-materials',
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
}
