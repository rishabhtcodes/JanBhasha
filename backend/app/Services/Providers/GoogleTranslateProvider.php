<?php

namespace App\Services\Providers;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GoogleTranslateProvider
{
    private const API_URL = 'https://translation.googleapis.com/language/translate/v2';

    public function __construct(
        private readonly string $apiKey,
    ) {}

    /**
     * Translate text using the Google Cloud Translation API v2.
     *
     * @throws RuntimeException
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        $response = Http::timeout(30)
            ->get(self::API_URL, [
                'key'    => $this->apiKey,
                'q'      => $text,
                'source' => $sourceLang,
                'target' => $targetLang,
                'format' => 'text',
            ]);

        if ($response->failed()) {
            $errorMessage = $response->json('error.message') ?? $response->status();
            throw new RuntimeException('Google Translate error: ' . $errorMessage);
        }

        $translated = $response->json('data.translations.0.translatedText');

        if (!is_string($translated)) {
            throw new RuntimeException('Google Translate returned an unexpected response format.');
        }

        return html_entity_decode($translated, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
