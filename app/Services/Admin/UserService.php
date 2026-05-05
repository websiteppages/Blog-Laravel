<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Enums\UserRole;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;


class UserService
{
    use UploadTrait;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected RoleRepositoryInterface $roleRepo
    ) {}


    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->paginate($filters, 15);
    }

    public function getAllRoles(): Collection
    {
        return $this->roleRepo->all();
    }

    public function findById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function getCreateData(): array
    {
        return [
            'customRoles' => $this->roleRepo->CustomRoles(),
        ];
    }

    public function createFromRequest(Request $request): User
    {
        $data = [
            'name'              => $request->input('name'),
            'email'             => $request->input('email'),
            'password'          => Hash::make($request->input('password')),
            'bio'               => $request->input('bio'),
            'email_verified_at' => now(),
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploadFile(
                $request->file('avatar'),
                'avatars'
            );
        }

        return $this->userRepository->create($data);
    }

    public function getEditData(User $user): array
    {
        return [
            'user' => $user,
            'customRoles' => $this->roleRepo->CustomRoles(),
        ];
    }

    public function updateFromRequest(Request $request, User $user): User
    {
        $data = [
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'bio'   => $request->input('bio'),
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->replaceFile(
                $request->file('avatar'),
                $user->avatar,
                'avatars'
            );
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        return $this->userRepository->update($user, $data);
    }

    public function delete(User $user): bool
    {
        if ($user->avatar) {
            $this->deleteFile($user->avatar);
        }
        return $this->userRepository->delete($user);
    }


    public function getUsersWithRoles()
    {
        return $this->userRepository->getUsersWithRoles();
    }

    public function removeUserRole(User $user, string $role): User
    {
        // 🔐 Owner protection
        if ($user->hasRole(UserRole::Owner->value)) {
            throw new \Exception('Owner role cannot be removed');
        }

        return $this->userRepository->removeRole($user, $role);
    }


}
