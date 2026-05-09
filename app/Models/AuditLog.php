<?php

namespace App\Models;

use App\Enums\ActorType;
use App\Enums\AuditAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    public $timestamps = true;
    public const UPDATED_AT = null; // Audit logs are immutable — never updated

    protected $fillable = [
        'workspace_id', 'user_id', 'actor_type', 'actor_id',
        'action', 'event', 'model_type', 'model_id',
        'before', 'after', 'ip_address', 'user_agent', 'url', 'context',
    ];

    protected function casts(): array
    {
        return [
            'actor_type' => ActorType::class,
            'action'     => AuditAction::class,
            'before'     => 'array',
            'after'      => 'array',
            'context'    => 'array',
        ];
    }

    public function workspace(): BelongsTo { return $this->belongsTo(Workspace::class); }
    public function user(): BelongsTo      { return $this->belongsTo(User::class); }
    public function auditable(): MorphTo   { return $this->morphTo('model'); }

    public function scopeViews(Builder $query): Builder
    {
        return $query->where('action', AuditAction::View->value);
    }

    public function scopeForModel(Builder $query, string $modelType, int $modelId): Builder
    {
        return $query->where('model_type', $modelType)->where('model_id', $modelId);
    }
}
