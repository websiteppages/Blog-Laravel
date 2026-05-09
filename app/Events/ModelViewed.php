<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired when any auditable model is viewed by a user.
 *
 * Design: a generic event rather than PostViewed, WorkspaceViewed, etc.
 * The $model property carries the identity of the viewed object polymorphically,
 * so the same listener handles all model types without modification.
 *
 * Usage:
 *   ModelViewed::dispatch($post, $request->user(), ['referrer' => $request->header('Referer')]);
 */
class ModelViewed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Model  $model,
        public readonly ?User  $viewer,
        public readonly array  $context = [],
    ) {}
}
