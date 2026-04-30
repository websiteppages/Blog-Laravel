<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;

class Role extends SpatieRole
{
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

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeProtected(Builder $query): Builder
    {
        return $query->whereIn('name', collect(UserRole::cases())
            ->filter(fn ($role) => $role->isProtected())
            ->map(fn ($role) => $role->value)
            ->toArray()
        );
    }

    public function scopeEditable(Builder $query): Builder
    {
        return $query->whereNotIn('name', collect(UserRole::cases())
            ->filter(fn ($role) => $role->isImmutable())
            ->map(fn ($role) => $role->value)
            ->toArray()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Convenience Methods
    |--------------------------------------------------------------------------
    */

    public function hasName(string $role): bool
    {
        return $this->name === $role;
    }

    public function hasUsers(): bool
    {
        return $this->users()->exists();
    }

    public function usersCount(): int
    {
        return $this->users()->count();
    }
}
