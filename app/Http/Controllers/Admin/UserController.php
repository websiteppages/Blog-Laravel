<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\RoleRepositoryInterface;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        return view('admin.users.index', [
            'users' => $this->userService->getPaginated(
                $request->only(['search', 'role'])
            ),
            'allRoles' => $this->userService->getAllRoles(),
        ]);
    }

    public function create()
    {
        $data = $this->userService->getCreateData();
        return view('admin.users.create', $data);
    }

    public function store(UserRequest $request)
    {
        $user = $this->userService->createFromRequest($request);

        if ($request->filled('role')) {
            $user->syncRoles([$request->input('role')]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" created.");
    }

    public function show(User $user)
    {
        $user->loadCount(['posts', 'publishedPosts']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $data = $this->userService->getEditData($user);
        return view('admin.users.edit', $data);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $this->userService->updateFromRequest($request, $user);

        if ($request->filled('role')
            && Auth::user()->hasPermissionTo('assign roles')
            && !$user->hasRole('owner')) {
            $user->syncRoles([$request->input('role')]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" updated.");
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        $this->userService->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted.');
    }

    public function removeRole(User $user)
    {
        $this->authorize('removeUserRole', $user);

        $role = request('role');

        $this->userService->removeUserRole($user, $role);

        return back()->with('success', 'Role removed successfully');
    }

}
