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

    php artisan make:middleware RoleMiddleware

    php artisan make:request Admin/StoreRoleRequest
    php artisan make:request Admin/UpdateRoleRequest



    php artisan make:model Book -m
    php artisan make:model Role

    ---------------------------------------------------------------------
    "autoload": {
            "files": [
                "app/Helpers/Helpers.php"
            ]
        }
        composer dump-autoload
    ---------------------------------------------------------------------

--}}
