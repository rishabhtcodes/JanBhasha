<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTranslationRequest;
use App\Models\Organisation;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationController extends Controller
{
    public function __construct(
        private readonly TranslationService $translationService,
    ) {}

    /**
     * POST /api/v1/translate
     *
     * Submit text for English → Hindi translation.
     */
    public function store(StoreTranslationRequest $request): JsonResponse
    {
        /** @var Organisation $org */
        $org = $request->attributes->get('organisation');

        try {
            $translation = $this->translationService->translate(
                text:       $request->validated('source_text'),
                org:        $org,
                user:       null,
                sourceLang: $request->input('source_lang', 'en'),
                targetLang: $request->input('target_lang', 'hi'),
                label:      $request->validated('source_label'),
            );

            return response()->json([
                'success'         => true,
                'translation_id'  => $translation->id,
                'source_text'     => $translation->source_text,
                'translated_text' => $translation->translated_text,
                'provider'        => $translation->provider,
                'characters'      => $translation->characters,
                'is_cached'       => $translation->is_cached,
                'created_at'      => $translation->created_at->toIso8601String(),
            ], Response::HTTP_CREATED);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * GET /api/v1/history
     *
     * Retrieve paginated translation history for the organisation.
     */
    public function index(Request $request): JsonResponse
    {
        /** @var Organisation $org */
        $org = $request->attributes->get('organisation');

        $translations = Translation::forOrganisation($org->id)
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(
                perPage: min((int) $request->input('per_page', 20), 100),
            );

        return response()->json([
            'success' => true,
            'data'    => $translations->map(fn (Translation $t) => [
                'id'              => $t->id,
                'source_label'    => $t->source_label,
                'source_text'     => $t->source_preview,
                'translated_text' => $t->translated_text,
                'characters'      => $t->characters,
                'status'          => $t->status,
                'is_cached'       => $t->is_cached,
                'created_at'      => $t->created_at->toIso8601String(),
            ]),
            'pagination' => [
                'total'        => $translations->total(),
                'per_page'     => $translations->perPage(),
                'current_page' => $translations->currentPage(),
                'last_page'    => $translations->lastPage(),
            ],
        ]);
    }

    /**
     * GET /api/v1/usage
     *
     * Return quota usage for the organisation.
     */
    public function usage(Request $request): JsonResponse
    {
        /** @var Organisation $org */
        $org = $request->attributes->get('organisation');

        $used  = $org->monthlyCharactersUsed();
        $quota = $org->monthly_char_limit;

        return response()->json([
            'success'           => true,
            'organisation'      => $org->name,
            'monthly_quota'     => $quota,
            'characters_used'   => $used,
            'characters_left'   => max(0, $quota - $used),
            'quota_percent'     => $quota > 0 ? round(($used / $quota) * 100, 2) : 0,
        ]);
    }
}
