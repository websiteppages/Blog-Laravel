<?php

namespace App\Listeners;

use App\Events\MemberInvited;
use App\Events\PostPublished;
use App\Services\Admin\AuditLogService;
use Illuminate\Events\Dispatcher;

/**
 * Event subscriber that records domain events in the audit log.
 *
 * Design: Using the subscriber pattern groups all audit-logging logic in one
 * class rather than spreading individual listeners across many files.
 * The subscribe() method explicitly maps events to handler methods.
 */
class LogModelAction
{
    public function __construct(private AuditLogService $auditLogService) {}

    public function handlePostPublished(PostPublished $event): void
    {
        $this->auditLogService->log(
            workspace: $event->post->workspace,
            action:    'publish',
            event:     'post.published',
            model:     $event->post,
            after:     ['status' => 'published', 'published_at' => now()],
            user:      $event->publisher,
        );
    }

    public function handleMemberInvited(MemberInvited $event): void
    {
        $this->auditLogService->log(
            workspace: $event->workspace,
            action:    'create',
            event:     'member.invited',
            model:     $event->invite,
            after:     ['email' => $event->invite->email],
            user:      $event->inviter,
        );
    }

    /**
     * Register the listeners for the subscriber.
     * Laravel calls this method to wire events to handler methods.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            PostPublished::class => 'handlePostPublished',
            MemberInvited::class => 'handleMemberInvited',
        ];
    }
}
