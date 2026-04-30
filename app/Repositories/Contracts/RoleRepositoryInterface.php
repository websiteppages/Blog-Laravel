<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

interface RoleRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 20): LengthAwarePaginator;
    public function findById(int $id): ?Role;
    public function findByName(string $name): ?Role;
    public function create(array $data): Role;
    public function update(Role $role, array $data): Role;
    public function delete(Role $role): bool;
    public function syncPermissions(Role $role, array $permissions): Role;
    public function getAllPermissions(): Collection;
    public function getPermissionsGrouped(): array;
}
