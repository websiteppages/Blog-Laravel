<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
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

    public function isEditor(): bool
    {
        return $this->hasRole(UserRole::Editor->value);
    }

    public function isAuthor(): bool
    {
        return $this->hasRole(UserRole::Author->value);
    }

    public function canAccessDashboard(): bool
    {
        return $this->hasPermissionTo('access dashboard');
    }


}
