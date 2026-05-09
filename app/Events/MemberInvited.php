<?php

namespace App\Events;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvite;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberInvited
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly WorkspaceInvite $invite,
        public readonly Workspace $workspace,
        public readonly User $inviter,
    ) {}
}
