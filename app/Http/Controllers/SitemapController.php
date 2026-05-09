<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use App\Models\Setting;

class SitemapController extends Controller
{
    public function __construct(
        protected SitemapService $sitemapService
    ) {}

    public function index()
    {
        if (!Setting::isEnabled('seo_sitemap')) {
            abort(404);
        }

        return $this->sitemapService->generateIndex();
    }

    public function posts()
    {
        return $this->sitemapService->generatePosts();
    }

    public function categories()
    {
        return $this->sitemapService->generateCategories();
    }

    public function tags()
    {
        return $this->sitemapService->generateTags();
    }
}
