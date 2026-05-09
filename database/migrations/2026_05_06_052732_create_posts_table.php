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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();

            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->foreignId("category_id")->nullable()->constrained()->nullOnDelete();

            $table->string("title");
            $table->string("slug")->unique();

            $table->text("excerpt")->nullable();
            $table->longText("content");

            $table->string("cover_image")->nullable();

            $table->string("status")->default("draft");
            $table->timestamp("published_at")->nullable();

            $table->boolean("is_featured")->default(false);
            $table->unsignedSmallInteger("reading_time")->default(1);

            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);

            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index(["status","published_at"]);
                //WHERE status = 'published' AND published_at <= NOW() -👉 Blog publish filter fast ஆகும்

            $table->index("user_id"); // 👉 Author posts load fast
            $table->index("category_id"); // 👉 Category based filtering fast
            $table->index(["category_id","status"]);

            // Search
            $table->fullText(["title", "content"]);
            // FULLTEXT = Search engine மாதிரி search (Google style) - மிகவும் fast-smart search (relevance ranking) -  best match first வரும்

             $table->unique(['workspace_id', 'slug']);
            // Optimized for listing published posts by workspace
            $table->index(['workspace_id', 'status', 'published_at']);
        });

        Schema::create("post_tag", function (Blueprint $table) {
            $table->foreignId("post_id")->constrained()->cascadeOnDelete();
            $table->foreignId("tag_id")->constrained()->cascadeOnDelete();
            $table->primary(["post_id","tag_id"]);

            //(post_id, tag_id) must be UNIQUE - No duplicate

            //(1,2) already இருந்தா
            //மீண்டும் (1,2) insert ❌

              //(2,2) → different pair → ✅

              //1 2 ✅
              //1 4 ✅
              //1 2 ❌
              //2 2 ✅

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('posts');
    }
};
