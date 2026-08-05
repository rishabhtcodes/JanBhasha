<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organisation_id',
        'role',
        'tour_completed',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'tour_completed'    => 'boolean',
        ];
    }

    // ──────────────────────────────────────────
    // Avatar Helper
    // ──────────────────────────────────────────

    /**
     * Returns the public URL for the user's avatar.
     * Falls back to a generated initials avatar if no photo is set.
     */
    public function avatarUrl(): string
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }
        // Initials-based fallback avatar
        $initial = strtoupper(substr($this->name ?? 'U', 0, 1));
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'User')
            . '&background=2563eb&color=fff&bold=true&size=128';
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function organisation(): BelongsTo
    {
        return $this->belongsTo(Organisation::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(Translation::class);
    }

    // ──────────────────────────────────────────
    // Role helpers
    // ──────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isTranslator(): bool
    {
        return $this->role === 'translator';
    }

    // ──────────────────────────────────────────
    // Password Reset — branded notification
    // ──────────────────────────────────────────

    /**
     * Send the password reset notification using JanBhasha branded email.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
