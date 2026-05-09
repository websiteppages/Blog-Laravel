<?php

namespace App\Repositories\Eloquent;

use App\Models\Workspace;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkspaceRepository implements WorkspaceRepositoryInterface
{
    public function findById(int $id): ?Workspace
    {
        return Workspace::find($id);
    }

    public function findBySlug(string $slug): ?Workspace
    {
        return Workspace::where('slug', $slug)->first();
    }

    public function create(array $data): Workspace
    {
        return Workspace::create($data);
    }

    public function update(Workspace $workspace, array $data): bool
    {
        return $workspace->update($data);
    }

    public function delete(Workspace $workspace): bool
    {
        return $workspace->delete();
    }

    public function forUser(int $userId): LengthAwarePaginator
    {
        return Workspace::where('owner_id', $userId)
            ->orWhereHas('workspaceMembers', function ($q) use ($userId) {
                $q->where('user_id', $userId)->where('status', 'active');
            })
            ->paginate(15);
    }
}
