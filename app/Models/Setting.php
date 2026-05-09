<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    protected $casts = [
        'value' => 'string',
    ];

    // ── Get ────────────────────────────────────────────────
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::rememberForever(
                "setting:{$key}",
                function () use ($key, $default) {
                    $s = static::where('key', $key)->first();
                    return $s ? $s->value : $default;
                }
            );
        } catch (\Exception $e) {
            // DB not ready (e.g. during migrations)
            return $default;
        }
    }

    // ── Set ────────────────────────────────────────────────
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );
        Cache::forget("setting:{$key}");
    }

    // ── Set many ───────────────────────────────────────────
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            static::set($key, $value);
        }
    }

    // ── All as array ───────────────────────────────────────
    public static function allAsArray(): array
    {
        try {
            return static::all()->pluck('value', 'key')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    // ── Clear all settings cache ───────────────────────────
    public static function clearCache(): void
    {
        try {
            $keys = static::pluck('key');
            foreach ($keys as $key) {
                Cache::forget("setting:{$key}");
            }
        } catch (\Exception $e) {
            // ignore
        }
    }

    // ── Boolean helper ─────────────────────────────────────
    public static function isEnabled(string $key): bool
    {
        return static::get($key, '0') === '1';
    }
}
