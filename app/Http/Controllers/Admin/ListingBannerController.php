<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroCarouselBanner;
use App\Support\CarouselBannerUpdater;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListingBannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(): View
    {
        $sections = HeroCarouselBanner::bannerSections();

        $activeBannerKinds = HeroCarouselBanner::query()
            ->whereIn('context', HeroCarouselBanner::listingBannerContexts())
            ->where('position', 1)
            ->pluck('context');

        return view('admin.listing-banners.index', [
            'sections' => $sections,
            'activeBannerKinds' => $activeBannerKinds,
        ]);
    }

    public function edit(string $kind): View
    {
        abort_unless(HeroCarouselBanner::isBannerContext($kind), 404);

        $banners = HeroCarouselBanner::query()
            ->forContext($kind)
            ->orderBy('position')
            ->get()
            ->keyBy('position');

        CarouselBannerUpdater::deleteExtraBanners($kind, HeroCarouselBanner::LISTING_MAX_BANNERS);

        $banners = HeroCarouselBanner::query()
            ->forContext($kind)
            ->orderBy('position')
            ->get()
            ->keyBy('position');

        $kindMeta = HeroCarouselBanner::bannerSections()[$kind] ?? [];

        return view('admin.listing-banners.edit', [
            'kind' => $kind,
            'kindMeta' => $kindMeta,
            'banners' => $banners,
        ]);
    }

    public function update(Request $request, string $kind): RedirectResponse
    {
        abort_unless(HeroCarouselBanner::isBannerContext($kind), 404);

        CarouselBannerUpdater::update(
            $request,
            $kind,
            requireMinimum: false,
            maxBanners: HeroCarouselBanner::LISTING_MAX_BANNERS
        );

        $label = HeroCarouselBanner::bannerSectionLabel($kind);

        return redirect()
            ->route('admin.listing-banners.edit', $kind)
            ->with('status', $label.' listing banner saved.');
    }
}
