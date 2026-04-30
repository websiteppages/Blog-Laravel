<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\Permission as Perm;
use App\Enums\UserRole;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $map = [

            UserRole::Owner->value => Perm::values(),

            UserRole::Admin->value => [
                Perm::AccessDashboard->value,
                Perm::ViewAnalytics->value,
            ],



        ];

        foreach ($map as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($permissions);

            $this->command->info("✓ {$roleName} synced");
        }
    }
}
