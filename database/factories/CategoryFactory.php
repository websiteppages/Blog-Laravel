<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    // Blog-க்கு realistic category names
    private array $categories = [
        'Design',
        'Technology',
        'Career',
        'Product',
        'Code',
        'Engineering',
        'Leadership',
        'Startup',
        'AI & Machine Learning',
        'Web Development',
        'Mobile Development',
        'DevOps',
        'UX Research',
        'Data Science',
        'Open Source',
    ];

    // Category colors — brand-aligned
    private array $colors = [
        '#2d6a2d', // Green — Design
        '#2d4a8a', // Blue — Technology
        '#8a5a1a', // Brown — Career
        '#8a2d2d', // Red — Product
        '#5a2d8a', // Purple — Code
        '#1a5a7a', // Teal — Engineering
        '#6b3a2d', // Rust — Leadership
        '#2d5a3a', // Dark green — Startup
        '#4a2d6a', // Dark purple — AI
        '#1a4a6a', // Navy — Web Dev
        '#6a2d4a', // Maroon — Mobile
        '#2d4a3a', // Forest — DevOps
        '#5a4a2d', // Tan — UX
        '#2d3a6a', // Indigo — Data
        '#3a5a2d', // Olive — Open Source
    ];

    public function definition(): array
    {
        // Random unique category name
        $name = fake()->unique()->randomElement($this->categories);

        return [
            // Schema: categories(parent_id, name, slug, description, color)
            'parent_id'   => null,    // Default: top-level
            'name'        => $name,
            'slug'        => Str::slug($name),
            'description' => fake()->optional(0.8)->sentence(),
            'color'       => fake()->randomElement($this->colors),
        ];
    }

    // ── States ────────────────────────────────────────────

    // Sub-category (parent_id set)
    public function child(Category $parent): static
    {
        return $this->state(fn(array $attributes) => [
            'parent_id' => $parent->id,
        ]);
    }

    // Specific color
    public function withColor(string $color): static
    {
        return $this->state(fn(array $attributes) => [
            'color' => $color,
        ]);
    }
}
