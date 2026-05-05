<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Enums\UserRole;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    public function paginate(
        array $filters = [],
        int   $perPage = 15
    ): LengthAwarePaginator {
        $query = User::withCount(['posts', 'publishedPosts'])->latest();

        if (!empty($filters['search'])) {
            $term = $filters['search'];
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%");
            });
        }

        if (!empty($filters['role'])) {
            $query->whereHas('roles',
                fn($q) => $q->where('name', $filters['role']));
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?User
    {
        return User::withCount(['posts', 'publishedPosts'])->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    public function getTopAuthors(int $limit = 8): Collection
    {
        return User::withCount('publishedPosts')
                   ->withSum('posts', 'views_count')
                   ->withSum('posts', 'likes_count')
                   ->orderByDesc('published_posts_count')
                   ->limit($limit)
                   ->get();
    }
    public function getUsersWithRoles(): Collection
    {
        return User::with('roles')
        ->whereHas('roles')
        ->whereDoesntHave('roles', function ($q) {
            $q->where('name', UserRole::Owner->value);
        })
        ->get();
    }

    public function removeRole(User $user, string $role): User
    {
        $user->removeRole($role); // Spatie method

        return $user->fresh(); // updated user
    }

}
