<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroCarouselBanner;
use App\Support\CarouselBannerUpdater;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroCarouselController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function edit(): View
    {
        $banners = HeroCarouselBanner::query()
            ->forContext(HeroCarouselBanner::CONTEXT_HOMEPAGE)
            ->orderBy('position')
            ->get()
            ->keyBy('position');

        return view('admin.hero-carousel.edit', [
            'banners' => $banners,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        CarouselBannerUpdater::update($request, HeroCarouselBanner::CONTEXT_HOMEPAGE, requireMinimum: true);

        return redirect()
            ->route('admin.hero-carousel.edit')
            ->with('status', 'Homepage carousel banners saved.');
    }
}
