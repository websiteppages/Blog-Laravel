<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'bio', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token',];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── Role helpers ───────────────────────────────────────

    public function isOwner(): bool
    {
        return $this->hasRole(UserRole::Owner->value);
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole([UserRole::Owner->value, UserRole::Admin->value]);
    }

    /*
    |--------------------------------------------------------------------------
    | Enum Bridge
    |--------------------------------------------------------------------------
    */

    public function toEnum(): ?UserRole
    {
        return UserRole::tryFrom($this->name);
    }

    public function isProtected(): bool
    {
        return $this->toEnum()?->isProtected() ?? false;
    }

    public function isImmutable(): bool
    {
        return $this->toEnum()?->isImmutable() ?? false;
    }

    // ── Relations ──────────────────────────────────────────

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function publishedPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id')
                    ->where('status', 'published')
                    ->whereNotNull('published_at');
    }

    // ── Accessors ──────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? Storage::disk('public')->url($this->avatar)
            : 'https://ui-avatars.com/api/?name='
              . urlencode($this->name)
              . '&background=c9883a&color=fff&bold=true';
    }

    public function getInitialsAttribute(): string
    {
        return collect(explode(' ', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn($p) => strtoupper($p[0]))
            ->implode('');
    }

    public function getRoleLabelAttribute(): string
    {
        $role = $this->getRoleNames()->first();
        return UserRole::tryFrom($role)?->label() ?? ucfirst($role ?? 'Reader');
    }





}
