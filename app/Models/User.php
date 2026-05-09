<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\MemberStatus;
use App\Enums\UserRole;
use App\Enums\Permission;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'current_workspace_id', 'name', 'email', 'password', 'bio', 'avatar',
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
    public function canBypassMaintenance(): bool
    {
        return $this->can(Permission::BypassMaintenance->value);
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


     // ─── Relationships workspace ─────────────────────────────────────────────────────────

    public function currentWorkspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'current_workspace_id');
    }

    public function workspaces(): BelongsToMany
    {
        return $this->belongsToMany(Workspace::class, 'workspace_members')
            ->withPivot(['workspace_role_id', 'status', 'joined_at'])
            ->withTimestamps();
    }

    public function ownedWorkspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'owner_id');
    }


     // ─── Helpers workspace ───────────────────────────────────────────────────────────────

    /**
     * Check if the user belongs to a given workspace (active membership).
     */
    public function belongsToWorkspace(Workspace $workspace): bool
    {
        return $this->workspaces()
            ->wherePivot('workspace_id', $workspace->id)
            ->wherePivot('status', MemberStatus::Active->value)
            ->exists();
    }

    /**
     * Get active membership record for a workspace.
     *
     * FIX: Use withoutTrashed() explicitly and exclude soft-deleted records.
     * Without this, a previously-removed (soft-deleted) membership would be
     * returned, causing hasPermission() to always fail with a 403.
     */
    public function membershipFor(Workspace $workspace): ?WorkspaceMember
    {
        return WorkspaceMember::withoutTrashed()
            ->where('workspace_id', $workspace->id)
            ->where('user_id', $this->id)
            ->first();
    }

    /**
     * Check if user has a specific permission in the given (or current) workspace.
     *
     * FIX: Compare member->status using the MemberStatus enum value, not a raw
     * string. The status column is cast to MemberStatus, so strict comparison
     * against the string 'active' always fails.
     */
    public function hasPermission(string $permission, ?Workspace $workspace = null): bool
    {
        $workspace ??= $this->currentWorkspace;

        if (! $workspace) {
            return false;
        }

        // Workspace owner always has all permissions
        if ($workspace->owner_id === $this->id) {
            return true;
        }

        $member = $this->membershipFor($workspace);

        if (! $member) {
            return false;
        }

        // FIX: compare enum value correctly — status is cast to MemberStatus enum
        if ($member->status !== MemberStatus::Active) {
            return false;
        }

        return $member->role?->hasPermission($permission) ?? false;
    }





}
