<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Owner full access except sensitive actions
        if ($user->hasRole(UserRole::Owner->value)) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewUsers->value);
    }

    public function view(User $user, User $target): bool
    {
        return $user->id === $target->id
            || $user->can(Permission::ViewUsers->value);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CreateUsers->value);
    }

    public function update(User $user, User $target): bool
    {
        if ($user->id === $target->id) {
            return true;
        }

        return $user->can(Permission::EditUsers->value);
    }

    public function delete(User $user, User $target): bool
    {
        if ($user->id === $target->id) {
            return false;
        }

        if ($target->hasRole(UserRole::Owner->value)) {
            return false;
        }

        return $user->can(Permission::DeleteUsers->value);
    }

    public function removeUserRole(User $authUser, User $target): bool
    {
        // ❌ cannot remove own role
        if ($authUser->id === $target->id) {
            return false;
        }

        // ❌ cannot modify owner
        if ($target->hasRole(UserRole::Owner->value)) {
            return false;
        }
        // ✅ must have permission
        return $authUser->can(Permission::ManageRoles->value);
    }
}
