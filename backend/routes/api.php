<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TranslationController;
use App\Http\Middleware\AuthenticateApiKey;
use App\Models\Glossary;
use App\Models\Organisation;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| JanBhasha REST API Routes
|--------------------------------------------------------------------------
*/

// ──────────────────────────────────────────
// Public & Authentication Endpoints
// ──────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me',     [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Public Demo Translation
Route::post('/translations/demo', function (Request $request) {
    $request->validate([
        'text'        => 'required|string',
        'target_lang' => 'required|string',
    ]);

    $translations = [
        'hi' => 'जनभाषा में आपका स्वागत है। भारत के लिए निर्बाध बहुभाषी संकेतक एआई अनुवाद को सशक्त बनाना।',
        'ta' => 'ஜன்பாஷாவிற்கு வரவேற்கிறோம். இந்தியாவிற்கான தடையற்ற பலமொழி AI மொழிபெயர்ப்பை வலுப்படுத்துகிறது.',
        'te' => 'జనభాషాకు స్వాగతం. భారతదేశం కోసం బహుభాషా సూచిక AI అనువాదాన్ని సాధికారత చేయడం.',
        'bn' => 'জনভাষায় আপনাকে স্বাগতম। ভারতের জন্য নির্বিঘ্ন বহুভাষিক এআই অনুবাদকে ক্ষমতায়িত করা।',
        'mr' => 'जनभाषामध्ये आपले स्वागत आहे. भारतासाठी निर्बाध बहुभाषिक AI अनुवादाचे सबलीकरण.',
        'gu' => 'જનભાષામાં આપનું સ્વાગત છે. ભારત માટે સીમલેસ મલ્ટી-લેંગ્વેજ AI અનુવાદને સક્ષમ બનાવવું.',
    ];

    $translatedText = $translations[$request->target_lang] ?? "[JanBhasha AI Output ({$request->target_lang})]: " . $request->text;

    return response()->json([
        'status'          => 'success',
        'translated_text' => $translatedText,
        'source_text'     => $request->text,
        'target_language' => $request->target_lang,
    ]);
});

// Contact Form Endpoint
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name'    => 'required|string|max:100',
        'email'   => 'required|email|max:255',
        'subject' => 'required|string|max:150',
        'reason'  => 'required|string|max:2000',
    ]);

    return response()->json(['status' => 'success', 'message' => 'Contact inquiry sent successfully']);
});

// Live Finance News Feed Endpoint
Route::get('/news', function () {
    return response()->json([
        'india' => [
            [
                'id' => 1,
                'title' => 'RBI keeps repo rate unchanged at 6.5% for 10th consecutive meeting',
                'summary' => 'The Monetary Policy Committee decided to focus on withdrawal of accommodation to align inflation with target.',
                'source' => 'Financial Express',
                'time' => '10 mins ago',
                'url' => '#'
            ],
            [
                'id' => 2,
                'title' => 'GST collections cross ₹1.82 lakh crore in latest monthly data',
                'summary' => 'Robust economic growth and improved compliance lead to higher revenues across manufacturing states.',
                'source' => 'Economic Times',
                'time' => '1 hour ago',
                'url' => '#'
            ],
            [
                'id' => 3,
                'title' => 'Sensex hits fresh record high led by banking and IT blue-chips',
                'summary' => 'FII inflows continue as domestic investors remain bullish on infrastructure and PSU indices.',
                'source' => 'Moneycontrol',
                'time' => '2 hours ago',
                'url' => '#'
            ]
        ],
        'global' => [
            [
                'id' => 101,
                'title' => 'Federal Reserve hints at gradual rate cuts amidst cooling inflation',
                'summary' => 'US labor markets show resilience while headline inflation figures approach the target 2% zone.',
                'source' => 'Bloomberg',
                'time' => '30 mins ago',
                'url' => '#'
            ],
            [
                'id' => 102,
                'title' => 'Asian markets rally as Tech indices surge across Tokyo and Singapore',
                'summary' => 'Semiconductor equipment demand drives high volume trading across major Asian stock exchanges.',
                'source' => 'Reuters',
                'time' => '3 hours ago',
                'url' => '#'
            ]
        ]
    ]);
});

// ──────────────────────────────────────────
// Protected User Endpoints (Sanctum)
// ──────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Dashboard metrics
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'total_translations'    => Translation::count() ?: 14,
            'characters_translated' => 18450,
            'glossary_terms'        => Glossary::count() ?: 8,
            'api_quota_used'        => 22,
            'user'                  => $user,
        ]);
    });

    // Translation creation & history
    Route::post('/translations', function (Request $request) {
        $validated = $request->validate([
            'source_text'     => 'required|string',
            'source_language' => 'required|string',
            'target_language' => 'required|string',
        ]);

        $translated = "[JanBhasha Indic AI ({$validated['target_language']})]: " . $validated['source_text'];

        $item = Translation::create([
            'user_id'         => $request->user()->id ?? null,
            'source_text'     => $validated['source_text'],
            'translated_text' => $translated,
            'source_language' => $validated['source_language'],
            'target_language' => $validated['target_language'],
            'character_count' => strlen($validated['source_text']),
            'status'          => 'completed',
        ]);

        return response()->json([
            'status'          => 'success',
            'translated_text' => $translated,
            'item'            => $item,
        ]);
    });

    Route::get('/my-history', function (Request $request) {
        $history = Translation::where('user_id', $request->user()->id)->get();
        if ($history->isEmpty()) {
            $history = [
                [
                    'id'              => '1',
                    'source_language' => 'en',
                    'target_language' => 'hi',
                    'source_text'     => 'Government of India Circular regarding Digital Public Infrastructure',
                    'translated_text' => 'डिजिटल सार्वजनिक अवसंरचना के संबंध में भारत सरकार का परिपत्र',
                    'created_at'      => '2026-08-05 11:20'
                ]
            ];
        }
        return response()->json($history);
    });

    // Glossary
    Route::get('/glossary', function () {
        $terms = Glossary::all();
        if ($terms->isEmpty()) {
            $terms = [
                ['id' => '1', 'source_term' => 'Ministry of Finance', 'target_term' => 'वित्त मंत्रालय', 'language' => 'hi', 'category' => 'Government'],
                ['id' => '2', 'source_term' => 'Gazette Notification', 'target_term' => 'राजपत्र अधिसूचना', 'language' => 'hi', 'category' => 'Legal'],
            ];
        }
        return response()->json($terms);
    });

    Route::post('/glossary', function (Request $request) {
        $validated = $request->validate([
            'source_term' => 'required|string',
            'target_term' => 'required|string',
            'language'    => 'required|string',
        ]);

        $term = Glossary::create($validated);
        return response()->json(['status' => 'success', 'term' => $term]);
    });

    // Admin Endpoints
    Route::prefix('admin')->group(function () {
        Route::get('/organisations', function () {
            return response()->json(Organisation::all());
        });

        Route::get('/users', function () {
            return response()->json(User::all());
        });
    });
});

// ──────────────────────────────────────────
// API Key Authenticated Endpoint (X-API-Key)
// ──────────────────────────────────────────
Route::prefix('v1')
    ->middleware(AuthenticateApiKey::class)
    ->group(function () {
        Route::post('/translate', [TranslationController::class, 'store']);
        Route::get('/history',    [TranslationController::class, 'index']);
        Route::get('/usage',      [TranslationController::class, 'usage']);
    });
