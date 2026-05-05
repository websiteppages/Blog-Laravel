<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover_image',
        'status',
        'published_at',
        'is_featured',
        'reading_time',
        'views_count',
        'likes_count',
    ];

    // protected function casts(): array
    // {
    //     return [
    //         'status'       => PostStatus::class,
    //         'published_at' => 'datetime',
    //         'is_featured'  => 'boolean',
    //         'reading_time' => 'integer',
    //         'views_count'  => 'integer',
    //         'likes_count'  => 'integer',
    //     ];
    // }

    public function slugSource(): string
    {
        return 'title';
    }

    // ── Relations ──────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function category(): BelongsTo
    // {
    //     return $this->belongsTo(Category::class);
    // }

    // ✅ withTimestamps() இல்லை — post_tag-ல் timestamps column இல்லை
    // public function tags(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         Tag::class,
    //         'post_tag',
    //         'post_id',
    //         'tag_id'
    //     );
    // }

    // public function comments(): HasMany
    // {
    //     return $this->hasMany(Comment::class);
    // }

    // public function approvedComments(): HasMany
    // {
    //     return $this->hasMany(Comment::class)
    //                 ->where('is_approved', true)
    //                 ->whereNull('parent_id');
    // }

    // ── Post Likes ─────────────────────────────────────────

    // Primary relation — PostLike model use
    // public function postLikes(): HasMany
    // {
    //     return $this->hasMany(PostLike::class);
    // }

    // ✅ Alias — likes() → postLikes() shortcut
    // show.blade.php-ல் $post->likes() use செய்ய
    // public function likes(): HasMany
    // {
    //     return $this->hasMany(PostLike::class);
    // }

    // ── Bookmarks ──────────────────────────────────────────

    // public function bookmarks(): HasMany
    // {
    //     return $this->hasMany(Bookmark::class);
    // }

    // ── Scopes ─────────────────────────────────────────────

    // public function scopePublished(Builder $query): Builder
    // {
    //     return $query->where('status', PostStatus::Published)
    //                  ->whereNotNull('published_at')
    //                  ->where('published_at', '<=', now());
    // }

    // public function scopeDraft(Builder $query): Builder
    // {
    //     return $query->where('status', PostStatus::Draft);
    // }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->whereFullText(['title', 'content'], $term);
    }

    // ── Accessors ──────────────────────────────────────────

    public function getExcerptTextAttribute(): string
    {
        return $this->excerpt
            ?? \Str::limit(strip_tags($this->content ?? ''), 160);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image
            ? \Storage::disk('public')->url($this->cover_image)
            : null;
    }

    // ── Helper Methods ─────────────────────────────────────

    public function isLikedBy(?int $userId): bool
    {
        if (!$userId) return false;

        return $this->likes()
                    ->where('user_id', $userId)
                    ->exists();
    }

    public function isBookmarkedBy(?int $userId): bool
    {
        if (!$userId) return false;

        return $this->bookmarks()
                    ->where('user_id', $userId)
                    ->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // public function isPublished(): bool
    // {
    //     return $this->status === PostStatus::Published;
    // }

    // public function isDraft(): bool
    // {
    //     return $this->status === PostStatus::Draft;
    // }

    public function calculateReadingTime(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        return (int) max(1, ceil($wordCount / 200));
    }

    // Inside Post model — relations section

    // public function reports(): HasMany
    // {
    //     return $this->hasMany(PostReport::class);
    // }

    // public function pendingReports(): HasMany
    // {
    //     return $this->hasMany(PostReport::class)
    //                 ->where('status', 'pending');
    // }

    public function isReportedBy(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->reports()
                    ->where('user_id', $userId)
                    ->exists();
    }
}
