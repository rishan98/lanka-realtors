<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($staticUrls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        <changefreq>weekly</changefreq>
        <priority>{{ $url['priority'] }}</priority>
    </url>
@endforeach
@foreach($listings as $listing)
    <url>
        <loc>{{ route('listings.show', $listing) }}</loc>
        <lastmod>{{ $listing->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
@endforeach
@foreach($agents as $agent)
    <url>
        <loc>{{ route('agents.portfolio', $agent) }}</loc>
        <lastmod>{{ $agent->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
@endforeach
</urlset>
