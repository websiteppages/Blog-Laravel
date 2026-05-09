<?php

namespace App\Repositories\Eloquent;

use App\Data\AuditLogData;
use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    public function record(AuditLogData $data): AuditLog
    {
        return AuditLog::create($data->toArray());
    }

    public function listForWorkspace(int $workspaceId, array $filters = []): LengthAwarePaginator
    {
        $query = AuditLog::where('workspace_id', $workspaceId)
            ->with('user')->orderByDesc('created_at');

        if (! empty($filters['action']))     $query->where('action', $filters['action']);
        if (! empty($filters['user_id']))    $query->where('user_id', $filters['user_id']);
        if (! empty($filters['model_type'])) $query->where('model_type', $filters['model_type']);
        if (! empty($filters['event']))      $query->where('event', 'like', '%'.$filters['event'].'%');
        if (! empty($filters['date_from']))  $query->whereDate('created_at', '>=', $filters['date_from']);
        if (! empty($filters['date_to']))    $query->whereDate('created_at', '<=', $filters['date_to']);

        return $query->paginate(25)->withQueryString();
    }

    public function viewsForModel(string $modelType, int $modelId, int $limit = 50): Collection
    {
        return AuditLog::where('action', AuditAction::View->value)
            ->where('model_type', $modelType)->where('model_id', $modelId)
            ->with('user')->orderByDesc('created_at')->limit($limit)->get();
    }

    public function dailyViewCounts(int $workspaceId, int $days = 30): Collection
    {
        return $this->viewsBase($workspaceId, $days)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')->orderBy('date')->get();
    }

    public function dailyViewCountsPrevious(int $workspaceId, int $days = 30): Collection
    {
        return AuditLog::where('workspace_id', $workspaceId)
            ->where('action', AuditAction::View->value)
            ->whereBetween('created_at', [now()->subDays($days * 2), now()->subDays($days)])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')->orderBy('date')->get();
    }

    public function topViewedModels(int $workspaceId, string $modelType, int $limit = 10): Collection
    {
        return $this->viewsBase($workspaceId)
            ->where('model_type', $modelType)->whereNotNull('model_id')
            ->select('model_id', DB::raw('COUNT(*) as view_count'))
            ->groupBy('model_id')->orderByDesc('view_count')->limit($limit)->get();
    }

    public function uniqueViewerCount(int $workspaceId, int $days = 30): int
    {
        return $this->viewsBase($workspaceId, $days)
            ->whereNotNull('user_id')
            ->distinct('user_id')->count('user_id');
    }

    public function viewsByHour(int $workspaceId, int $days = 30): Collection
    {
        return $this->viewsBase($workspaceId, $days)
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')->orderBy('hour')->get();
    }

    public function viewsByDayOfWeek(int $workspaceId, int $days = 30): Collection
    {
        // DAYOFWEEK: 1=Sun…7=Sat. We remap to Mon=1…Sun=7 in the service/view.
        return $this->viewsBase($workspaceId, $days)
            ->select(DB::raw('DAYOFWEEK(created_at) as dow'), DB::raw('COUNT(*) as count'))
            ->groupBy('dow')->orderBy('dow')->get();
    }

    public function topReferrers(int $workspaceId, int $days = 30, int $limit = 10): Collection
    {
        // Extract host from JSON context->referrer stored in the context column
        return $this->viewsBase($workspaceId, $days)
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(context, '$.referrer')) IS NOT NULL")
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(context, '$.referrer')) != 'null'")
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(context, '$.referrer')) != ''")
            ->select(
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(context, '$.referrer')) as referrer"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('referrer')->orderByDesc('count')->limit($limit)->get();
    }

    public function topViewers(int $workspaceId, int $days = 30, int $limit = 10): Collection
    {
        return $this->viewsBase($workspaceId, $days)
            ->whereNotNull('user_id')
            ->select('user_id', DB::raw('COUNT(*) as view_count'), DB::raw('MAX(created_at) as last_seen'))
            ->groupBy('user_id')->orderByDesc('view_count')->limit($limit)
            ->with('user')->get();
    }

    public function viewsByContentType(int $workspaceId, int $days = 30): Collection
    {
        return $this->viewsBase($workspaceId, $days)
            ->join('posts', function ($join) {
                $join->on('audit_logs.model_id', '=', 'posts.id')
                     ->where('audit_logs.model_type', '=', 'App\Models\Post');
            })
            ->select('posts.content_type', DB::raw('COUNT(audit_logs.id) as count'))
            ->groupBy('posts.content_type')->orderByDesc('count')->get();
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function viewsBase(int $workspaceId, ?int $days = null)
    {
        // Qualify ALL columns with the table name so any subsequent join()
        // (e.g. viewsByContentType joining posts) does not cause ambiguous
        // column errors — both audit_logs and posts share workspace_id,
        // action, created_at column names.
        $q = AuditLog::where('audit_logs.workspace_id', $workspaceId)
            ->where('audit_logs.action', AuditAction::View->value);
        if ($days) {
            $q->where('audit_logs.created_at', '>=', now()->subDays($days));
        }
        return $q;
    }

    /** @deprecated */
    public function create(array $data): AuditLog { return AuditLog::create($data); }
    /** @deprecated */
    public function forWorkspace(int $workspaceId, array $filters = []): LengthAwarePaginator
    { return $this->listForWorkspace($workspaceId, $filters); }
}
