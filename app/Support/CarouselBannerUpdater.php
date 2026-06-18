<?php

namespace App\Support;

use App\Models\HeroCarouselBanner;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CarouselBannerUpdater
{
    public static function update(
        Request $request,
        string $context,
        bool $requireMinimum = true,
        int $maxBanners = HeroCarouselBanner::MAX_BANNERS
    ): void {
        $request->validate([
            'banners' => 'required|array',
            'banners.*.alt' => 'nullable|string|max:255',
            'banners.*.url' => 'nullable|url|max:500',
            'banners.*.image' => 'nullable|image|max:4096',
            'banners.*.remove' => 'nullable|boolean',
        ]);

        $existing = HeroCarouselBanner::query()
            ->where('context', $context)
            ->orderBy('position')
            ->get()
            ->keyBy('position');

        $intendedCount = 0;

        for ($position = 1; $position <= $maxBanners; $position++) {
            $slot = $request->input("banners.{$position}", []);
            $banner = $existing->get($position);
            $remove = filter_var($slot['remove'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $hasUpload = $request->hasFile("banners.{$position}.image");

            if ($remove) {
                continue;
            }

            if ($hasUpload || $banner) {
                $intendedCount++;
            }
        }

        if ($requireMinimum && $intendedCount < HeroCarouselBanner::MIN_BANNERS) {
            throw ValidationException::withMessages([
                'banners' => 'Keep at least one banner image.',
            ]);
        }

        if ($intendedCount > $maxBanners) {
            throw ValidationException::withMessages([
                'banners' => 'You can upload a maximum of '.$maxBanners.' banner'.($maxBanners === 1 ? '' : 's').'.',
            ]);
        }

        for ($position = 1; $position <= $maxBanners; $position++) {
            $slot = $request->input("banners.{$position}", []);
            $banner = $existing->get($position);
            $remove = filter_var($slot['remove'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if ($remove) {
                if ($banner) {
                    $banner->delete();
                }

                continue;
            }

            if ($request->hasFile("banners.{$position}.image")) {
                $imagePath = $request->file("banners.{$position}.image")
                    ->store('hero-banners/'.$context, 'public');

                HeroCarouselBanner::updateOrCreate(
                    ['context' => $context, 'position' => $position],
                    [
                        'image_path' => $imagePath,
                        'alt' => $slot['alt'] ?? null,
                        'url' => $slot['url'] ?? null,
                    ]
                );

                continue;
            }

            if ($banner) {
                $banner->update([
                    'alt' => $slot['alt'] ?? null,
                    'url' => $slot['url'] ?? null,
                ]);
            }
        }

        static::deleteExtraBanners($context, $maxBanners);
    }

    public static function deleteExtraBanners(string $context, int $maxBanners): void
    {
        HeroCarouselBanner::query()
            ->where('context', $context)
            ->where('position', '>', $maxBanners)
            ->get()
            ->each->delete();
    }
}
