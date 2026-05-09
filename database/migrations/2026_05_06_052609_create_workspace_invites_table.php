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
        Schema::create('workspace_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->string('email');
            $table->foreignId('workspace_role_id')->constrained('workspace_roles')->restrictOnDelete();
            // Secure random token for invite link
            $table->string('token', 64)->unique();
            $table->enum('status', ['pending', 'accepted', 'expired', 'removed'])->default('pending');
            // Invites expire after a configurable period (default 7 days)
            $table->timestamp('expires_at');
            $table->foreignId('invited_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['email', 'workspace_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspace_invites');
    }
};
