<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Super Admin bypass
        if ($user->hasRole('owner')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewRoles->value);
    }

    public function create(User $user): bool
    {
        return $user->can(Permission::CreateRoles->value);
    }

    public function update(User $user, Role $role): bool
    {
        // 🔒 Prevent editing immutable roles
        if ($role->isImmutable()) {
            return false;
        }

        return $user->can(Permission::EditRoles->value);
    }

    public function delete(User $user, Role $role): bool
    {
        // 🔒 Prevent deleting protected roles
        if ($role->isProtected()) {
            return false;
        }

        return $user->can(Permission::DeleteRoles->value);
    }

    public function assign(User $user): bool
    {
        return $user->can(Permission::AssignRoles->value);
    }
}
