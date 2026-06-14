<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HeroCarouselBanner extends Model
{
    public const CONTEXT_HOMEPAGE = 'homepage';

    public const LISTING_KINDS = ['sale', 'rental', 'projects', 'wanted'];

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
        if (! $kind || ! self::isListingKind($kind)) {
            return [];
        }

        return static::slidesForContext($kind, [], self::LISTING_MAX_BANNERS);
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
            'url' => $banner->url,
        ])->all();
    }
}
