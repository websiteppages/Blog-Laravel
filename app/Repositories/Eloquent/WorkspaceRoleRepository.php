<?php

namespace App\Repositories\Eloquent;

use App\Models\WorkspaceRole;
use App\Repositories\Contracts\WorkspaceRoleRepositoryInterface;

class WorkspaceRoleRepository implements WorkspaceRoleRepositoryInterface
{
    public function create(array $data): WorkspaceRole
    {
        return WorkspaceRole::create($data);
    }

    public function update(WorkspaceRole $role, array $data): bool
    {
        return $role->update($data);
    }

    public function delete(WorkspaceRole $role): bool
    {
        return $role->delete();
    }
}
