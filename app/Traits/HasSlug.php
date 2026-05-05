<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug(
                    $model->{$model->slugSource()}
                );
            }
        });
    }

    public static function generateUniqueSlug(
        string $value,
        ?int   $excludeId = null
    ): string {
        $slug   = Str::slug($value);
        $unique = $slug;
        $count  = 1;

        while (
            static::where('slug', $unique)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $unique = "{$slug}-{$count}";
            $count++;
        }

        return $unique;
    }

    public function slugSource(): string
    {
        return 'name'; // Override in model if needed
    }
}
