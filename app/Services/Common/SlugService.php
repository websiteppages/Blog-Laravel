<?php

namespace App\Services\Common;

use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate unique slug.
     */
    public function generate(
        string $name,
        string $modelClass,
        array $conditions = [],
        string $column = 'slug'
    ): string {

        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 1;

        while (
            $modelClass::query()
                ->withTrashed()
                ->where($column, $slug)
                ->where($conditions)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
