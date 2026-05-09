<?php

namespace App\Services\Admin;

use App\Data\AuditLogData;
use App\Enums\ActorType;
use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Workspace;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ViewTrackingService
{
    public function __construct(private AuditLogRepositoryInterface $repository) {}

    public function recordView(Model $model, Workspace $workspace, ?User $viewer = null, array $context = []): ?AuditLog
    {
        if (! $workspace->getSetting('audit_logs', true)) return null;

        $viewer ??= Auth::user();

        return $this->repository->record(AuditLogData::fromRequest(
            workspaceId: $workspace->id,
            action:      AuditAction::View,
            event:       $this->eventName($model),
            userId:      $viewer?->id,
            actorType:   $viewer ? ActorType::User : ActorType::System,
            model:       $model,
            context:     $context ?: null,
        ));
    }

    public function viewsForModel(Model $model, int $limit = 50): Collection
    {
        return $this->repository->viewsForModel(get_class($model), $model->getKey(), $limit);
    }

    public function dailyViewCounts(Workspace $workspace, int $days = 30): Collection
    {
        return $this->repository->dailyViewCounts($workspace->id, $days);
    }

    public function topViewedModels(Workspace $workspace, string $modelClass, int $limit = 10): Collection
    {
        return $this->repository->topViewedModels($workspace->id, $modelClass, $limit);
    }

    /** Full analytics payload for the dashboard — assembled here so the controller stays thin */
    public function analyticsPayload(Workspace $workspace, int $days): array
    {
        $daily        = $this->repository->dailyViewCounts($workspace->id, $days);
        $prevDaily    = $this->repository->dailyViewCountsPrevious($workspace->id, $days);
        $totalViews   = (int) $daily->sum('count');
        $prevTotal    = (int) $prevDaily->sum('count');

        return [
            'daily'           => $daily,
            'totalViews'      => $totalViews,
            'prevTotalViews'  => $prevTotal,
            'trendPct'        => $prevTotal > 0 ? round((($totalViews - $prevTotal) / $prevTotal) * 100) : null,
            'avgPerDay'       => $daily->count() ? round($totalViews / $daily->count(), 1) : 0,
            'peakDay'         => $daily->sortByDesc('count')->first(),
            'uniqueViewers'   => $this->repository->uniqueViewerCount($workspace->id, $days),
            'byHour'          => $this->repository->viewsByHour($workspace->id, $days),
            'byDow'           => $this->repository->viewsByDayOfWeek($workspace->id, $days),
            'topReferrers'    => $this->repository->topReferrers($workspace->id, $days),
            'topViewers'      => $this->repository->topViewers($workspace->id, $days),
            'byContentType'   => $this->repository->viewsByContentType($workspace->id, $days),
        ];
    }

    private function eventName(Model $model): string
    {
        return strtolower(class_basename($model)) . '.viewed';
    }
}
