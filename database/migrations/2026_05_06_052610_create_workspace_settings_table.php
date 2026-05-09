<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('workspace_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            // Setting key, e.g. "audit_logs", "max_users"
            $table->string('key', 100);
            // Stored as JSON to support booleans, numbers, strings, arrays.
            // Nullable to support optional settings like max_users with no configured limit.
            $table->json('value')->nullable();
            $table->timestamps();

            $table->unique(['workspace_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_settings');
    }
};
