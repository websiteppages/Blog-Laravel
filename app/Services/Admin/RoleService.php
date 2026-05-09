<?php

namespace App\Services\Admin;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository,
        protected UserService $userService
    ) {}

    public function getCustomRoles(): Collection
    {
        return $this->roleRepository->CustomRoles();
    }

    public function getAll(): Collection
    {
        return $this->roleRepository->all();
    }

    public function getUsers()
    {
        return $this->userService->getUsersWithRoles();
    }

    public function getPaginated(): LengthAwarePaginator
    {
        return $this->roleRepository->paginate(20);
    }

    public function findById(int $id): ?Role
    {
        return $this->roleRepository->findById($id);
    }

    public function getPermissionsGrouped(): array
    {
        return $this->roleRepository->getPermissionsGrouped();
    }

    public function getRolePermissions(Role $role): array
    {
        return $role->permissions->pluck('name')->toArray();
    }

    public function create(array $data): Role
    {
        $data['guard_name'] = 'web';

        $role = $this->roleRepository->create($data);

        return $this->roleRepository->syncPermissions(
            $role,
            $data['permissions'] ?? []
        );
    }

    public function update(Role $role, array $permissions): Role
    {
        // 🔒 Prevent modifying immutable roles
        if ($role->isImmutable()) {
            throw new \RuntimeException(
                "Role \"{$role->name}\" cannot be modified."
            );
        }

        return $this->roleRepository->syncPermissions($role, $permissions);
    }

    public function delete(Role $role): bool
    {
        // 🔒 Prevent deleting protected roles
        if ($role->isProtected()) {
            throw new \RuntimeException(
                "Role \"{$role->name}\" is protected and cannot be deleted."
            );
        }

        // 🚫 Prevent deleting roles assigned to users
        if ($role->users()->count() > 0) {
            throw new \RuntimeException(
                "Cannot delete \"{$role->name}\" — {$role->users()->count()} users have this role."
            );
        }

        return $this->roleRepository->delete($role);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS MATRIX (DYNAMIC - NO HARDCODE)
    |--------------------------------------------------------------------------
    */

    public function getAccessMatrix(): array
    {
        $roles = Role::all()->pluck('name')->toArray();
        $groups = \App\Enums\Permission::grouped();

        $matrix = [];

        foreach ($groups as $groupName => $permissions) {

            $row = [
                'capability' => $groupName,
                'access' => []
            ];

            foreach ($roles as $roleName) {

                $role = Role::findByName($roleName);

                $rolePermissions = $role->permissions->pluck('name')->toArray();

                $hasAccess = false;

                foreach ($permissions as $permission) {
                    if (in_array($permission->value, $rolePermissions)) {
                        $hasAccess = true;
                        break;
                    }
                }

                $row['access'][] = $hasAccess ? 1 : 0;
            }

            $matrix[] = $row;
        }

        return $matrix;
    }

}
