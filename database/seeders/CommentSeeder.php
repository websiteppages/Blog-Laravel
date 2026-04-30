<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    private array $commentBodies = [
        'This is exactly what I needed to read today. The breakdown of the core concepts is incredibly clear.',
        'I\'ve been struggling with this topic for months. Your explanation finally made it click for me.',
        'Great article! One question — how would you handle this in a larger codebase with multiple teams?',
        'The example in section two is brilliant. I\'m going to adapt this approach for my current project.',
        'I disagree with the point about performance. In my experience, the overhead is negligible at scale.',
        'Thank you for writing this. I\'ve shared it with my entire team.',
        'The code examples are super clean and practical. This is bookmarked.',
        'Do you have a follow-up planned? I\'d love to see this applied to a real-world scenario.',
        'This changed how I think about the problem. Subscribed for more.',
        'Minor note: the third example has a small typo on line 3, but the concept is spot on.',
        'Been doing it the old way for years. Time to refactor. Thanks for the nudge.',
        'Really appreciate the depth here. Most articles on this topic stay too surface-level.',
        'The analogy you used in the middle section is perfect. I\'m borrowing that for my next talk.',
        'This is the best explanation of this concept I\'ve found anywhere online.',
        'How does this approach scale with async operations? That\'s my current challenge.',
    ];

    public function run(): void
    {
        $this->command->info('💬 Seeding comments...');

        $publishedPosts = Post::where('status', 'published')
                              ->inRandomOrder()
                              ->limit(20)
                              ->get();

        $users = User::all();

        if ($publishedPosts->isEmpty() || $users->isEmpty()) {
            $this->command->warn('   ⚠ No posts or users found, skipping comments.');
            return;
        }

        $totalComments = 0;

        foreach ($publishedPosts as $post) {
            // Each post → 2–6 top-level comments
            $commentCount = rand(2, 6);

            for ($i = 0; $i < $commentCount; $i++) {
                $commenter = $users->random();

                // Skip if commenter is the post author
                if ($commenter->id === $post->user_id) {
                    continue;
                }

                $topComment = Comment::create([
                    'post_id'     => $post->id,
                    'user_id'     => $commenter->id,
                    'parent_id'   => null,
                    'body'        => fake()->randomElement($this->commentBodies),
                    'is_approved' => rand(1, 10) > 2, // 80% approved
                    'guest_name'  => null,
                    'guest_email' => null,
                ]);

                $totalComments++;

                // 50% chance of replies
                if (rand(0, 1)) {
                    $replyCount = rand(1, 3);

                    for ($r = 0; $r < $replyCount; $r++) {
                        $replier = $users->random();

                        Comment::create([
                            'post_id'     => $post->id,
                            'user_id'     => $replier->id,
                            'parent_id'   => $topComment->id, // Nested reply
                            'body'        => fake()->randomElement([
                                'Great point! I hadn\'t thought about it that way.',
                                'Thanks for the question — great follow-up.',
                                'Totally agree with your perspective here.',
                                'I\'ve had a similar experience. Works well in practice.',
                                'Valid concern. There are tradeoffs either way.',
                                'This is a good discussion. Thanks everyone.',
                            ]),
                            'is_approved' => rand(1, 10) > 1, // 90% approved
                            'guest_name'  => null,
                            'guest_email' => null,
                        ]);

                        $totalComments++;
                    }
                }
            }
        }

        $this->command->info(
            '   ✓ ' . $totalComments . ' comments created'
        );

        $this->command->info(
            '   ✓ ' . Comment::where('is_approved', false)->count()
            . ' pending approval'
        );
    }
}
