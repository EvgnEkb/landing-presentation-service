<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OpenAIAnalysisService implements AIAnalysisInterface
{
    protected string $apiKey;

    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = config('services.openai.api_url');
    }

    public function analyze(string $text): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->apiUrl, [
                'model'    => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'Ты — аналитик. Определи тональность (positive, neutral, negative) и классифицируй тип запроса (complaint, suggestion, question, other). Ответь JSON: {"sentiment": "...", "category": "..."}.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => $text,
                    ],
                ],
                'temperature' => 0.3,
                'max_tokens'  => 60,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                // Парсим JSON из ответа
                $result = json_decode($content, true);
                if (json_last_error() === \JSON_ERROR_NONE) {
                    return $result;
                }

                // Если не JSON, попробуем извлечь ключевые слова (запасной вариант)
                return $this->fallbackParse($content);
            }

            // Логируем ошибку API
            Log::warning('OpenAI API error', ['status' => $response->status(), 'body' => $response->body()]);

            return null;

        } catch (\Exception $e) {
            Log::error('OpenAI exception: ' . $e->getMessage());

            return null; // graceful fallback
        }
    }

    /**
     * Примитивный fallback, если API вернул не JSON.
     */
    private function fallbackParse(string $content): array
    {
        $sentiment = 'neutral';
        $category  = 'other';

        $lower = strtolower($content);
        if (str_contains($lower, 'positive')) {
            $sentiment = 'positive';
        } elseif (str_contains($lower, 'negative')) {
            $sentiment = 'negative';
        }

        if (str_contains($lower, 'complaint')) {
            $category = 'complaint';
        } elseif (str_contains($lower, 'suggestion')) {
            $category = 'suggestion';
        } elseif (str_contains($lower, 'question')) {
            $category = 'question';
        }

        return ['sentiment' => $sentiment, 'category' => $category];
    }
}
