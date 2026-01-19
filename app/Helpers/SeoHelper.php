<?php

namespace App\Helpers;

class SeoHelper
{
    /**
     * Generate SEO meta tags array
     */
    public static function generateMeta(array $data = []): array
    {
        $siteName = setting('site_name', 'Cambridge College');
        $siteUrl = config('app.url');

        return [
            'title' => $data['title'] ?? setting('site_title', $siteName),
            'description' => $data['description'] ?? setting('site_description', 'Best education and courses'),
            'keywords' => $data['keywords'] ?? setting('seo_keywords', 'education, courses, training'),
            'canonical' => $data['canonical'] ?? UrlHelper::canonicalUrl(),
            'og_title' => $data['og_title'] ?? ($data['title'] ?? setting('site_title', $siteName)),
            'og_description' => $data['og_description'] ?? ($data['description'] ?? setting('site_description', 'Best education and courses')),
            'og_image' => $data['og_image'] ?? ($siteUrl . '/images/og-image.jpg'),
            'og_url' => $data['og_url'] ?? UrlHelper::canonicalUrl(),
            'og_type' => $data['og_type'] ?? 'website',
            'twitter_card' => $data['twitter_card'] ?? 'summary_large_image',
            'twitter_title' => $data['twitter_title'] ?? ($data['title'] ?? setting('site_title', $siteName)),
            'twitter_description' => $data['twitter_description'] ?? ($data['description'] ?? setting('site_description', 'Best education and courses')),
            'twitter_image' => $data['twitter_image'] ?? ($siteUrl . '/images/og-image.jpg'),
        ];
    }

    /**
     * Generate Course Schema.org JSON-LD
     */
    public static function generateCourseSchema($course): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $course->title,
            'description' => strip_tags($course->description ?? $course->short_description ?? ''),
            'provider' => [
                '@type' => 'Organization',
                'name' => setting('site_name', 'Cambridge College'),
                'url' => config('app.url'),
            ],
        ];

        // Add optional fields if available
        if ($course->course_code) {
            $schema['courseCode'] = $course->course_code;
        }

        if ($course->image) {
            $schema['image'] = asset('storage/' . $course->image);
        }

        if ($course->duration) {
            $schema['timeRequired'] = $course->duration;
        }

        if ($course->fee) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $course->fee,
                'priceCurrency' => 'LYD',
            ];
        }

        if ($course->level) {
            $schema['educationalLevel'] = $course->level->name;
        }

        if ($course->category) {
            $schema['about'] = [
                '@type' => 'Thing',
                'name' => $course->category->name,
            ];
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Organization Schema.org JSON-LD
     */
    public static function generateOrganizationSchema(): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => setting('site_name', 'Cambridge College'),
            'url' => config('app.url'),
            'logo' => setting('site_logo') ? asset('storage/' . setting('site_logo')) : null,
            'description' => setting('site_description', 'Best education and courses'),
        ];

        // Add contact information if available
        $contactInfo = [];

        if (setting('contact_phone')) {
            $contactInfo['telephone'] = setting('contact_phone');
        }

        if (setting('contact_email')) {
            $contactInfo['email'] = setting('contact_email');
        }

        if (!empty($contactInfo)) {
            $schema['contactPoint'] = array_merge([
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
            ], $contactInfo);
        }

        // Add social media links if available
        $socialLinks = [];

        if (setting('social_facebook')) {
            $socialLinks[] = setting('social_facebook');
        }

        if (setting('social_twitter')) {
            $socialLinks[] = setting('social_twitter');
        }

        if (setting('social_instagram')) {
            $socialLinks[] = setting('social_instagram');
        }

        if (setting('social_linkedin')) {
            $socialLinks[] = setting('social_linkedin');
        }

        if (!empty($socialLinks)) {
            $schema['sameAs'] = $socialLinks;
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Generate BreadcrumbList Schema.org JSON-LD
     */
    public static function generateBreadcrumbSchema(array $items): string
    {
        $listItems = [];

        foreach ($items as $position => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Clean and limit text for meta descriptions
     */
    public static function cleanDescription(?string $text, int $limit = 160): string
    {
        if (empty($text)) {
            return '';
        }

        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        if (mb_strlen($text) > $limit) {
            $text = mb_substr($text, 0, $limit);
            $text = mb_substr($text, 0, mb_strrpos($text, ' '));
            $text .= '...';
        }

        return $text;
    }

    /**
     * Extract keywords from text
     */
    public static function extractKeywords(string $text, int $limit = 10): string
    {
        $text = strip_tags($text);
        $text = strtolower($text);

        // Remove common words (stop words)
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by'];
        $words = str_word_count($text, 1);
        $words = array_diff($words, $stopWords);

        // Count word frequency
        $wordCount = array_count_values($words);
        arsort($wordCount);

        // Get top keywords
        $keywords = array_slice(array_keys($wordCount), 0, $limit);

        return implode(', ', $keywords);
    }
}
