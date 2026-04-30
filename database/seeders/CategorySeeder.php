<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    // Parent → children map
    private array $tree = [
        [
            'name'        => 'Design',
            'description' => 'UI/UX design, visual design, and design systems.',
            'color'       => '#2d6a2d',
            'children'    => [
                ['name' => 'UI Design',    'description' => 'User interface design principles and patterns.'],
                ['name' => 'UX Research',  'description' => 'User experience research methods and insights.'],
                ['name' => 'Typography',   'description' => 'Type design, font pairing, and readability.'],
                ['name' => 'Design Systems','description' => 'Component libraries and design tokens.'],
            ],
        ],
        [
            'name'        => 'Technology',
            'description' => 'Latest in tech, frameworks, tools, and industry trends.',
            'color'       => '#2d4a8a',
            'children'    => [
                ['name' => 'AI & ML',          'description' => 'Artificial intelligence and machine learning.'],
                ['name' => 'Web Development',  'description' => 'Modern web development practices.'],
                ['name' => 'Mobile',           'description' => 'iOS, Android, and cross-platform development.'],
                ['name' => 'Cloud & DevOps',   'description' => 'Cloud infrastructure and deployment.'],
            ],
        ],
        [
            'name'        => 'Career',
            'description' => 'Career growth, interviews, and professional development.',
            'color'       => '#8a5a1a',
            'children'    => [
                ['name' => 'Leadership',   'description' => 'Engineering leadership and management.'],
                ['name' => 'Remote Work',  'description' => 'Tips and strategies for remote teams.'],
                ['name' => 'Interviews',   'description' => 'Technical interview prep and advice.'],
                ['name' => 'Freelancing',  'description' => 'Running a freelance business.'],
            ],
        ],
        [
            'name'        => 'Product',
            'description' => 'Product management, strategy, and user research.',
            'color'       => '#8a2d2d',
            'children'    => [
                ['name' => 'Strategy',     'description' => 'Product strategy and vision.'],
                ['name' => 'Analytics',    'description' => 'Data-driven product decisions.'],
                ['name' => 'Growth',       'description' => 'Growth hacking and user acquisition.'],
                ['name' => 'Roadmapping',  'description' => 'Building and prioritizing product roadmaps.'],
            ],
        ],
        [
            'name'        => 'Code',
            'description' => 'Programming tutorials, patterns, and best practices.',
            'color'       => '#5a2d8a',
            'children'    => [
                ['name' => 'Laravel',      'description' => 'Laravel PHP framework tutorials and tips.'],
                ['name' => 'JavaScript',   'description' => 'JS, TypeScript, and modern frameworks.'],
                ['name' => 'Clean Code',   'description' => 'Code quality, patterns, and refactoring.'],
                ['name' => 'Testing',      'description' => 'Unit, feature, and E2E testing strategies.'],
            ],
        ],
        [
            'name'        => 'Engineering',
            'description' => 'Software engineering principles and system design.',
            'color'       => '#1a5a7a',
            'children'    => [
                ['name' => 'Architecture',  'description' => 'System design and software architecture.'],
                ['name' => 'Performance',   'description' => 'Optimization techniques and benchmarks.'],
                ['name' => 'Security',      'description' => 'Application security and best practices.'],
                ['name' => 'Open Source',   'description' => 'Contributing to and maintaining OSS.'],
            ],
        ],
    ];

    public function run(): void
    {
        $this->command->info('📂 Seeding categories...');

        $total = 0;

        foreach ($this->tree as $parentData) {
            // Create parent
            $parent = Category::create([
                'parent_id'   => null,
                'name'        => $parentData['name'],
                'slug'        => Str::slug($parentData['name']),
                'description' => $parentData['description'],
                'color'       => $parentData['color'],
            ]);
            $total++;

            // Create children
            foreach ($parentData['children'] as $childData) {
                Category::create([
                    'parent_id'   => $parent->id,
                    'name'        => $childData['name'],
                    'slug'        => Str::slug($childData['name']),
                    'description' => $childData['description'] ?? null,
                    'color'       => $parentData['color'], // Inherit parent color
                ]);
                $total++;
            }
        }

        $this->command->info("   ✓ {$total} categories created (6 parent + 24 children)");
    }
}
