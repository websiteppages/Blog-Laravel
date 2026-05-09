<?php

namespace App\Mail;

use App\Models\Workspace;
use App\Models\WorkspaceInvite;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkspaceInviteMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public WorkspaceInvite $invite,
        public Workspace $workspace,
        public string $acceptUrl,
    ) {}

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject(
                "You're invited to join \"{$this->workspace->name}\""
            )
            ->view('emails.workspace-invite');
    }
}
