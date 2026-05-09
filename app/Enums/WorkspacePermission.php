<?php

namespace App\Enums;

/**
 * All available permission strings.
 * These are stored as JSON arrays in workspace_roles.permissions.
 * Design decision: string-based permissions allow flexible UI without schema changes.
 */
enum WorkspacePermission: string
{
    // Workspace management
    case WorkspaceView = 'workspace.view';
    case WorkspaceUpdate = 'workspace.update';
    case WorkspaceDelete = 'workspace.delete';

    // Member management
    case MembersView = 'members.view';
    case MembersInvite = 'members.invite';
    case MembersRemove = 'members.remove';
    case MembersSuspend = 'members.suspend';

    // Role management
    case RolesView = 'roles.view';
    case RolesCreate = 'roles.create';
    case RolesUpdate = 'roles.update';
    case RolesDelete = 'roles.delete';
    case RolesAssign = 'roles.assign';

    // Post management
    case PostsView = 'posts.view';
    case PostsCreate = 'posts.create';
    case PostsUpdate = 'posts.update';
    case PostsDelete = 'posts.delete';
    case PostsPublish = 'posts.publish';

    // Settings management
    case SettingsView = 'settings.view';
    case SettingsUpdate = 'settings.update';

    // Audit logs
    case AuditLogsView = 'audit_logs.view';

    /**
     * Returns all permissions for the admin system role.
     */
    public static function allPermissions(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Returns default permissions for the 'member' system role.
     */
    public static function memberPermissions(): array
    {
        return [
            self::WorkspaceView->value,
            self::MembersView->value,
            self::PostsView->value,
            self::PostsCreate->value,
            self::PostsUpdate->value,
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::WorkspaceView => 'View Workspace',
            self::WorkspaceUpdate => 'Update Workspace',
            self::WorkspaceDelete => 'Delete Workspace',
            self::MembersView => 'View Members',
            self::MembersInvite => 'Invite Members',
            self::MembersRemove => 'Remove Members',
            self::MembersSuspend => 'Suspend Members',
            self::RolesView => 'View Roles',
            self::RolesCreate => 'Create Roles',
            self::RolesUpdate => 'Update Roles',
            self::RolesDelete => 'Delete Roles',
            self::RolesAssign => 'Assign Roles',
            self::PostsView => 'View Posts',
            self::PostsCreate => 'Create Posts',
            self::PostsUpdate => 'Update Posts',
            self::PostsDelete => 'Delete Posts',
            self::PostsPublish => 'Publish Posts',
            self::SettingsView => 'View Settings',
            self::SettingsUpdate => 'Update Settings',
            self::AuditLogsView => 'View Audit Logs',
        };
    }

    public function group(): string
    {
        return explode('.', $this->value)[0];
    }

    /**
     * Get label from value.
     */
    public static function labelFromValue(string $value): ?string
    {
        return self::tryFrom($value)?->label();
    }
}
