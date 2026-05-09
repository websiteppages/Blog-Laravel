<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceRole;
use App\Policies\PostPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkspacePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    // ─── Model → Policy Mapping ───────────────
    protected $policies = [
        //Post::class => PostPolicy::class,
        User::class => UserPolicy::class,
        Workspace::class     => WorkspacePolicy::class,
        WorkspaceRole::class => RolePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
