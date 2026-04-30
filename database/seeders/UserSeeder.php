<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Fixed users ─────────────────────────────

        User::factory()->create([
            'name'  => 'Lauren Tarshis',
            'email' => 'admin@gmail.com',
            'bio'   => 'Platform administrator',
        ]);

        User::factory()->create([
            'name'  => 'Sarah Chen',
            'email' => 'sarah@gmail.com',
        ]);

        // ── Random users ────────────────────────────
        User::factory(16)->create();

        $this->command->info('✅ Users created');
    }
}
