@php
    use Illuminate\Support\Str;

    $emailTitle  = 'Translation Complete — JanBhasha';
    $headerTitle = 'Your Translation is Ready! ✨';

    // Language display names (ISO 639-1 → readable)
    $langNames = [
        'en' => 'English',   'hi' => 'Hindi',     'bn' => 'Bengali',
        'ta' => 'Tamil',     'te' => 'Telugu',     'mr' => 'Marathi',
        'gu' => 'Gujarati',  'kn' => 'Kannada',   'ml' => 'Malayalam',
        'pa' => 'Punjabi',   'ur' => 'Urdu',      'or' => 'Odia',
        'as' => 'Assamese',  'sa' => 'Sanskrit',  'ne' => 'Nepali',
        'fr' => 'French',    'de' => 'German',    'es' => 'Spanish',
        'zh' => 'Chinese',   'ja' => 'Japanese',  'ko' => 'Korean',
        'ar' => 'Arabic',    'ru' => 'Russian',   'pt' => 'Portuguese',
    ];

    $sourceLangName = $langNames[$translation->source_lang ?? ''] ?? strtoupper($translation->source_lang ?? '?');
    $targetLangName = $langNames[$translation->target_lang ?? ''] ?? strtoupper($translation->target_lang ?? '?');

    $sourcePreview  = Str::limit($translation->source_text ?? '', 120);
    $targetPreview  = Str::limit($translation->translated_text ?? '', 120);
@endphp

@extends('emails.layout')

@section('content')

    <p class="greeting">Thank you, {{ $user->name }}! 🙏</p>

    <p class="body-text">
        Your translation has been completed successfully on JanBhasha.
        We appreciate you using our platform to bridge language barriers!
    </p>

    <div class="info-box">
        <p><span class="info-label">{{ $sourceLangName }} → {{ $targetLangName }}</span></p>

        @if($sourcePreview)
            <p style="margin-top: 10px; font-style: italic; color: #6b7280;">
                "{{ $sourcePreview }}"
            </p>
        @endif

        @if($targetPreview)
            <p style="margin-top: 8px; font-weight: 600; color: #1a202c;">
                "{{ $targetPreview }}"
            </p>
        @endif
    </div>

    <p class="body-text">
        Want to view the full translation, explore your history, or start a new one?
        Come back to JanBhasha — we're always here for you!
    </p>

    <div class="cta-container">
        <a href="{{ config('app.url') . '/dashboard' }}" class="cta-button">
            Visit JanBhasha Again →
        </a>
    </div>

    <div class="divider"></div>

    <p class="body-text" style="font-size: 13px; color: #718096; text-align: center;">
        Every translation you make helps us build a more connected, multilingual India. 🇮🇳
    </p>

@endsection
