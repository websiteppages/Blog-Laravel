<?php

namespace App\Repositories\Eloquent;

use App\Enums\Permission as PermEnum;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use App\Models\Role;
use App\Enums\UserRole;

class RoleRepository implements RoleRepositoryInterface
{
    public function all(): Collection
    {
        return Role::with('permissions')
                   ->withCount('users')
                   ->orderBy('id')
                   ->get();
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return Role::with('permissions')
                   ->withCount('users')
                   ->orderBy('name')
                   ->paginate($perPage);
    }

    public function findById(int $id): ?Role
    {
        return Role::with('permissions')
                   ->withCount('users')
                   ->find($id);
    }

    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }

    public function create(array $data): Role
    {
        return Role::create([
            'name'       => $data['name'],
            'guard_name' => 'web',
        ]);
    }

    public function update(Role $role, array $data): Role
    {
        // Role name is generally immutable — only update display label
        $role->update($data);
        return $role->fresh(['permissions']);
    }

    public function delete(Role $role): bool
    {
        return (bool) $role->delete();
    }

    public function syncPermissions(Role $role, array $permissions): Role
    {
        $role->syncPermissions($permissions);
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->forgetCachedPermissions();
        return $role->fresh(['permissions']);
    }

    public function getAllPermissions(): Collection
    {
        return Permission::orderBy('name')->get();
    }

    public function getPermissionsGrouped(): array
    {
        $allPermissions = $this->getAllPermissions();

        $grouped = [];
        foreach (PermEnum::grouped() as $group => $enumCases) {
            $groupPerms = [];
            foreach ($enumCases as $case) {
                $perm = $allPermissions->firstWhere('name', $case->value);
                if ($perm) {
                    $groupPerms[] = $perm;
                }
            }
            if (!empty($groupPerms)) {
                $grouped[$group] = $groupPerms;
            }
        }

        return $grouped;
    }

    public function CustomRoles(): Collection
    {
        return Role::where('name', '!=', UserRole::Owner->value)->get();
    }


}
