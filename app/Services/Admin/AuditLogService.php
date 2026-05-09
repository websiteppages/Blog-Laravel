<?php

namespace App\Services\Admin;

use App\Data\AuditLogData;
use App\Enums\ActorType;
use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\WorkspaceSetting;
use App\Models\Workspace;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Central service for recording all audit log entries.
 *
 * Refactored: now delegates persistence to AuditLogRepositoryInterface
 * (not direct Eloquent calls) and uses AuditLogData DTOs (not raw arrays).
 */
class AuditLogService
{
    public function __construct(
        private AuditLogRepositoryInterface $repository,
    ) {}

    /**
     * Record any audit event from a fully-populated DTO.
     */
    public function record(AuditLogData $data): AuditLog
    {
        if (! $this->isEnabledForWorkspace($data->workspaceId)) {
            return new AuditLog();
        }

        return $this->repository->record($data);
    }

    /**
     * Fluent shorthand — builds a DTO from loose parameters.
     * Kept for backward compatibility with all existing callers.
     */
    public function log(
        Workspace  $workspace,
        string     $action,
        string     $event,
        ?Model     $model     = null,
        array      $before    = [],
        array      $after     = [],
        ?User      $user      = null,
        ActorType  $actorType = ActorType::User,
        ?array     $context   = null,
    ): AuditLog {
        $user ??= Auth::user();
        $actionEnum = AuditAction::tryFrom($action) ?? AuditAction::Create;

        $data = AuditLogData::fromRequest(
            workspaceId: $workspace->id,
            action:      $actionEnum,
            event:       $event,
            userId:      $user?->id,
            actorType:   $actorType,
            model:       $model,
            before:      $before ?: null,
            after:       $after ?: null,
            context:     $context,
        );

        return $this->record($data);
    }

    public function logCreated(Workspace $workspace, Model $model, ?User $user = null): AuditLog
    {
        return $this->log($workspace, 'create', $this->eventName($model, 'created'),
            $model, [], $model->toArray(), $user);
    }

    public function logUpdated(Workspace $workspace, Model $model, array $original, ?User $user = null): AuditLog
    {
        return $this->log($workspace, 'update', $this->eventName($model, 'updated'),
            $model, $original, $model->toArray(), $user);
    }

    public function logDeleted(Workspace $workspace, Model $model, ?User $user = null): AuditLog
    {
        return $this->log($workspace, 'delete', $this->eventName($model, 'deleted'),
            $model, $model->toArray(), [], $user);
    }

    private function isEnabledForWorkspace(int $workspaceId): bool
    {
        // ->value() bypasses Eloquent casts and returns the raw MySQL JSON string.
        // json_decode() converts 'true' -> true, 'false' -> false, '1' -> 1, null -> null.
        // We cast to bool so null (setting not configured) defaults to enabled (true).
        $raw = WorkspaceSetting::where('workspace_id', $workspaceId)
            ->where('key', 'audit_logs')
            ->value('value');

        if ($raw === null) {
            return true; // not configured = enabled by default
        }

        return (bool) json_decode($raw);
    }

    private function eventName(Model $model, string $verb): string
    {
        return strtolower(class_basename($model)) . '.' . $verb;
    }
}
