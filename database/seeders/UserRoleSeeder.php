<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            'admin@gmail.com'  => UserRole::Owner->value,
            'sarah@gmail.com'  => UserRole::Admin->value,
        ];

        foreach ($assignments as $email => $role) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->syncRoles([$role]);
                $this->command->line("→ {$email} : {$role}");
            }
        }

        $this->command->info('✅ Roles assigned to users');
    }
}
