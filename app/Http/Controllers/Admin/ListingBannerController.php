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
        $kinds = collect(HeroCarouselBanner::LISTING_KINDS)
            ->mapWithKeys(fn (string $kind) => [$kind => config('listing.kinds.'.$kind)])
            ->filter();

        $activeBannerKinds = HeroCarouselBanner::query()
            ->whereIn('context', HeroCarouselBanner::LISTING_KINDS)
            ->where('position', 1)
            ->pluck('context');

        return view('admin.listing-banners.index', [
            'kinds' => $kinds,
            'activeBannerKinds' => $activeBannerKinds,
        ]);
    }

    public function edit(string $kind): View
    {
        abort_unless(HeroCarouselBanner::isListingKind($kind), 404);

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

        $kindMeta = config('listing.kinds.'.$kind, []);

        return view('admin.listing-banners.edit', [
            'kind' => $kind,
            'kindMeta' => $kindMeta,
            'banners' => $banners,
        ]);
    }

    public function update(Request $request, string $kind): RedirectResponse
    {
        abort_unless(HeroCarouselBanner::isListingKind($kind), 404);

        CarouselBannerUpdater::update(
            $request,
            $kind,
            requireMinimum: false,
            maxBanners: HeroCarouselBanner::LISTING_MAX_BANNERS
        );

        $label = config('listing.kinds.'.$kind.'.nav_label', ucfirst($kind));

        return redirect()
            ->route('admin.listing-banners.edit', $kind)
            ->with('status', $label.' listing banner saved.');
    }
}
