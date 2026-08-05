<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GlossaryController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TranslationController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────
// Public routes
// ──────────────────────────────────────────

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Public demo translation
Route::post('/api/translate', [TranslationController::class, 'demoTranslate'])->name('demo.translate');

// Contact form (chat widget) — public, no auth required
Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:100'],
        'email'   => ['required', 'email', 'max:255'],
        'subject' => ['required', 'string', 'max:150'],
        'reason'  => ['required', 'string', 'max:2000'],
    ]);

    $submittedAt = now()->setTimezone('Asia/Kolkata')->format('D, d M Y \a\t h:i A T');

    // 1. Notify JanBhasha admin with full inquiry details
    \Illuminate\Support\Facades\Mail::to(config('mail.from.address'))
        ->queue(new \App\Mail\ContactInquiryMail(
            senderName:  $validated['name'],
            senderEmail: $validated['email'],
            subject:     $validated['subject'],
            reason:      $validated['reason'],
            submittedAt: $submittedAt,
        ));

    // 2. Send acknowledgement to the person who submitted
    \Illuminate\Support\Facades\Mail::to($validated['email'])
        ->queue(new \App\Mail\ContactConfirmationMail(
            senderName:  $validated['name'],
            senderEmail: $validated['email'],
            subject:     $validated['subject'],
            reason:      $validated['reason'],
        ));

    return response()->json(['ok' => true]);
})->name('contact.submit');


// Tour complete
Route::post('/tour/complete', function () {
    auth()->user()->update(['tour_completed' => true]);
    return response()->json(['ok' => true]);
})->middleware('auth')->name('tour.complete');

// ──────────────────────────────────────────
// Authenticated routes (Breeze session auth)
// ──────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Profile Avatar
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    Route::delete('/profile/avatar', [ProfileController::class, 'removeAvatar'])->name('profile.avatar.remove');

    // Personal Translation History
    Route::get('/my-history', [ProfileController::class, 'history'])->name('profile.history');

    // Translations
    
    Route::resource('translations', TranslationController::class)
        ->only(['index', 'create', 'store', 'show', 'destroy']);

    // Glossary
    Route::resource('glossary', GlossaryController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // ──────────────────────────────────────────
    // Super-admin panel
    // ──────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {

        // Admin Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // Organisations (CRUD)
        Route::resource('organisations', OrganisationController::class);
        Route::post(
            'organisations/{organisation}/regenerate-key',
            [OrganisationController::class, 'regenerateApiKey']
        )->name('organisations.regenerate-key');

        // Users (CRUD)
        Route::get('/users',                [AdminController::class, 'users'])->name('users.index');
        Route::get('/users/create',         [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users',               [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit',    [AdminController::class, 'editUser'])->name('users.edit');
        Route::patch('/users/{user}',       [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}',      [AdminController::class, 'destroyUser'])->name('users.destroy');
    });
});

require __DIR__ . '/auth.php';
