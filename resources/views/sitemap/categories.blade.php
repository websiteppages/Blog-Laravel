<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($categories as $category)
    <url>
        <loc>{{ url('/articles?category_id=' . $category->id) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
        <lastmod>{{ $category->updated_at->toAtomString() }}</lastmod>
    </url>
    @endforeach
</urlset>
