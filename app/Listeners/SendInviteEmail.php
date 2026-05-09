<?php

namespace App\Listeners;

use App\Events\MemberInvited;
use App\Mail\WorkspaceInviteMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

/**
 * Sends workspace invitation email.
 *
 * Architecture:
 * - Event Driven
 * - Queueable
 * - Clean Separation
 * - Future scalable
 */
class SendInviteEmail implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(
        MemberInvited $event
    ): void {

        $invite    = $event->invite;
        $workspace = $event->workspace;

        /*
        |--------------------------------------------------------------------------
        | Workspace Notification Settings
        |--------------------------------------------------------------------------
        */
        $emailEnabled = $workspace->getSetting(
            'email_notifications',
            true
        );

        $notifyInvite = $workspace->getSetting(
            'notify_on_invite',
            true
        );

        if (! $emailEnabled || ! $notifyInvite) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Accept URL
        |--------------------------------------------------------------------------
        */
        $acceptUrl = route(
            'customer.invites.accept',
            [
                'token' => $invite->token,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Send Mail
        |--------------------------------------------------------------------------
        */
        Mail::to($invite->email)
            ->send(
                new WorkspaceInviteMail(
                    invite: $invite,
                    workspace: $workspace,
                    acceptUrl: $acceptUrl
                )
            );
    }
}
