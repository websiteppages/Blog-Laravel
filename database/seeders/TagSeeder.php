<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    private array $tags = [
        // PHP / Laravel ecosystem
        ['name' => 'Laravel',           'color' => '#ef3b2d'],
        ['name' => 'PHP',               'color' => '#777bb4'],
        ['name' => 'Livewire',          'color' => '#fb70a9'],
        ['name' => 'Eloquent',          'color' => '#ef3b2d'],
        ['name' => 'Artisan',           'color' => '#ef3b2d'],
        ['name' => 'Composer',          'color' => '#f28d1a'],

        // JavaScript ecosystem
        ['name' => 'JavaScript',        'color' => '#f7df1e'],
        ['name' => 'TypeScript',        'color' => '#3178c6'],
        ['name' => 'Vue.js',            'color' => '#42b883'],
        ['name' => 'Alpine.js',         'color' => '#77c1d2'],
        ['name' => 'Inertia.js',        'color' => '#9553e9'],
        ['name' => 'Node.js',           'color' => '#339933'],

        // CSS / Design
        ['name' => 'Tailwind CSS',      'color' => '#38bdf8'],
        ['name' => 'CSS',               'color' => '#264de4'],
        ['name' => 'Figma',             'color' => '#f24e1e'],
        ['name' => 'UI Design',         'color' => '#ff6b6b'],
        ['name' => 'UX',                'color' => '#845ef7'],
        ['name' => 'Accessibility',     'color' => '#20c997'],

        // Database
        ['name' => 'MySQL',             'color' => '#00758f'],
        ['name' => 'Redis',             'color' => '#dc382d'],
        ['name' => 'PostgreSQL',        'color' => '#336791'],
        ['name' => 'SQLite',            'color' => '#003b57'],

        // DevOps / Infra
        ['name' => 'Docker',            'color' => '#2496ed'],
        ['name' => 'Git',               'color' => '#f05032'],
        ['name' => 'CI/CD',             'color' => '#40a9ff'],
        ['name' => 'AWS',               'color' => '#ff9900'],
        ['name' => 'Linux',             'color' => '#fcc624'],

        // Concepts
        ['name' => 'Clean Code',        'color' => '#20c997'],
        ['name' => 'TDD',               'color' => '#74c0fc'],
        ['name' => 'REST API',          'color' => '#66d9e8'],
        ['name' => 'Security',          'color' => '#ff6b6b'],
        ['name' => 'Performance',       'color' => '#ffd43b'],
        ['name' => 'Design Patterns',   'color' => '#da77f2'],
        ['name' => 'SOLID',             'color' => '#a9e34b'],
        ['name' => 'Microservices',     'color' => '#74c0fc'],

        // Career / Soft skills
        ['name' => 'Career',            'color' => '#a9e34b'],
        ['name' => 'Remote Work',       'color' => '#74c0fc'],
        ['name' => 'Productivity',      'color' => '#f783ac'],
        ['name' => 'Leadership',        'color' => '#ffa94d'],

        // Content type
        ['name' => 'Beginners',         'color' => '#63e6be'],
        ['name' => 'Tutorial',          'color' => '#ffa94d'],
        ['name' => 'Best Practices',    'color' => '#da77f2'],
        ['name' => 'Open Source',       'color' => '#69db7c'],
        ['name' => 'AI',                'color' => '#74c0fc'],
        ['name' => 'Opinion',           'color' => '#f783ac'],
        ['name' => 'Case Study',        'color' => '#ffd43b'],
    ];

    public function run(): void
    {
        $this->command->info('🏷  Seeding tags...');

        foreach ($this->tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                [
                    'slug'  => Str::slug($tag['name']),
                    'color' => $tag['color'],
                ]
            );
        }

        $this->command->info('   ✓ ' . Tag::count() . ' tags created');
    }
}
