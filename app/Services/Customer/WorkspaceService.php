<?php

namespace App\Services\Customer;

use App\Enums\MemberStatus;
use App\Enums\InviteStatus;
use App\Enums\WorkspacePermission;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvite;
use App\Models\WorkspaceMember;
use App\Models\WorkspaceRole;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Admin\AuditLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkspaceService
{
    public function __construct(
        private WorkspaceRepositoryInterface $workspaceRepo,
        private AuditLogService $auditLog,
        private WorkspaceSettingService $settingService,
        private UserRepositoryInterface $userRepository,
    ) {}

    /**
     * Create a new workspace and bootstrap it with system roles and owner membership.
     */
    public function create(User $owner, array $data): Workspace
    {
        return DB::transaction(function () use ($owner, $data) {
            $data['owner_id'] = $owner->id;
            $data['slug'] = Workspace::generateSlug($data['name']);

            $workspace = $this->workspaceRepo->create($data);

            // Bootstrap system roles: admin (all perms) and member (limited perms)
            // $adminRole = WorkspaceRole::create([
            //     'workspace_id' => $workspace->id,
            //     'name'         => 'Admin',
            //     'slug'         => 'admin',
            //     'permissions'  => WorkspacePermission::allPermissions(),
            //     'is_system'    => true,
            //     'created_by'   => $owner->id,
            // ]);

            // WorkspaceRole::create([
            //     'workspace_id' => $workspace->id,
            //     'name'         => 'Member',
            //     'slug'         => 'member',
            //     'permissions'  => WorkspacePermission::memberPermissions(),
            //     'is_system'    => true,
            //     'created_by'   => $owner->id,
            // ]);

            // Add owner as active admin member
            // WorkspaceMember::create([
            //     'workspace_id'      => $workspace->id,
            //     'user_id'           => $owner->id,
            //     'workspace_role_id' => $adminRole->id,
            //     'status'            => MemberStatus::Active,
            //     'joined_at'         => now(),
            // ]);

            // Bootstrap default workspace settings
            $this->settingService->bootstrapDefaults($workspace);

            // Switch user to new workspace
            $owner->update(['current_workspace_id' => $workspace->id]);

            $this->auditLog->logCreated($workspace, $workspace, $owner);

            return $workspace;
        });
    }

    public function update(Workspace $workspace, array $data, User $actor): bool
    {
        $original = $workspace->toArray();
        $result = $this->workspaceRepo->update($workspace, $data);
        $this->auditLog->logUpdated($workspace, $workspace->fresh(), $original, $actor);
        return $result;
    }

    public function delete(Workspace $workspace, User $actor): bool
    {
        $this->auditLog->logDeleted($workspace, $workspace, $actor);
        return $this->workspaceRepo->delete($workspace);
    }

    /**
     * Switch a user's active workspace.
     */

    public function switchWorkspace(User $user, Workspace $workspace): void {

        // Ensure user has access to workspace
        if (! $user->belongsToWorkspace($workspace) && $workspace->owner_id !== $user->id) {
            throw ValidationException::withMessages([
                'workspace' => 'You do not have access to this workspace.',
            ]);
        }

        // Switch active workspace
        $this->userRepository->update($user, [
            'current_workspace_id' => $workspace->id,
        ]);
    }

    /**
     * Remove a member from a workspace.
     */
    public function removeMember(Workspace $workspace, User $member, User $actor): void
    {
        $membership = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', $member->id)
            ->firstOrFail();

        $membership->delete();

        WorkspaceInvite::where('workspace_id', $workspace->id)
        ->where('email', $member->email)
        ->where('status', InviteStatus::Accepted)
        ->update([
            'status' => InviteStatus::Removed,
        ]);

         /*
        |--------------------------------------------------------------------------
        | Switch current workspace safely
        |--------------------------------------------------------------------------
        */

        if ($member->current_workspace_id === $workspace->id) {

            // 1. Prefer owned workspace
            $newWorkspaceId = Workspace::where('owner_id', $member->id)
                ->value('id');

            // 2. Otherwise use another active membership
            if (! $newWorkspaceId) {

                $newWorkspaceId = WorkspaceMember::where('user_id', $member->id)
                    ->where('workspace_id', '!=', $workspace->id)
                    ->where('status', MemberStatus::Active)
                    ->value('workspace_id');
            }

            // 3. Fallback to null
            $member->update([
                'current_workspace_id' => $newWorkspaceId,
            ]);
        }

        $this->auditLog->log($workspace, 'delete', 'member.removed', $membership, $membership->toArray(), [], $actor);
    }

    /**
     * Change a member's role.
     */
    public function changeRole(Workspace $workspace, User $member, WorkspaceRole $newRole, User $actor): void
    {
        $membership = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', $member->id)
            ->firstOrFail();

        $before = $membership->toArray();
        $membership->update(['workspace_role_id' => $newRole->id]);

        $this->auditLog->log($workspace, 'update', 'member.role_changed', $membership, $before, $membership->fresh()->toArray(), $actor);
    }
}
