<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTranslationRequest;
use App\Mail\TranslationThankYouMail;
use App\Models\Translation;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TranslationController extends Controller
{
    public function __construct(
        private readonly TranslationService $translationService,
    ) {}

    /**
     * List paginated translation history for the authenticated user's org.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $org  = $user->organisation;

        abort_if(!$org, 403, 'You are not associated with any organisation.');

        // Admins see all org translations; regular users only see their own
        $query = Translation::forOrganisation($org->id)->with('user');

        if (!$user->isAdmin()) {
            // Scope to just this user's translations
            $query->where('user_id', $user->id);
        }

        $translations = $query
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), fn ($q) => $q->where('source_text', 'like', '%' . $request->search . '%'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('translations.index', compact('translations', 'org'));
    }

    /**
     * Show the translation form.
     */
    public function create(Request $request)
    {
        $org = $request->user()->organisation;
        abort_if(!$org, 403, 'You are not associated with any organisation.');

        return view('translations.create', compact('org'));
    }

    /**
     * Submit and process a new translation.
     */
    public function store(StoreTranslationRequest $request)
    {
        $user = $request->user();
        $org  = $user->organisation;

        abort_if(!$org, 403, 'You are not associated with any organisation.');

        try {
            $translation = $this->translationService->translate(
                text:       $request->validated('source_text'),
                org:        $org,
                user:       $user,
                sourceLang: $request->validated('source_lang'),
                targetLang: $request->validated('target_lang'),
                label:      $request->validated('source_label'),
            );

            // Send thank-you email (queued)
            Mail::to($user->email)->queue(new TranslationThankYouMail($user, $translation));

            return redirect()
                ->route('translations.show', $translation)
                ->with('success', 'Translation completed successfully.');
        } catch (\RuntimeException $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show a single translation result.
     */
    public function show(Request $request, Translation $translation)
    {
        $org = $request->user()->organisation;
        abort_if($translation->organisation_id !== $org?->id, 403, 'You are not associated with any organisation.');

        return view('translations.show', compact('translation'));
    }

    /**
     * Delete (soft-delete) a translation log entry.
     */
    public function destroy(Request $request, Translation $translation)
    {
        $org = $request->user()->organisation;
        abort_if($translation->organisation_id !== $org?->id, 403, 'You are not associated with any organisation.');

        $translation->delete();

        return redirect()
            ->route('translations.index')
            ->with('success', 'Translation record deleted.');
    }

    /**
     * Public translation demo (no auth required).
     */
    public function demoTranslate(Request $request)
    {
        $request->validate([
            'text'            => 'required|string|max:5000',
            'source_language' => 'nullable|string|size:2',
            'target_language' => 'required|string|size:2',
        ]);
        
        try {
            $translated = $this->translationService->rawTranslate(
                $request->text,
                $request->input('source_language', 'en'),
                $request->target_language
            );
            
            return response()->json([
                'translated_text' => $translated,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
