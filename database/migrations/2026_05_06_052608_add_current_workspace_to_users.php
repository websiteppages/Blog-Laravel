<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This migration modifies the base users table created by Laravel's default migration.
 * We add current_workspace_id to enable workspace switching per user.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_workspace_id')
                ->nullable()
                ->after('id')
                ->constrained('workspaces')
                ->nullOnDelete();

            $table->index('current_workspace_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_workspace_id']);
            $table->dropIndex(['current_workspace_id']);
            $table->dropColumn('current_workspace_id');
        });
    }
};
