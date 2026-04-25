<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Organisation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'api_key',
        'email',
        'website',
        'department',
        'is_active',
        'monthly_char_limit',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'monthly_char_limit' => 'integer',
    ];

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    public function glossaries(): HasMany
    {
        return $this->hasMany(Glossary::class);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Generate a cryptographically secure API key for a new organisation.
     */
    public static function generateApiKey(): string
    {
        return 'jb_' . Str::random(58);  // 61 chars total, fits in 64-char column
    }

    /**
     * Characters translated this calendar month.
     */
    public function monthlyCharactersUsed(): int
    {
        return $this->translations()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('characters');
    }

    /**
     * Whether the organisation has exceeded its monthly quota.
     */
    public function hasExceededQuota(): bool
    {
        return $this->monthlyCharactersUsed() >= $this->monthly_char_limit;
    }

    // ──────────────────────────────────────────
    // Boot
    // ──────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (Organisation $org) {
            if (empty($org->api_key)) {
                $org->api_key = static::generateApiKey();
            }
            if (empty($org->slug)) {
                $org->slug = Str::slug($org->name);
            }
        });
    }
}
