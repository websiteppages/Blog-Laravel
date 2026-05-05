<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use App\Policies\CategoryPolicy;
use App\Policies\PostPolicy;
use App\Policies\RolePolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    public function boot(): void
    {
        // ── Observers ───────────────────────────────────────
        //Post::observe(PostObserver::class);
        //User::observe(UserObserver::class);

        // ── Policies ────────────────────────────────────────
        Gate::policy(User::class,     UserPolicy::class);
        Gate::policy(Role::class,     RolePolicy::class);
        //Role model-க்கு எந்த Policy class use பண்ணணும் என்று Laravel-க்கு சொல்லு

        //Gate::policy(Post::class,     PostPolicy::class);
        //Gate::policy(Category::class, CategoryPolicy::class);
        //Gate::policy(Tag::class,      TagPolicy::class);

        // ── Tailwind Pagination ──────────────────────────────
        Paginator::useTailwind();
    }
}
