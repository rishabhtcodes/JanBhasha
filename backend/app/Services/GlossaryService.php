<?php

namespace App\Services;

use App\Models\Glossary;
use App\Models\Organisation;

class GlossaryService
{
    /**
     * Replace glossary source terms with placeholder tokens before sending to the
     * translation API. This prevents API from mangling domain-specific terms.
     *
     * @return array{text: string, map: array<string, string>}
     */
    public function tokenize(string $text, Organisation $org): array
    {
        $glossaries = $org->glossaries()->get();
        $map = [];

        foreach ($glossaries as $index => $entry) {
            $token = "[[JBTK_{$index}]]";
            $map[$token] = $entry->target_term;

            $flags = $entry->case_sensitive ? 0 : PREG_OFFSET_CAPTURE;
            $pattern = $entry->case_sensitive
                ? '/' . preg_quote($entry->source_term, '/') . '/'
                : '/' . preg_quote($entry->source_term, '/') . '/i';

            $text = preg_replace($pattern, $token, $text) ?? $text;
        }

        return ['text' => $text, 'map' => $map];
    }

    /**
     * Replace placeholder tokens back with the desired Hindi target terms.
     */
    public function detokenize(string $text, array $map): string
    {
        return str_replace(array_keys($map), array_values($map), $text);
    }
}
