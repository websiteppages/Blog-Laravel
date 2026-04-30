<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📝 Seeding posts...');

        // ── Pre-load data ─────────────────────────────────────
        $users      = User::all();
        $categories = Category::whereNull('parent_id')->get();
        $tags       = Tag::all();

        // Known users
        $sarah  = User::where('email', 'sarah@inkwell.app')->first();
        $marcus = User::where('email', 'marcus@inkwell.app')->first();
        $priya  = User::where('email', 'priya@inkwell.app')->first();
        $raj    = User::where('email', 'raj@inkwell.app')->first();
        $ama    = User::where('email', 'ama@inkwell.app')->first();
        $leo    = User::where('email', 'leo@inkwell.app')->first();

        // Known categories
        $designCat  = Category::where('name', 'Design')->first();
        $techCat    = Category::where('name', 'Technology')->first();
        $codeCat    = Category::where('name', 'Code')->first();
        $careerCat  = Category::where('name', 'Career')->first();
        $productCat = Category::where('name', 'Product')->first();
        $engCat     = Category::where('name', 'Engineering')->first();

        // ── Step 1: Known featured posts ─────────────────────
        $this->command->info('   Creating featured posts...');

        $featuredPosts = [
            [
                'user_id'     => $sarah?->id,
                'category_id' => $designCat?->id,
                'title'       => 'The Art of Thoughtful Interface Design: A Deep Dive',
                'excerpt'     => 'Exploring how the most impactful digital products balance aesthetic beauty with profound usability.',
                'tags'        => ['UI Design', 'UX', 'Figma', 'Accessibility'],
                'views'       => 12450,
                'likes'       => 847,
            ],
            [
                'user_id'     => $marcus?->id,
                'category_id' => $techCat?->id,
                'title'       => 'AI-First Development: Rethinking the Engineering Workflow',
                'excerpt'     => 'How language models are quietly changing the way we architect, review, and ship software products.',
                'tags'        => ['AI', 'Laravel', 'Best Practices'],
                'views'       => 9870,
                'likes'       => 634,
            ],
            [
                'user_id'     => $ama?->id,
                'category_id' => $careerCat?->id,
                'title'       => 'From Maker to Manager: Navigating the IC to Lead Transition',
                'excerpt'     => 'The skills that made you an exceptional individual contributor might be holding you back as a leader.',
                'tags'        => ['Leadership', 'Career', 'Remote Work'],
                'views'       => 8320,
                'likes'       => 521,
            ],
            [
                'user_id'     => $raj?->id,
                'category_id' => $productCat?->id,
                'title'       => 'Building Products That Last: Lessons from Decade-Old Startups',
                'excerpt'     => 'Why some early-stage decisions echo through an entire company\'s trajectory.',
                'tags'        => ['Case Study', 'Opinion', 'Best Practices'],
                'views'       => 7640,
                'likes'       => 412,
            ],
            [
                'user_id'     => $priya?->id,
                'category_id' => $codeCat?->id,
                'title'       => 'Writing Code Worth Reading: A Practitioner\'s Guide',
                'excerpt'     => 'Clean code isn\'t about following rules — it\'s about communicating intent clearly to your future self.',
                'tags'        => ['Clean Code', 'PHP', 'Best Practices'],
                'views'       => 6540,
                'likes'       => 398,
            ],
            [
                'user_id'     => $leo?->id,
                'category_id' => $designCat?->id,
                'title'       => 'Typography as Architecture: Structure Through Type',
                'excerpt'     => 'Fonts aren\'t decoration — they\'re the invisible scaffolding that holds visual communication together.',
                'tags'        => ['Typography', 'UI Design', 'CSS'],
                'views'       => 5890,
                'likes'       => 334,
            ],
        ];

        foreach ($featuredPosts as $postData) {
            $postTags = Tag::whereIn('name', $postData['tags'])->get();

            $post = Post::create([
                'user_id'      => $postData['user_id'] ?? $users->random()->id,
                'category_id'  => $postData['category_id'] ?? $categories->random()->id,
                'title'        => $postData['title'],
                'slug'         => Str::slug($postData['title']),
                'excerpt'      => $postData['excerpt'],
                'content'      => $this->generateContent($postData['title']),
                'cover_image'  => null,
                'status'       => PostStatus::Published->value,
                'published_at' => now()->subDays(rand(1, 60)),
                'is_featured'  => true,
                'reading_time' => rand(5, 12),
                'views_count'  => $postData['views'],
                'likes_count'  => $postData['likes'],
            ]);

            $post->tags()->sync($postTags->pluck('id'));
        }

        // ── Step 2: Popular published posts ──────────────────
        $this->command->info('   Creating popular posts...');

        Post::factory(14)
            ->popular()
            ->create([
                'user_id'     => $users->random()->id,
                'category_id' => $categories->random()->id,
            ])
            ->each(fn($post) =>
                $post->tags()->sync(
                    $tags->random(rand(2, 4))->pluck('id')
                )
            );

        // ── Step 3: Regular published posts ──────────────────
        $this->command->info('   Creating published posts...');

        Post::factory(30)
            ->published()
            ->create([
                'user_id'     => $users->random()->id,
                'category_id' => $categories->random()->id,
            ])
            ->each(fn($post) =>
                $post->tags()->sync(
                    $tags->random(rand(1, 4))->pluck('id')
                )
            );

        // ── Step 4: Draft posts ───────────────────────────────
        $this->command->info('   Creating draft posts...');

        Post::factory(8)
            ->draft()
            ->create([
                'user_id'     => $users->whereIn(
                    'email',
                    [
                        'sarah@inkwell.app',
                        'marcus@inkwell.app',
                        'priya@inkwell.app',
                    ]
                )->random()->id,
                'category_id' => $categories->random()->id,
            ]);

        // ── Step 5: Scheduled posts ───────────────────────────
        $this->command->info('   Creating scheduled posts...');

        Post::factory(4)
            ->scheduled()
            ->create([
                'user_id'     => $users->random()->id,
                'category_id' => $categories->random()->id,
            ]);

        $this->command->info(
            '   ✓ ' . Post::count() . ' posts created total'
        );

        $this->command->table(
            ['Status', 'Count'],
            [
                ['Published (Featured)', Post::where('is_featured', true)->count()],
                ['Published (Regular)',  Post::where('status', 'published')->where('is_featured', false)->count()],
                ['Draft',               Post::where('status', 'draft')->count()],
                ['Scheduled',           Post::where('status', 'scheduled')->count()],
            ]
        );
    }

    // ── Realistic content generator ───────────────────────────
    private function generateContent(string $title): string
    {
        $paragraphs = [
            "There's a peculiar tension at the heart of {$title}. We build systems intended to be invisible — tools that get out of the way so users can accomplish their goals — yet we spend enormous energy on every detail. This tension isn't a contradiction. It's the whole point.",

            "When we talk about good practices, we often gesture vaguely at aesthetics: clean lines, pleasing interfaces, elegant code. But the practitioners who've shaped the most enduring work understand that aesthetics aren't the goal — they're the vehicle. The real work is cognitive. How do we reduce mental overhead? How do we make the right action obvious?",

            "The best solutions feel inevitable in retrospect. You encounter well-designed work and think, \"of course it works this way.\" That feeling of inevitability is manufactured — painstakingly — through iteration, research, and a deep commitment to understanding how people actually think and behave, not how we wish they would.",

            "Like any discipline, there's a hierarchy of needs. At the base: functionality. Does it do what it claims? Above that: reliability. Can users trust it to behave consistently? Then usability — is it learnable, efficient, forgiving of mistakes? Only after all of these are satisfied does the finer craft even enter the conversation in a meaningful way.",

            "This sounds obvious, but countless products ship with fundamental gaps disguised by beautiful surfaces. The gorgeous loading animation doesn't compensate for a form that loses data on submission. The stunning visual doesn't redeem a core feature that doesn't actually solve the user's problem.",

            "Preserving user input on error is one of the smallest, most underrated things you can do. It costs almost nothing to implement, and it signals profound respect for the person on the other side of the screen.",

            "The practitioners who get this right aren't necessarily more talented — they're more patient. They resist the urge to ship until the fundamentals are solid. They treat every friction point as a personal failure. They obsess over the details that most people never consciously notice, but everyone feels.",

            "As you apply these principles to your own work, remember that the goal isn't perfection on the first try. It's building the judgment to recognize when something is good enough, and the courage to keep improving when it isn't.",
        ];

        $content = '';

        foreach ($paragraphs as $i => $para) {
            if ($i === 2) {
                $content .= "<h2>The Core Principle</h2>\n";
            }
            if ($i === 4) {
                $content .= "<h2>Common Pitfalls</h2>\n";
            }
            if ($i === 6) {
                $content .= "<h2>Putting It Into Practice</h2>\n";
            }
            if ($i === 3) {
                $content .= "<blockquote><p>The best {$title} feels inevitable in retrospect — that's the mark of real mastery.</p></blockquote>\n";
            }

            $content .= "<p>{$para}</p>\n\n";
        }

        return trim($content);
    }
}
