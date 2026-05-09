<?php

namespace App\Repositories\Contracts;

use App\Models\WorkspaceInvite;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WorkspaceInviteRepositoryInterface
{
    public function create(array $data): WorkspaceInvite;

    public function findPendingInvite(int $workspaceId, string $email): ?WorkspaceInvite;

    public function update(WorkspaceInvite $invite, array $data): WorkspaceInvite;

    public function expireStale(): int;

    public function paginateByWorkspace(int $workspaceId, int $perPage = 10): LengthAwarePaginator;

    public function findByToken(string $token): ?WorkspaceInvite;


}
