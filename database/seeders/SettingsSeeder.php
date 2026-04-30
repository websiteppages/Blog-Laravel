<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // General
            'site_name'            => 'Inkwell',
            'site_description'     => 'A modern blogging platform.',
            'site_email'           => 'hello@inkwell.app',
            'timezone'             => 'UTC',
            'date_format'          => 'M j, Y',

            // Appearance
            'theme_mode'           => 'light',
            'theme_color'          => '#c9883a',
            'admin_theme'          => 'default',
            'sidebar_position'     => 'right',

            // Posts
            'posts_per_page'       => '9',
            'posts_sort'           => 'latest',
            'show_reading_time'    => '1',
            'show_views_count'     => '1',
            'show_likes_count'     => '1',
            'show_author_bio'      => '1',
            'show_related_posts'   => '1',
            'show_social_share'    => '1',

            // Comments
            'comments_enabled'     => '1',
            'comments_moderation'  => '1',
            'allow_guest_comments' => '0',
            'notify_on_comment'    => '1',
            'close_old_comments'   => '0',
            'close_comments_days'  => '90',
            'show_comment_count'   => '1',

            // SEO
            'analytics_id'         => '',
            'seo_robots'           => "User-agent: *\nAllow: /",
            'meta_keywords'        => '',
            'seo_open_graph'       => '1',
            'seo_twitter_card'     => '1',
            'seo_schema'           => '1',
            'seo_sitemap'          => '1',

            // Social
            'social_twitter'       => '',
            'social_github'        => '',
            'social_linkedin'      => '',
            'social_instagram'     => '',
            'social_facebook'      => '',
            'social_youtube'       => '',
            'social_discord'       => '',
            'social_rss'           => '',

            // Advanced
            'maintenance_mode'     => '0',
            'maintenance_message'  => "We're performing scheduled maintenance. We'll be back shortly!",
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->command->info('✓ Settings seeded with defaults');
    }
}
