<?php
namespace App\Repositories\Eloquent;

use App\Models\WorkspaceMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Repositories\Contracts\WorkspaceMemberRepositoryInterface;

class WorkspaceMemberRepository implements WorkspaceMemberRepositoryInterface
{
    public function paginateByWorkspace(int $workspaceId, int $perPage = 10): LengthAwarePaginator
    {
        return WorkspaceMember::where('workspace_id',$workspaceId)
            ->with(['user', 'role'])
            ->latest()
            ->paginate($perPage);
    }
}
