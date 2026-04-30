<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{
    protected $model = Tag::class;

    // Realistic tech/blog tags
    private array $tags = [
        'Laravel', 'PHP', 'JavaScript', 'TypeScript', 'Vue.js',
        'React', 'Alpine.js', 'Livewire', 'Tailwind CSS', 'CSS',
        'HTML', 'MySQL', 'PostgreSQL', 'Redis', 'Docker',
        'Git', 'REST API', 'GraphQL', 'Testing', 'TDD',
        'Clean Code', 'Design Patterns', 'SOLID', 'Refactoring',
        'UI Design', 'UX', 'Figma', 'Accessibility', 'Performance',
        'SEO', 'Open Source', 'Career', 'Remote Work', 'Productivity',
        'Leadership', 'Agile', 'DevOps', 'CI/CD', 'AWS',
        'AI', 'Machine Learning', 'ChatGPT', 'Automation', 'Security',
        'Beginners', 'Tutorial', 'Tips', 'Best Practices', 'Tools',
    ];

    private array $tagColors = [
        '#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6',
        '#1abc9c', '#e67e22', '#34495e', '#e91e63', '#00bcd4',
    ];

    public function definition(): array
    {
        // Schema: tags(name, slug, color)
        $name = fake()->unique()->randomElement($this->tags);

        return [
            'name'  => $name,
            'slug'  => Str::slug($name),
            'color' => fake()->optional(0.6)->randomElement($this->tagColors),
        ];
    }
}
