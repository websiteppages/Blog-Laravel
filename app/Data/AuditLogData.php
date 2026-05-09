<?php

namespace App\Data;

use App\Enums\ActorType;
use App\Enums\AuditAction;
use Illuminate\Database\Eloquent\Model;

/**
 * Data Transfer Object for creating an audit log entry.
 *
 * Why a DTO instead of a plain array?
 *
 * 1. Type safety — callers can't forget a required field or mistype a key.
 * 2. IDE support — autocomplete works on properties, not string array keys.
 * 3. Single definition — the shape is defined once; controller, service, and
 *    repository all reference the same class rather than agreeing on an
 *    implicit array contract.
 * 4. Testability — you can assert on a typed object rather than array keys.
 *
 * Design: readonly properties enforced immutability. Once a log entry is
 * created it should never change — this is reflected at the DTO level too.
 */
readonly class AuditLogData
{
    public function __construct(
        public int          $workspaceId,
        public AuditAction  $action,
        public string       $event,
        public ?int         $userId      = null,
        public ActorType    $actorType   = ActorType::User,
        public ?int         $actorId     = null,
        public ?string      $modelType   = null,
        public ?int         $modelId     = null,
        public ?array       $before      = null,
        public ?array       $after       = null,
        public ?string      $ipAddress   = null,
        public ?string      $userAgent   = null,
        public ?string      $url         = null,
        public ?array       $context     = null,
    ) {}

    /**
     * Convenience constructor that fills request metadata automatically.
     */
    public static function fromRequest(
        int         $workspaceId,
        AuditAction $action,
        string      $event,
        ?int        $userId    = null,
        ActorType   $actorType = ActorType::User,
        ?Model      $model     = null,
        ?array      $before    = null,
        ?array      $after     = null,
        ?array      $context   = null,
    ): self {
        return new self(
            workspaceId: $workspaceId,
            action:      $action,
            event:       $event,
            userId:      $userId,
            actorType:   $actorType,
            actorId:     $userId,
            modelType:   $model ? get_class($model) : null,
            modelId:     $model?->getKey(),
            before:      $before,
            after:       $after,
            ipAddress:   request()->ip(),
            userAgent:   request()->userAgent(),
            url:         request()->fullUrl(),
            context:     $context,
        );
    }

    /**
     * Convert to array for Eloquent create().
     */
    public function toArray(): array
    {
        return [
            'workspace_id' => $this->workspaceId,
            'user_id'      => $this->userId,
            'actor_type'   => $this->actorType,
            'actor_id'     => $this->actorId,
            'action'       => $this->action->value,
            'event'        => $this->event,
            'model_type'   => $this->modelType,
            'model_id'     => $this->modelId,
            'before'       => $this->before,
            'after'        => $this->after,
            'ip_address'   => $this->ipAddress,
            'user_agent'   => $this->userAgent,
            'url'          => $this->url,
            'context'      => $this->context,
        ];
    }
}
