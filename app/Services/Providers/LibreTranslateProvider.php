<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class LibreTranslateProvider
{
    public function __construct(
        private readonly string $baseUrl,
        private readonly string $apiKey = '',
    ) {}

    /**
     * Translate text using the LibreTranslate API.
     *
     * @throws RuntimeException
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        $payload = [
            'q'      => $text,
            'source' => $sourceLang,
            'target' => $targetLang,
            'format' => 'text',
        ];

        if ($this->apiKey) {
            $payload['api_key'] = $this->apiKey;
        }

        $response = Http::timeout(30)
            ->post(rtrim($this->baseUrl, '/') . '/translate', $payload);

        if ($response->failed()) {
            throw new RuntimeException(
                'LibreTranslate error: ' . ($response->json('error') ?? $response->status())
            );
        }

        $translated = $response->json('translatedText');

        if (!is_string($translated)) {
            throw new RuntimeException('LibreTranslate returned an unexpected response format.');
        }

        return $translated;
    }
}
