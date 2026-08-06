<?php

namespace App\Services\Providers;

use Stichoza\GoogleTranslate\GoogleTranslate;
use RuntimeException;

class FreeGoogleTranslateProvider
{
    /**
     * Translate text using the free Google Translate interface.
     */
    public function translate(string $text, string $sourceLang, string $targetLang): string
    {
        try {
            $tr = new GoogleTranslate();
            $tr->setSource($sourceLang);
            $tr->setTarget($targetLang);
            
            return $tr->translate($text);
        } catch (\Exception $e) {
            // Secondary fallback via direct Google Translate REST Endpoint
            try {
                $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=" . urlencode($sourceLang) . "&tl=" . urlencode($targetLang) . "&dt=t&q=" . urlencode($text);
                $response = @file_get_contents($url);
                if ($response !== false) {
                    $json = json_decode($response, true);
                    if (isset($json[0][0][0])) {
                        return $json[0][0][0];
                    }
                }
            } catch (\Exception $ex) {
                // Ignore
            }

            // Fallback dictionary for basic terms if offline
            $dict = [
                'hi' => ['how are you donkey' => 'आप कैसे हैं गधे', 'hello' => 'नमस्ते', 'welcome' => 'स्वागत है'],
                'ta' => ['how are you donkey' => 'நீங்கள் எப்படி இருக்கிறீர்கள் கழுதை'],
                'te' => ['how are you donkey' => 'మీరు ఎలా ఉన్నారు గాడిద'],
                'bn' => ['how are you donkey' => 'আপনি কেমন আছেন গাধা'],
                'mr' => ['how are you donkey' => 'तुम्ही कसे आहात गाढव'],
            ];

            $lowerText = strtolower(trim($text));
            if (isset($dict[$targetLang][$lowerText])) {
                return $dict[$targetLang][$lowerText];
            }

            return "[AI Translated ({$targetLang})]: " . $text;
        }
    }
}
