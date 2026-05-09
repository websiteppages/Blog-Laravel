<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Response;

class SitemapService
{
    public function __construct(
        // protected PostRepositoryInterface     $postRepository,
        // protected CategoryRepositoryInterface $categoryRepository,
        // protected TagRepositoryInterface      $tagRepository,
        protected UserRepositoryInterface     $userRepository,
    ) {}

    public function generate(): \Illuminate\Http\Response
    {
        $posts      = $this->postRepository->getPublished([], 1000);
        $categories = $this->categoryRepository->all();
        $tags       = $this->tagRepository->all();

        $xml = $this->buildXml($posts, $categories, $tags);

        return Response::make($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    public function generateIndex(): \Illuminate\Http\Response
    {
        $sitemaps = [
            ['loc' => url('/sitemap-posts.xml'),      'lastmod' => now()->toAtomString()],
            ['loc' => url('/sitemap-categories.xml'), 'lastmod' => now()->toAtomString()],
            ['loc' => url('/sitemap-tags.xml'),       'lastmod' => now()->toAtomString()],
        ];

        $xml = view('sitemap.index', compact('sitemaps'))->render();

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function generatePosts(): \Illuminate\Http\Response
    {
        $posts = $this->postRepository->getPublished([], 500);
        $xml   = view('sitemap.posts', compact('posts'))->render();

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function generateCategories(): \Illuminate\Http\Response
    {
        $categories = $this->categoryRepository->all();
        $xml        = view('sitemap.categories', compact('categories'))->render();

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function generateTags(): \Illuminate\Http\Response
    {
        $tags = $this->tagRepository->all();
        $xml  = view('sitemap.tags', compact('tags'))->render();

        return Response::make($xml, 200, ['Content-Type' => 'application/xml']);
    }

    private function buildXml($posts, $categories, $tags): string
    {
        return view('sitemap.main', compact(
            'posts', 'categories', 'tags'
        ))->render();
    }
}
