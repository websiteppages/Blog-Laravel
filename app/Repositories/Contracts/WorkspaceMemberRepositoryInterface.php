<?php

namespace App\Repositories\Contracts;

use App\Models\WorkspaceMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface WorkspaceMemberRepositoryInterface
{
    public function paginateByWorkspace(
    int $workspaceId,
    int $perPage = 10
): LengthAwarePaginator;
}
