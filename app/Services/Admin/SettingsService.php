<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;
use App\Repositories\Contracts\SettingRepositoryInterface;

class SettingsService
{
    /**
     * Application settings cache key.
     */
    private const CACHE_KEY = 'app_settings';

    /**
     * Checkbox setting keys.
     */
    private const CHECKBOX_KEYS = [

        // Post Settings
        'show_reading_time',
        'show_views_count',
        'show_likes_count',
        'show_author_bio',
        'show_related_posts',
        'show_social_share',

        // Comment Settings
        'comments_enabled',
        'comments_moderation',
        'allow_guest_comments',
        'notify_on_comment',
        'close_old_comments',
        'show_comment_count',

        // SEO Settings
        'seo_open_graph',
        'seo_twitter_card',
        'seo_schema',
        'seo_sitemap',

        // System Settings
        'maintenance_mode',
    ];

    /**
     * Ignore request keys.
     */
    private const SKIP_KEYS = [
        '_token',
        '_method',
        'site_logo',
        'site_favicon',
    ];

    /**
     * Cached settings.
     */
    protected array $settings = [];

    public function __construct(
        protected SettingRepositoryInterface $settingRepository
    ) {

        /*
        |--------------------------------------------------------------------------
        | Load Settings Once
        |--------------------------------------------------------------------------
        */
        $this->settings = Cache::rememberForever(
            self::CACHE_KEY,
            function () {

                return Setting::query()
                    ->pluck('value', 'key')
                    ->toArray();
            }
        );
    }

    /**
     * Get all cached settings.
     */
    public function all(): array
    {
        return $this->settings;
    }

    /**
     * Get single setting.
     */
    public function get(
        string $key,
        mixed $default = null
    ): mixed {

        return $this->settings[$key] ?? $default;
    }

    /**
     * Get all settings from repository.
     * Useful for admin forms/pages.
     */
    public function getAllSettings(): array
    {
        return $this->settingRepository->allAsArray();
    }

    /**
     * Update settings from request.
     */
    public function updateFromRequest(
        Request $request
    ): void {

        DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | Checkbox Fields
            |--------------------------------------------------------------------------
            */
            foreach (self::CHECKBOX_KEYS as $key) {

                $this->settingRepository->set(
                    $key,
                    $request->boolean($key) ? '1' : '0'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | File Uploads
            |--------------------------------------------------------------------------
            */
            $this->handleFileUploads($request);

            /*
            |--------------------------------------------------------------------------
            | Text / Select Fields
            |--------------------------------------------------------------------------
            */
            $skipKeys = array_merge(
                self::CHECKBOX_KEYS,
                self::SKIP_KEYS
            );

            $settings = collect($request->all())
                ->except($skipKeys)
                ->filter(fn ($value) => ! is_array($value))
                ->map(fn ($value) => $value ?? '')
                ->toArray();

            $this->settingRepository->setMany($settings);

            /*
            |--------------------------------------------------------------------------
            | Clear Settings Cache
            |--------------------------------------------------------------------------
            */
            $this->clearSettingsCache();
        });
    }

    /**
     * Handle file uploads.
     */
    protected function handleFileUploads(
        Request $request
    ): void {

        $uploads = [
            'site_logo',
            'site_favicon',
        ];

        foreach ($uploads as $field) {

            if (! $request->hasFile($field)) {
                continue;
            }

            $path = $request->file($field)
                ->store('settings', 'public');

            $this->settingRepository->set(
                $field,
                $path
            );
        }
    }

    /**
     * Clear caches.
     */
    public function clearCache(
        string $type = 'all'
    ): string {

        match ($type) {

            'config' => Artisan::call('config:clear'),

            'views' => Artisan::call('view:clear'),

            'routes' => Artisan::call('route:clear'),

            'permissions' => app(
                PermissionRegistrar::class
            )->forgetCachedPermissions(),

            'settings' => $this->clearSettingsCache(),

            'sessions' => $this->clearExpiredSessions(),

            default => $this->clearAllCaches(),
        };

        return ucfirst($type) . ' cleared successfully!';
    }

    /**
     * Clear settings cache only.
     */
    public function clearSettingsCache(): void
    {
        Cache::forget(self::CACHE_KEY);

        $this->settings = Setting::query()
            ->pluck('value', 'key')
            ->toArray();

        Cache::forever(
            self::CACHE_KEY,
            $this->settings
        );
    }

    /**
     * Clear all application caches.
     */
    protected function clearAllCaches(): void
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        $this->clearSettingsCache();

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }

    /**
     * Delete expired sessions.
     */
    public function clearExpiredSessions(): void
    {
        $expiredTime = now()->timestamp
            - (config('session.lifetime') * 60);

        DB::table('sessions')
            ->where('last_activity', '<', $expiredTime)
            ->delete();
    }
}
