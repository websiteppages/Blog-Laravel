<?php

namespace App\Repositories\Eloquent;

use App\Enums\InviteStatus;
use App\Models\WorkspaceInvite;
use App\Repositories\Contracts\WorkspaceInviteRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class WorkspaceInviteRepository implements WorkspaceInviteRepositoryInterface
{
    public function create(array $data): WorkspaceInvite
    {
        return WorkspaceInvite::create($data);
    }

    public function findPendingInvite(int $workspaceId, string $email): ?WorkspaceInvite {

        return WorkspaceInvite::where('workspace_id', $workspaceId)
            ->where('email', $email)
            ->where('status', InviteStatus::Pending)
            ->first();
    }

    public function update(WorkspaceInvite $invite, array $data): WorkspaceInvite {
        $invite->update($data);
        return $invite->fresh();
    }

    public function expireStale(): int
    {
        return WorkspaceInvite::where('status', InviteStatus::Pending)
            ->where('expires_at', '<', now())
            ->update([
                'status' => InviteStatus::Expired,
            ]);
    }

    public function paginateByWorkspace(int $workspaceId, int $perPage = 10): LengthAwarePaginator {
        return WorkspaceInvite::where('workspace_id', $workspaceId)
            ->with(['role', 'inviter',])
            ->latest()
            ->paginate($perPage);
    }

    public function findByToken(string $token): ?WorkspaceInvite {
        return WorkspaceInvite::where('token', $token)->first();
    }


}
