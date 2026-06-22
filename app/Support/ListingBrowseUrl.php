<?php

namespace App\Support;

use App\Models\City;
use Illuminate\Support\Str;

class ListingBrowseUrl
{
    /**
     * Build a crawl-friendly browse URL from listing filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public static function forListings(array $filters): string
    {
        $filters = array_filter($filters, fn ($value) => $value !== null && $value !== '');

        if (isset($filters['quick']) || isset($filters['q']) || isset($filters['price_min']) || isset($filters['price_max']) || isset($filters['area_min']) || isset($filters['area_max']) || isset($filters['bhk'])) {
            return route('listings.index', $filters);
        }

        $kind = $filters['kind'] ?? null;
        $subtype = $filters['subtype'] ?? null;
        $city = $filters['city'] ?? null;

        if ($kind && in_array($kind, ['sale', 'rental', 'projects', 'wanted'], true)) {
            if ($subtype && $city) {
                return route('listings.browse', [
                    'kind' => $kind,
                    'subtype' => $subtype,
                    'citySlug' => static::citySlug($city),
                ]);
            }

            if ($subtype) {
                return route('listings.browse-kind-subtype', [
                    'kind' => $kind,
                    'subtype' => $subtype,
                ]);
            }

            return route('listings.browse-kind', ['kind' => $kind]);
        }

        return route('listings.index', $filters);
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    public static function forLands(array $filters): string
    {
        $filters = array_filter($filters, fn ($value) => $value !== null && $value !== '');

        if (isset($filters['q']) || isset($filters['price_min']) || isset($filters['price_max']) || isset($filters['area_min']) || isset($filters['area_max']) || isset($filters['bhk'])) {
            return route('lands.index', $filters);
        }

        $kind = $filters['kind'] ?? null;
        $city = $filters['city'] ?? null;

        if ($city && $kind && in_array($kind, ['sale', 'rental'], true)) {
            return route('lands.browse-kind-city', [
                'kind' => $kind,
                'citySlug' => static::citySlug($city),
            ]);
        }

        if ($city) {
            return route('lands.browse-city', ['citySlug' => static::citySlug($city)]);
        }

        if ($kind && in_array($kind, ['sale', 'rental'], true)) {
            return route('lands.browse-kind', ['kind' => $kind]);
        }

        return route('lands.index', $filters);
    }

    /**
     * @param  array<string, mixed>  $link
     */
    public static function fromFooterLink(array $link): string
    {
        $route = $link['route'] ?? 'listings.index';
        $params = $link['params'] ?? [];

        if ($route === 'lands.index') {
            return static::forLands($params);
        }

        return static::forListings($params);
    }

    public static function citySlug(string $city): string
    {
        $resolved = City::resolveFilter($city);

        return $resolved?->slug ?? Str::slug($city);
    }
}
