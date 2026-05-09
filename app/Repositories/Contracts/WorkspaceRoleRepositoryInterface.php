<?php

namespace App\Repositories\Contracts;

use App\Models\WorkspaceRole;

interface WorkspaceRoleRepositoryInterface
{
    public function create(array $data): WorkspaceRole;

    public function update(WorkspaceRole $role, array $data): bool;

    public function delete(WorkspaceRole $role): bool;
}
