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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            // User can be null for system actions
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Who performed the action (user / system / api)
            $table->enum('actor_type', ['user', 'system', 'api'])->default('user');
            $table->unsignedBigInteger('actor_id')->nullable();

            // Action verb: create, update, delete, login, logout, etc.
            $table->string('action', 50);
            // Dot-notation event: post.created, role.updated, user.login
            $table->string('event', 100);

            // Polymorphic reference to the affected model
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();

            // State snapshots for diff/rollback capability
            $table->json('before')->nullable();
            $table->json('after')->nullable();

            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->string('url', 2000)->nullable();
             $table->json('context')->nullable();

            $table->timestamps();

            $table->index(['workspace_id', 'user_id']);
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');

            // Index for filtering view events specifically
            $table->index(['workspace_id', 'action', 'created_at'], 'audit_workspace_action_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
