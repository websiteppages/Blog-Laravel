<?php

namespace App\Services\Customer;

use App\Models\Workspace;
use App\Models\WorkspaceSetting;

class WorkspaceSettingService
{
    /**
     * Default settings applied to every new workspace.
     *
     * Design: null values (e.g. max_users) represent "no limit / not configured".
     * These are stored as NULL in the database (column is nullable JSON).
     */
    private const DEFAULTS = [
        'audit_logs'            => true,
        'invites'               => true,
        'max_users'             => 2,   // null = unlimited
        'email_notifications'   => true,
        'notify_on_invite'      => true,
        'notify_on_role_change' => true,
    ];

    public function bootstrapDefaults(Workspace $workspace): void
    {
        foreach (self::DEFAULTS as $key => $value) {
            WorkspaceSetting::create([
                'workspace_id' => $workspace->id,
                'key'          => $key,
                // Store null directly — migration column is nullable
                'value'        => $value,
            ]);
        }
    }

    public function get(Workspace $workspace, string $key, mixed $default = null): mixed
    {
        $setting = WorkspaceSetting::where('workspace_id', $workspace->id)
            ->where('key', $key)
            ->first();

        // A stored null is a valid value (e.g. max_users = no limit)
        return $setting ? $setting->value : $default;
    }

    public function set(Workspace $workspace, string $key, mixed $value): WorkspaceSetting
    {
        return WorkspaceSetting::updateOrCreate(
            ['workspace_id' => $workspace->id, 'key' => $key],
            ['value' => $value]
        );
    }

    public function updateMany(Workspace $workspace, array $settings): void
    {
        foreach ($settings as $key => $value) {
            $this->set($workspace, $key, $value);
        }
    }

    public function all(Workspace $workspace): array
    {
        return WorkspaceSetting::where('workspace_id', $workspace->id)
            ->pluck('value', 'key')
            ->toArray();
    }
}
