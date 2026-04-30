{{--
        php artisan make:seeder UserSeeder
        php artisan make:seeder CategorySeeder
        php artisan make:seeder TagSeeder
        php artisan make:seeder PostSeeder
        php artisan make:seeder DatabaseSeeder
    ─────────────────────────────────────────────────────────────
        php artisan make:factory UserFactory --model=User
        php artisan make:factory CategoryFactory --model=Category
        php artisan make:factory TagFactory --model=Tag
        php artisan make:factory PostFactory --model=Post

    ─────────────────────────────────────────────────────────────
        php artisan migrate:fresh

        # ── Fresh migration + seed (எல்லாவற்றையும் reset செய்யும்) ──
            php artisan migrate:fresh --seed

        # ── Seed மட்டும் (migration இல்லாமல்) ───────────────────────
            php artisan db:seed

        # ── Specific seeder மட்டும் run செய்ய ────────────────────────
            php artisan db:seed --class=DatabaseSeeder
            php artisan db:seed --class=UserSeeder
            php artisan db:seed --class=UserRoleSeeder
            php artisan db:seed --class=RoleSeeder
            php artisan db:seed --class=PermissionSeeder

            php artisan db:seed --class=CategorySeeder
            php artisan db:seed --class=TagSeeder
            php artisan db:seed --class=PostSeeder
            php artisan db:seed --class=RolesAndPermissionsSeeder

        # ── Factory tinker-ல் test செய்ய ─────────────────────────────
        php artisan tinker

    ------------------------------------------------------


--}}
