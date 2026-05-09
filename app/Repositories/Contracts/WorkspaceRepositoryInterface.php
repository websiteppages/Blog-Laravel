<?php

namespace App\Repositories\Contracts;

use App\Models\Workspace;
use Illuminate\Pagination\LengthAwarePaginator;

interface WorkspaceRepositoryInterface
{
    public function findById(int $id): ?Workspace;
    public function findBySlug(string $slug): ?Workspace;
    public function create(array $data): Workspace;
    public function update(Workspace $workspace, array $data): bool;
    public function delete(Workspace $workspace): bool;
    public function forUser(int $userId): LengthAwarePaginator;
}
