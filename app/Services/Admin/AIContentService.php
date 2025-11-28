<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use OpenAI;

class AIContentService
{
    /**
     * Get AI provider from config (openai or gemini)
     */
    public function getAIProvider()
    {
        return env('AI_PROVIDER', 'gemini'); // Default to Gemini (free)
    }

    /**
     * Generate content with retry logic.
     */
    public function generateContent(string $title)
    {
        $maxRetries = 3;
        $retryDelay = 2;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $provider = $this->getAIProvider();

                if ($provider === 'gemini') {
                    $content = $this->generateWithGemini($title);
                } else {
                    $content = $this->generateWithOpenAI($title);
                }

                return [
                    'success' => true,
                    'content' => $content,
                    'provider' => $provider
                ];

            } catch (\Exception $e) {
                Log::error("AI API Error (Attempt $attempt/$maxRetries): " . $e->getMessage());

                $isRateLimit = str_contains($e->getMessage(), 'rate limit') || str_contains($e->getMessage(), 'Rate limit');

                if ($isRateLimit && $attempt < $maxRetries) {
                    sleep($retryDelay * $attempt);
                    continue;
                }

                if ($isRateLimit) {
                    return [
                        'success' => false,
                        'message' => 'تم تجاوز حد الطلبات. تم المحاولة ' . $maxRetries . ' مرات. الرجاء المحاولة لاحقاً.',
                        'code' => 429
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'خطأ في الذكاء الاصطناعي: ' . $e->getMessage(),
                    'code' => 500
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'فشل توليد المحتوى بعد عدة محاولات',
            'code' => 500
        ];
    }

    /**
     * Improve content with retry logic.
     */
    public function improveContent(string $content)
    {
        $maxRetries = 3;
        $retryDelay = 2;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $provider = $this->getAIProvider();

                if ($provider === 'gemini') {
                    $improved = $this->improveWithGemini($content);
                } else {
                    $improved = $this->improveWithOpenAI($content);
                }

                return [
                    'success' => true,
                    'content' => $improved,
                    'provider' => $provider
                ];

            } catch (\Exception $e) {
                Log::error("AI Improve Error (Attempt $attempt/$maxRetries): " . $e->getMessage());

                $isRateLimit = str_contains($e->getMessage(), 'rate limit') || str_contains($e->getMessage(), 'Rate limit');

                if ($isRateLimit && $attempt < $maxRetries) {
                    sleep($retryDelay * $attempt);
                    continue;
                }

                if ($isRateLimit) {
                    return [
                        'success' => false,
                        'message' => 'تم تجاوز حد الطلبات. تم المحاولة ' . $maxRetries . ' مرات. الرجاء المحاولة لاحقاً.',
                        'code' => 429
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'خطأ في الذكاء الاصطناعي: ' . $e->getMessage(),
                    'code' => 500
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'فشل تحسين المحتوى بعد عدة محاولات',
            'code' => 500
        ];
    }

    /**
     * Generate content using OpenAI
     */
    private function generateWithOpenAI($title)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional content writer for Cambridge International College in UK, a prestigious online education institution. Create well-structured, professional HTML content for web pages. Use proper HTML tags with inline styling. Use navy blue (#1e3a8a), gold (#ffcc00), and light blue (#3b82f6) as primary colors.'
                ],
                [
                    'role' => 'user',
                    'content' => "Create professional, detailed HTML content for a page titled: '$title'. Include:
                    - A compelling introduction (2-3 paragraphs)
                    - Key highlights or features (4-6 bullet points in a styled box)
                    - Detailed explanation (2-3 paragraphs)
                    - A call-to-action section
                    - Contact information reference

                    Format everything in HTML with inline CSS. Make it professional and informative."
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000
        ]);

        return $response->choices[0]->message->content;
    }

    /**
     * Generate content using Google Gemini
     */
    private function generateWithGemini($title)
    {
        $apiKey = env('GEMINI_API_KEY');

        $prompt = "You are a professional content writer for Cambridge International College in UK, a prestigious online education institution.\n\n" .
                  "Create professional, detailed HTML content for a page titled: '$title'.\n\n" .
                  "Include:\n" .
                  "- A compelling introduction (2-3 paragraphs)\n" .
                  "- Key highlights or features (4-6 bullet points in a styled box)\n" .
                  "- Detailed explanation (2-3 paragraphs)\n" .
                  "- A call-to-action section\n" .
                  "- Contact information reference\n\n" .
                  "Format everything in HTML with inline CSS. Use colors: navy blue (#1e3a8a), gold (#ffcc00), and light blue (#3b82f6). Make it professional and informative.";

        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2000,
            ]
        ]);

        if ($response->failed()) {
            throw new \Exception('Gemini API Error: ' . $response->body());
        }

        $data = $response->json();
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Failed to generate content';
    }

    /**
     * Improve content using OpenAI
     */
    private function improveWithOpenAI($content)
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional content editor for Cambridge International College in UK. Improve and format content into professional HTML. Maintain the original meaning but enhance clarity, structure, and professionalism. Use inline CSS with colors: navy blue (#1e3a8a), gold (#ffcc00), light blue (#3b82f6).'
                ],
                [
                    'role' => 'user',
                    'content' => "Improve and format this content into professional HTML:\n\n$content\n\nMake it:\n- Well-structured with proper headings\n- Professional and engaging\n- Properly formatted with inline CSS\n- Clear and concise\n- Keep all important information"
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000
        ]);

        return $response->choices[0]->message->content;
    }

    /**
     * Improve content using Google Gemini
     */
    private function improveWithGemini($content)
    {
        $apiKey = env('GEMINI_API_KEY');

        $prompt = "You are a professional content editor for Cambridge International College in UK.\n\n" .
                  "Improve and format this content into professional HTML:\n\n$content\n\n" .
                  "Make it:\n" .
                  "- Well-structured with proper headings\n" .
                  "- Professional and engaging\n" .
                  "- Properly formatted with inline CSS\n" .
                  "- Clear and concise\n" .
                  "- Keep all important information\n\n" .
                  "Use colors: navy blue (#1e3a8a), gold (#ffcc00), light blue (#3b82f6).";

        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 2000,
            ]
        ]);

        if ($response->failed()) {
            throw new \Exception('Gemini API Error: ' . $response->body());
        }

        $data = $response->json();
        return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Failed to improve content';
    }
}
