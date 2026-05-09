<?php

namespace App\Policies;

use App\Enums\WorkspacePermission;
use App\Models\User;
use App\Models\Workspace;

class WorkspacePolicy
{
    public function view(User $user, Workspace $workspace): bool
    {
        return $user->hasPermission(WorkspacePermission::WorkspaceView->value, $workspace);
    }

    public function update(User $user, Workspace $workspace): bool
    {
        return $user->hasPermission(WorkspacePermission::WorkspaceUpdate->value, $workspace);
    }

    public function delete(User $user, Workspace $workspace): bool
    {
        // Only owner can delete
        return $workspace->owner_id === $user->id;
    }

    public function manageMembers(User $user, Workspace $workspace): bool
    {
        return $user->hasPermission(WorkspacePermission::MembersInvite->value, $workspace);
    }

    public function manageRoles(User $user, Workspace $workspace): bool
    {
        return $user->hasPermission(WorkspacePermission::RolesCreate->value, $workspace);
    }

    public function viewSettings(User $user, Workspace $workspace): bool
    {
        return $user->hasPermission(WorkspacePermission::SettingsView->value, $workspace);
    }

    public function updateSettings(User $user, Workspace $workspace): bool
    {
        return $user->hasPermission(WorkspacePermission::SettingsUpdate->value, $workspace);
    }

    public function viewAuditLogs(User $user, Workspace $workspace): bool
    {
        return $user->hasPermission(WorkspacePermission::AuditLogsView->value, $workspace);
    }
}
