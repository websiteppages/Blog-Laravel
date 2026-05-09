<?php

namespace App\Http\Controllers\Customer\Workspace;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invite\InviteMemberRequest;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvite;
use App\Repositories\Contracts\WorkspaceInviteRepositoryInterface;
use App\Repositories\Contracts\WorkspaceMemberRepositoryInterface;
use App\Services\Customer\InviteService;
use App\Services\Customer\WorkspaceService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(
        private InviteService $inviteService,
        private WorkspaceService $workspaceService,
        private WorkspaceMemberRepositoryInterface $memberRepository,
        private WorkspaceInviteRepositoryInterface $inviteRepository,
    ) {}

    public function index(Workspace $workspace)
    {
        $this->authorize('manageMembers', $workspace);

        $members = $this->memberRepository->paginateByWorkspace($workspace->id);

        $invites = $this->inviteRepository->paginateByWorkspace($workspace->id);

        $roles = $workspace->roles()->get();

        return view('customer.workspace.members',
            compact('workspace', 'members', 'invites', 'roles')
        );
    }

    public function invite(InviteMemberRequest $request, Workspace $workspace) {

        $role = $workspace->roles()->findOrFail($request->workspace_role_id);

        $invite = $this->inviteService->invite($workspace, $request->email, $role, $request->user());

        return redirect()->route('customer.workspaces.members', $workspace)
            ->with('success', "Invitation sent to {$invite->email}.");
    }

    public function acceptInvite(Request $request, string $token) {

        $invite = $this->inviteRepository->findByToken($token);

        abort_if(! $invite, 404);

        if (! $invite->isPending()) {
            return redirect()->route('dashboard')
                ->with('error', 'This invitation is no longer valid.');
        }

        if (! $request->user()) {
            session([
                'invite_token' => $token,
            ]);

            return redirect()->route('register')
                ->with('info', "Create an account or log in to accept the invitation to \"{$invite->workspace->name}\".");
        }

        $this->inviteService->accept($invite, $request->user());

        return redirect()->route('customer.workspaces.index')
            ->with('success', "Welcome to \"{$invite->workspace->name}\"!");
    }

    public function remove(Request $request, Workspace $workspace, User $user) {

        $this->authorize('manageMembers', $workspace);

        $this->workspaceService->removeMember($workspace, $user, $request->user());

        return redirect()->route('customer.workspaces.members', $workspace)
            ->with('success', 'Member removed.');
    }

    public function changeRole(Request $request, Workspace $workspace, User $user) {

        $this->authorize('manageMembers', $workspace);

        $role = $workspace->roles()->findOrFail($request->workspace_role_id);

        $this->workspaceService->changeRole($workspace, $user, $role, $request->user());

        return redirect()
            ->route('customer.workspaces.members', $workspace )
            ->with('success', 'Role updated.');
    }

    public function revokeInvite(Request $request, Workspace $workspace, WorkspaceInvite $invite) {

        $this->authorize('manageMembers', $workspace);

        $this->inviteService->revoke($invite, $request->user());

        return redirect()->route('customer.workspaces.members', $workspace)
            ->with('success', 'Invite revoked.');
    }
}
