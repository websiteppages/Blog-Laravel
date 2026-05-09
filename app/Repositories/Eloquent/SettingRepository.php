<?php

namespace App\Repositories\Eloquent;

use App\Models\Setting;
use App\Repositories\Contracts\SettingRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SettingRepository implements SettingRepositoryInterface
{
    // ✅ Load ALL settings in ONE query, cache together
    private function loadAll(): array
    {
        return Cache::rememberForever('settings:all', function () {
            try {
                return Setting::all()->pluck('value', 'key')->toArray();
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->loadAll();
        return $all[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );

        // Bust the combined cache
        Cache::forget('settings:all');

        // Also bust individual key cache (for backward compat)
        Cache::forget("setting:{$key}");
    }

    public function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => (string) $value]
            );
        }

        // Single cache bust for all
        Cache::forget('settings:all');
    }

    public function allAsArray(): array
    {
        return $this->loadAll();
    }

    public function clearCache(?string $key = null): void
    {
        Cache::forget('settings:all');
        if ($key) {
            Cache::forget("setting:{$key}");
        }
    }

    public function isEnabled(string $key): bool
    {
        return $this->get($key, '0') === '1';
    }
}
