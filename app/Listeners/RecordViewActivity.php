<?php

namespace App\Listeners;

use App\Events\ModelViewed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Listener that persists view activity when a ModelViewed event fires.
 *
 * Why is this a listener rather than inline in the controller?
 *
 * 1. ShouldQueue — the DB write happens on a background queue worker,
 *    so view tracking adds zero latency to the HTTP response.
 * 2. Decoupling — the controller just fires ModelViewed::dispatch().
 *    It doesn't know or care how (or whether) the view is persisted.
 * 3. Extensibility — add a second listener (e.g. WebSocket broadcast,
 *    Mixpanel event) without touching the controller or service.
 *
 * If you don't run a queue worker, set QUEUE_CONNECTION=sync in .env
 * and the listener runs synchronously inline.
 */
class RecordViewActivity implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The queue to run this listener on.
     * Use a dedicated low-priority queue so view writes
     * don't delay higher-priority jobs.
     */
    public string $queue = 'tracking';

    /**
     * If the job fails (e.g. DB is down), retry up to 3 times.
     */
    public int $tries = 3;

    /**
     * Don't re-queue failed view events after backoff — they're not critical.
     */
    public function failed(ModelViewed $event, \Throwable $e): void
    {
        // Silently discard — view tracking failure should never surface to users
        logger()->warning('View tracking job failed', [
            'model'   => get_class($event->model),
            'id'      => $event->model->getKey(),
            'error'   => $e->getMessage(),
        ]);
    }

    /**
     * Handle the event.
     *
     * Note: ViewTrackingService is NOT injected here to keep the listener thin.
     * The listener's only job is to call the service. Business logic (workspace
     * checks, DTO building) stays in the service — not here.
     */
    public function handle(ModelViewed $event): void
    {
        // The model must belong to a workspace for us to know the audit target
        if (! method_exists($event->model, 'workspace') || ! $event->model->workspace) {
            return;
        }

        app(\App\Services\ViewTrackingService::class)->recordView(
            model:     $event->model,
            workspace: $event->model->workspace,
            viewer:    $event->viewer,
            context:   $event->context,
        );
    }
}
