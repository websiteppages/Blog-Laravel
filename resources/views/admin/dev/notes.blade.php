{{--

    --------------------------------------------------------
        {{ config('app.name') }}
        {{ config('app.url') }}
        {{config('mail.mailers.smtp.username')}}
        {{config('mail.mailers.contact.username')}}
    ------------------------------------------------------
        php artisan migrate - 👉 All migrations run ஆகும்
        php artisan migrate:rollback - 👉 Last migration undo ஆகும்
        php artisan migrate:fresh - 👉 All tables delete + recreate
    ----------------------------------------------------------------------

    php artisan make:controller Web/HomeController
    php artisan make:controller Customer/DashboardController
    php artisan make:controller Admin/OverviewController
    php artisan make:controller Admin/RoleController
    php artisan make:controller Admin/UserController
    php artisan make:controller Admin/SettingsController
    php artisan make:controller SitemapController

    php artisan make:middleware RoleMiddleware

    php artisan make:request Admin/StoreRoleRequest
    php artisan make:request Admin/UpdateRoleRequest

    php artisan make:listener SendInviteEmail

    php artisan make:mail WorkspaceInviteMail

    php artisan make:provider EventServiceProvider
    php artisan make:provider ViewServiceProvider
    php artisan make:Middleware LoadUserRelations



    php artisan make:model Book -m
    php artisan make:model Role
    php artisan make:model Post -m

    php artisan make:model Workspace -m
    php artisan make:model WorkspaceRole -m
    php artisan make:model WorkspaceMember -m
    php artisan make:model WorkspaceInvite -m
    php artisan make:model WorkspaceSetting -m

    php artisan make:model AuditLog -m

    php artisan make:model Setting -m

    ---------------------------------------------------------------------
    "autoload": {
            "files": [
                "app/Helpers/Helpers.php"
            ]
        }
        composer dump-autoload
    ---------------------------------------------------------------------

--}}
