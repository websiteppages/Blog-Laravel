<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    // Realistic blog post titles
    private array $titles = [
        'The Art of Writing Clean Code That Actually Works',
        'Why Your Database is Slower Than It Should Be',
        'Building Scalable APIs with Laravel and MySQL',
        'From Junior to Senior: The Skills Nobody Talks About',
        'Understanding the Repository Pattern in Depth',
        'Why I Switched from React to Vue (And Never Looked Back)',
        'The Complete Guide to Laravel Service Containers',
        'Designing for Accessibility: A Practical Approach',
        'How to Structure a Large Laravel Application',
        'Redis Caching Strategies That Actually Scale',
        'The Hidden Costs of Technical Debt',
        'Building Real-Time Features with Laravel and Pusher',
        'A Beginner\'s Guide to Docker for PHP Developers',
        'Optimizing Eloquent Queries for Better Performance',
        'The Psychology of Great UX Design',
        'How We Reduced Our App\'s Load Time by 80%',
        'Understanding SOLID Principles Through Real Examples',
        'Why Remote Work Changed How I Code',
        'The Future of Full-Stack Development in 2024',
        'Building a Design System From Scratch',
        'Mastering Tailwind CSS: Beyond the Basics',
        'Test-Driven Development: A Practical Introduction',
        'How to Handle Authentication in Laravel APIs',
        'The Art of Code Review: Giving Feedback That Helps',
        'Understanding Database Indexing for Beginners',
        'Getting Started with Livewire 3',
        'Why I Use Alpine.js Instead of Vue for Small Projects',
        'Building Command-Line Tools with Laravel Artisan',
        'A Deep Dive into PHP 8.3\'s New Features',
        'How to Deploy Laravel Apps to Production Safely',
    ];

    public function definition(): array
    {
        $title   = fake()->unique()->randomElement($this->titles);
        $content = $this->generateContent();
        $words   = str_word_count(strip_tags($content));

        return [
            // Schema: posts(user_id, category_id, title, slug, excerpt,
            //               content, cover_image, status, published_at,
            //               is_featured, reading_time, views_count, likes_count)

            'user_id'      => User::factory(),      // Auto create user
            'category_id'  => Category::factory(),  // Auto create category
            'title'        => $title,
            'slug'         => Str::slug($title),
            'excerpt'      => fake()->paragraph(2),
            'content'      => $content,
            'cover_image'  => null,                 // Real file இல்லை → null
            'status'       => PostStatus::Published,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'is_featured'  => false,
            'reading_time' => max(1, (int) ceil($words / 200)),
            'views_count'  => fake()->numberBetween(0, 50000),
            'likes_count'  => fake()->numberBetween(0, 5000),
        ];
    }

    // ── States ────────────────────────────────────────────────

    // Published post
    public function published(): static
    {
        return $this->state(fn(array $attributes) => [
            'status'       => PostStatus::Published,
            'published_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    // Draft post
    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status'       => PostStatus::Draft,
            'published_at' => null,
        ]);
    }

    // Scheduled post
    public function scheduled(): static
    {
        return $this->state(fn(array $attributes) => [
            'status'       => PostStatus::Scheduled,
            'published_at' => fake()->dateTimeBetween('now', '+1 month'),
        ]);
    }

    // Featured post
    public function featured(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_featured'  => true,
            'status'       => PostStatus::Published,
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'views_count'  => fake()->numberBetween(5000, 50000),
        ]);
    }

    // Popular post (high views)
    public function popular(): static
    {
        return $this->state(fn(array $attributes) => [
            'views_count'  => fake()->numberBetween(10000, 100000),
            'likes_count'  => fake()->numberBetween(500, 10000),
            'status'       => PostStatus::Published,
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    // Specific author
    public function forUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    // Specific category
    public function inCategory(Category $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category_id' => $category->id,
        ]);
    }

    // ── Realistic content generator ───────────────────────
    private function generateContent(): string
    {
        $paragraphs = fake()->numberBetween(6, 12);
        $content    = '';

        // Introduction
        $content .= '<p>' . fake()->paragraph(4) . '</p>';
        $content .= "\n\n";

        // Main content with headings
        $headings = [
            'Getting Started',
            'The Core Concept',
            'Why This Matters',
            'A Practical Example',
            'Common Mistakes to Avoid',
            'Best Practices',
            'Advanced Techniques',
            'Real-World Application',
            'Performance Considerations',
            'Conclusion',
        ];

        $usedHeadings = fake()->randomElements(
            $headings,
            min(4, $paragraphs - 2)
        );

        for ($i = 0; $i < $paragraphs; $i++) {

            // Add heading every 2-3 paragraphs
            if ($i > 0 && $i % 2 === 0 && !empty($usedHeadings)) {
                $heading  = array_shift($usedHeadings);
                $content .= '<h2>' . $heading . '</h2>' . "\n";
            }

            // Occasionally add code block
            if ($i === 3) {
                $content .= $this->generateCodeBlock();
                $content .= "\n\n";
            }

            // Occasionally add blockquote
            if ($i === 5) {
                $content .= '<blockquote>';
                $content .= '<p>' . fake()->sentence(12) . '</p>';
                $content .= '</blockquote>';
                $content .= "\n\n";
            }

            $content .= '<p>' . fake()->paragraph(
                fake()->numberBetween(3, 6)
            ) . '</p>';
            $content .= "\n\n";
        }

        return trim($content);
    }

    private function generateCodeBlock(): string
    {
        $examples = [
            "// Laravel Eloquent example\n\$posts = Post::with(['author', 'category'])\n    ->published()\n    ->orderByDesc('published_at')\n    ->paginate(12);",
            "// Service layer\npublic function createPost(PostData \$data, int \$userId): Post\n{\n    return DB::transaction(function () use (\$data, \$userId) {\n        \$post = \$this->postRepository->create([\n            ...\$data->toArray(),\n            'user_id' => \$userId,\n        ]);\n        return \$post;\n    });\n}",
            "// Repository pattern\npublic function findBySlug(string \$slug): Post\n{\n    return \$this->model\n        ->with(['author', 'category', 'tags'])\n        ->where('slug', \$slug)\n        ->firstOrFail();\n}",
        ];

        $code = fake()->randomElement($examples);

        return '<pre><code>' . htmlspecialchars($code) . '</code></pre>';
    }
}
