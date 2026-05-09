<?php

namespace App\Providers;

use App\Repositories\Eloquent\AuditLogRepository;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\HomeRepositoryInterface;
use App\Repositories\Contracts\PostReportRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WorkspaceInviteRepositoryInterface;
use App\Repositories\Contracts\WorkspaceMemberRepositoryInterface;
use App\Repositories\Contracts\WorkspaceRepositoryInterface;
use App\Repositories\Contracts\WorkspaceRoleRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\HomeRepository;
use App\Repositories\Eloquent\PostReportRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\WorkspaceInviteRepository;
use App\Repositories\Eloquent\WorkspaceMemberRepository;
use App\Repositories\Eloquent\WorkspaceRepository;
use App\Repositories\Eloquent\WorkspaceRoleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WorkspaceRepositoryInterface::class, WorkspaceRepository::class);
        $this->app->bind(WorkspaceRoleRepositoryInterface::class, WorkspaceRoleRepository::class);
        $this->app->bind(WorkspaceInviteRepositoryInterface::class, WorkspaceInviteRepository::class);
        $this->app->bind(WorkspaceMemberRepositoryInterface::class, WorkspaceMemberRepository::class);
        $this->app->bind(AuditLogRepositoryInterface::class, AuditLogRepository::class);

        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);

        // $this->app->bind(
        //     PostRepositoryInterface::class,
        //     PostRepository::class
        // );

        // $this->app->bind(
        //     CategoryRepositoryInterface::class,
        //     CategoryRepository::class
        // );

        // $this->app->bind(
        //     TagRepositoryInterface::class,
        //     TagRepository::class
        // );



        // $this->app->bind(
        //     CommentRepositoryInterface::class,
        //     CommentRepository::class
        // );

        // $this->app->bind(
        //     PostReportRepositoryInterface::class,
        //     PostReportRepository::class
        // );



        // $this->app->bind(
        //     HomeRepositoryInterface::class,
        //     HomeRepository::class
        // );
    }
}
