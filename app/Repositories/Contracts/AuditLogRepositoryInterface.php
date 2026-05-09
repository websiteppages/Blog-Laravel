<?php

namespace App\Repositories\Contracts;

use App\Data\AuditLogData;
use App\Models\AuditLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AuditLogRepositoryInterface
{
    public function record(AuditLogData $data): AuditLog;
    public function listForWorkspace(int $workspaceId, array $filters = []): LengthAwarePaginator;
    public function viewsForModel(string $modelType, int $modelId, int $limit = 50): Collection;
    public function dailyViewCounts(int $workspaceId, int $days = 30): Collection;
    public function topViewedModels(int $workspaceId, string $modelType, int $limit = 10): Collection;

    // ── New analytics methods ─────────────────────────────────────────────────

    /** Total unique viewer count for a workspace in N days */
    public function uniqueViewerCount(int $workspaceId, int $days = 30): int;

    /** Views grouped by hour-of-day (0-23) for a heatmap */
    public function viewsByHour(int $workspaceId, int $days = 30): Collection;

    /** Views grouped by day-of-week (1=Mon … 7=Sun) */
    public function viewsByDayOfWeek(int $workspaceId, int $days = 30): Collection;

    /** Top referrer domains for view events */
    public function topReferrers(int $workspaceId, int $days = 30, int $limit = 10): Collection;

    /** Per-user view activity sorted by total views */
    public function topViewers(int $workspaceId, int $days = 30, int $limit = 10): Collection;

    /** Daily counts for the PREVIOUS period (for trend comparison) */
    public function dailyViewCountsPrevious(int $workspaceId, int $days = 30): Collection;

    /** Views broken down by content_type via post join */
    public function viewsByContentType(int $workspaceId, int $days = 30): Collection;
}
