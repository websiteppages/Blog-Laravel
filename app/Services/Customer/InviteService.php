<?php

namespace App\Services\Customer;

use App\Enums\InviteStatus;
use App\Enums\MemberStatus;
use App\Events\MemberInvited;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvite;
use App\Models\WorkspaceMember;
use App\Models\WorkspaceRole;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WorkspaceInviteRepositoryInterface;
use App\Services\Admin\AuditLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InviteService
{
    public function __construct(
        private WorkspaceInviteRepositoryInterface $inviteRepository,
        private UserRepositoryInterface $userRepository,
        private AuditLogService $auditLog,
    ) {}

    /**
     * Send workspace invite.
     */
    public function invite(Workspace $workspace, string $email, WorkspaceRole $role, User $inviter): WorkspaceInvite {

        /*
        |--------------------------------------------------------------------------
        | Invites Enabled
        |--------------------------------------------------------------------------
        */
        if (! $workspace->getSetting('invites', true)) {

            throw ValidationException::withMessages([
                'email' => 'Invites are disabled for this workspace.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Max Users Limit
        |--------------------------------------------------------------------------
        */
        $maxUsers = $workspace->getSetting('max_users');

        if ($maxUsers !== null) {

            $activeCount = $workspace->members()
                ->wherePivot(
                    'status',
                    MemberStatus::Active->value
                )
                ->count();

            if ($activeCount >= $maxUsers) {

                throw ValidationException::withMessages([
                    'email' => "Workspace has reached the maximum of {$maxUsers} members.",
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Pending Invite
        |--------------------------------------------------------------------------
        */
        $existingInvite = $this->inviteRepository->findPendingInvite($workspace->id, $email);

        if ($existingInvite) {
            throw ValidationException::withMessages([
                'email' => 'A pending invite already exists for this email.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create Invite
        |--------------------------------------------------------------------------
        */
        $invite = $this->inviteRepository->create([
            'workspace_id'      => $workspace->id,
            'email'             => $email,
            'workspace_role_id' => $role->id,
            'token'             => WorkspaceInvite::generateToken(),
            'status'            => InviteStatus::Pending,
            'expires_at'        => now()->addDays(7),
            'invited_by'        => $inviter->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */
        $this->auditLog->logCreated($workspace, $invite, $inviter);

        /*
        |--------------------------------------------------------------------------
        | Dispatch Event
        |--------------------------------------------------------------------------
        */
        MemberInvited::dispatch($invite, $workspace, $inviter);

        return $invite;
    }

    /**
     * Accept invite.
     */
    public function accept(
        WorkspaceInvite $invite,
        User $user
    ): WorkspaceMember {

        if (! $invite->isPending()) {

            throw ValidationException::withMessages([
                'token' => 'This invite is no longer valid.',
            ]);
        }

        if ($invite->email !== $user->email) {

            throw ValidationException::withMessages([
                'token' => 'This invite was not sent to your email address.',
            ]);
        }

        return DB::transaction(function () use ($invite, $user) {

            /*
            |--------------------------------------------------------------------------
            | Restore Existing Membership
            |--------------------------------------------------------------------------
            */
            $member = WorkspaceMember::withTrashed()
                ->where('workspace_id', $invite->workspace_id)
                ->where('user_id', $user->id)
                ->first();

            if ($member) {

                $member->restore();

                $member->update([
                    'workspace_role_id' => $invite->workspace_role_id,
                    'status'            => MemberStatus::Active,
                    'joined_at'         => now(),
                ]);

            } else {

                $member = WorkspaceMember::create([
                    'workspace_id'      => $invite->workspace_id,
                    'user_id'           => $user->id,
                    'workspace_role_id' => $invite->workspace_role_id,
                    'status'            => MemberStatus::Active,
                    'joined_at'         => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Update Invite Status
            |--------------------------------------------------------------------------
            */
            $this->inviteRepository->update(
                $invite,
                [
                    'status' => InviteStatus::Accepted,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Switch Current Workspace
            |--------------------------------------------------------------------------
            */
            $this->userRepository->update($user, [
                'current_workspace_id' => $invite->workspace_id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Audit Log
            |--------------------------------------------------------------------------
            */
            $this->auditLog->log(
                $invite->workspace,
                'update',
                'invite.accepted',
                $invite,
                [],
                ['user_id' => $user->id],
                $user
            );

            return $member;
        });
    }

    /**
     * Expire stale invites.
     */
    public function expireStale(): int
    {
        return $this->inviteRepository->expireStale();
    }

     /**
     * Revoke workspace invite.
     */
    public function revoke(WorkspaceInvite $invite, User $actor): void {

        $before = $invite->toArray();

        $this->inviteRepository->update(
            $invite,
            [
                'status' => InviteStatus::Expired,
            ]
        );

        $this->auditLog->logUpdated($invite->workspace, $invite->fresh(), $before, $actor);
    }
}
