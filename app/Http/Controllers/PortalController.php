<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\HeroCarouselBanner;
use App\Models\Listing;
use App\Models\User;
use App\Support\ListingRules;
use App\Support\Seo;
use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index()
    {
        $featured = Listing::published()
            ->fromAgents()
            ->with('user')
            ->orderByDesc('view_count')
            ->latest()
            ->take(4)
            ->get();

        $topAgentsQuery = User::query()
            ->agents()
            ->approved()
            ->withPublishedListingCounts()
            ->orderedByRating();

        $heroTopAgents = (clone $topAgentsQuery)->take(3)->get();
        $topAgents = (clone $topAgentsQuery)
            ->whereNotIn('id', $heroTopAgents->pluck('id'))
            ->take(4)
            ->get();

        $projectListings = Listing::published()
            ->fromAgents()
            ->where('listing_kind', 'projects')
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('portal.home', [
            'featured' => $featured,
            'heroTopAgents' => $heroTopAgents,
            'topAgents' => $topAgents,
            'projectListings' => $projectListings,
            'districts' => City::districtsForForms(),
            'kinds' => config('listing.kinds'),
            'quick' => config('listing.homepage_quick'),
            'portal' => config('portal'),
            'heroCarousel' => HeroCarouselBanner::slidesForPortal(),
            'budget_presets' => config('portal.budget_presets_lkr', []),
            'sqft_presets' => config('portal.sqft_presets', []),
        ]);
    }

    public function agentPortfolio(Request $request, User $agent)
    {
        if (! $agent->isAgent() || ! $agent->isApproved()) {
            abort(404);
        }

        $portfolioKinds = ['sale', 'rental', 'projects', 'wanted'];

        $activeKind = $request->filled('kind') && ListingRules::validKind($request->kind)
            && in_array($request->kind, $portfolioKinds, true)
            ? $request->kind
            : null;

        $agent->loadCount([
            'listings as published_sale_count' => function ($q) {
                $q->where('status', 'published')->where('listing_kind', 'sale');
            },
            'listings as published_rent_count' => function ($q) {
                $q->where('status', 'published')->where('listing_kind', 'rental');
            },
        ]);

        $publishedBase = Listing::published()->where('user_id', $agent->id);

        $kindCounts = [];
        foreach ($portfolioKinds as $kind) {
            $kindCounts[$kind] = (clone $publishedBase)->where('listing_kind', $kind)->count();
        }

        $listingsQuery = clone $publishedBase;
        if ($activeKind) {
            $listingsQuery->where('listing_kind', $activeKind);
        }

        $listings = $listingsQuery->latest()->paginate(9)->withQueryString();

        $reviews = $agent->reviews()->approved()->latest()->get();
        $reviewCount = $agent->approvedReviewCount();
        $averageReviewRating = $agent->averageReviewRating();
        $visibleReviews = $reviews->take(3);

        return view('portal.agent-portfolio', [
            'agent' => $agent,
            'listings' => $listings,
            'activeKind' => $activeKind,
            'kindCounts' => $kindCounts,
            'portfolioKinds' => $portfolioKinds,
            'reviews' => $reviews,
            'visibleReviews' => $visibleReviews,
            'reviewCount' => $reviewCount,
            'averageReviewRating' => $averageReviewRating,
            'seo' => Seo::agentPortfolio($agent, $activeKind),
        ]);
    }
}
