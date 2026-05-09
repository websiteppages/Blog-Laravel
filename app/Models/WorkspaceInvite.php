<?php

namespace App\Models;

use App\Enums\InviteStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class WorkspaceInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'email',
        'workspace_role_id',
        'token',
        'status',
        'expires_at',
        'invited_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => InviteStatus::class,
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Route model binding by token instead of id.
     */
    public function getRouteKeyName(): string
    {
        return 'token';
    }

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(WorkspaceRole::class, 'workspace_role_id');
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public function isExpired(): bool
    {
        return $this->expires_at->isPast() || $this->status === InviteStatus::Expired;
    }

    public function isPending(): bool
    {
        return $this->status === InviteStatus::Pending && ! $this->isExpired();
    }

    /**
     * Generate a cryptographically secure token.
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    public function scopePending($query)
    {
        return $query->where('status', InviteStatus::Pending)
            ->where('expires_at', '>', now());
    }


}
