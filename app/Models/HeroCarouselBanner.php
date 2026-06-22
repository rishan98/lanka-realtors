<?php

namespace App\Models;

use App\Support\StoredFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HeroCarouselBanner extends Model
{
    public const CONTEXT_HOMEPAGE = 'homepage';

    public const LISTING_KINDS = ['sale', 'rental', 'projects', 'wanted'];

    public const PAGE_BANNER_CONTEXTS = ['lands', 'find-realtor', 'owners'];

    public const MIN_BANNERS = 1;

    public const MAX_BANNERS = 3;

    public const LISTING_MAX_BANNERS = 1;

    protected $fillable = [
        'context',
        'position',
        'image_path',
        'alt',
        'url',
    ];

    protected static function booted(): void
    {
        static::updating(function (HeroCarouselBanner $banner) {
            StoredFile::deleteMany(StoredFile::originalPathsForDirtyFields($banner, ['image_path']));
        });

        static::deleting(function (HeroCarouselBanner $banner) {
            StoredFile::delete($banner->image_path);
        });
    }

    public function imageUrl(): string
    {
        return asset('storage/'.$this->image_path);
    }

    public function scopeForContext(Builder $query, string $context): Builder
    {
        return $query->where('context', $context);
    }

    public static function isListingKind(string $kind): bool
    {
        return in_array($kind, self::LISTING_KINDS, true);
    }

    /**
     * @return array<int, string>
     */
    public static function listingBannerContexts(): array
    {
        return array_merge(self::LISTING_KINDS, self::PAGE_BANNER_CONTEXTS);
    }

    public static function isBannerContext(string $context): bool
    {
        return in_array($context, self::listingBannerContexts(), true);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function bannerSections(): array
    {
        $sections = [];

        foreach (self::LISTING_KINDS as $kind) {
            $sections[$kind] = array_merge(config('listing.kinds.'.$kind, []), [
                'preview_route' => 'listings.index',
                'preview_params' => ['kind' => $kind],
            ]);
        }

        foreach (config('portal.page_banners', []) as $context => $meta) {
            $sections[$context] = $meta;
        }

        return $sections;
    }

    public static function bannerSectionLabel(string $context): string
    {
        $sections = self::bannerSections();

        return $sections[$context]['nav_label']
            ?? $sections[$context]['label']
            ?? ucfirst(str_replace('-', ' ', $context));
    }

    /**
     * @return array<int, array{image: string, alt: string|null, url: string|null}>
     */
    public static function slidesForPortal(): array
    {
        return static::slidesForContext(self::CONTEXT_HOMEPAGE, config('portal.hero_carousel', []));
    }

    /**
     * @return array<int, array{image: string, alt: string|null, url: string|null}>
     */
    public static function slidesForListingKind(?string $kind): array
    {
        return self::slidesForBannerContext($kind ?? '');
    }

    /**
     * @return array<int, array{image: string, alt: string|null, url: string|null}>
     */
    public static function slidesForBannerContext(string $context): array
    {
        if (! self::isBannerContext($context)) {
            return [];
        }

        return static::slidesForContext($context, [], self::LISTING_MAX_BANNERS);
    }

    /**
     * @return array<int, array{image: string, alt: string|null, url: string|null}>
     */
    public static function slidesForContext(string $context, array $fallback = [], ?int $limit = null): array
    {
        $query = static::query()
            ->forContext($context)
            ->orderBy('position');

        if ($limit !== null) {
            $query->limit($limit);
        }

        $banners = $query->get();

        if ($banners->isEmpty()) {
            return $fallback;
        }

        return $banners->map(fn (self $banner) => [
            'image' => 'storage/'.$banner->image_path,
            'alt' => $banner->alt,
            'url' => self::normalizeUrl($banner->url),
        ])->all();
    }

    public static function normalizeUrl(?string $url): ?string
    {
        $url = trim((string) $url);

        if (
            $url === ''
            || $url === '#'
            || str_starts_with($url, '#')
            || str_starts_with(strtolower($url), 'javascript:')
        ) {
            return null;
        }

        return $url;
    }
}
