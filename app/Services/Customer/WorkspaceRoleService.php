<?php

namespace App\Services\Customer;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceRole;
use App\Repositories\Contracts\WorkspaceRoleRepositoryInterface;
use App\Services\Admin\AuditLogService;
use App\Services\Common\SlugService;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WorkspaceRoleService
{
    public function __construct(
        private WorkspaceRoleRepositoryInterface $repository,
        private AuditLogService $auditLog,
        private SlugService $slugService
    ) {}

    /**
     * Create a new workspace role.
     */
    public function create(Workspace $workspace, array $data, User $creator): WorkspaceRole {

        $data['workspace_id'] = $workspace->id;
        $data['created_by']   = $creator->id;
        $data['slug'] = $this->slugService->generate(
            $data['name'],
            WorkspaceRole::class,[
                'workspace_id' => $workspace->id,
            ]
        );

        $role = $this->repository->create($data);

        $this->auditLog->logCreated($workspace, $role, $creator);

        return $role;
    }

    /**
     * Update an existing role.
     */
    public function update(WorkspaceRole $role, array $data, User $actor): WorkspaceRole {

        // Prevent system role name/slug modification
        if ($role->is_system) {
            $data = array_intersect_key($data, [
                'permissions' => true,
            ]);
        }

        $original = $role->toArray();

        $this->repository->update($role, $data);

        $updatedRole = $role->fresh();

        $this->auditLog->logUpdated($role->workspace, $updatedRole, $original, $actor);

        return $updatedRole;
    }

    /**
     * Delete a role.
     */
    public function delete(WorkspaceRole $role, User $actor): void {

        if ($role->is_system) {
            throw ValidationException::withMessages([
                'role' => 'System roles cannot be deleted.',
            ]);
        }

        if ($role->members()->exists()) {
            throw ValidationException::withMessages([
                'role' => 'Cannot delete a role with assigned members.',
            ]);
        }

        $this->auditLog->logDeleted($role->workspace, $role, $actor);

        $this->repository->delete($role);
    }
}
