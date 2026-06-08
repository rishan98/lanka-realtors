<?php

namespace App\Http\Controllers;

use App\Models\City;
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
            ->with('user')
            ->latest()
            ->take(4)
            ->get();

        $topAgentsQuery = User::query()
            ->agents()
            ->approved()
            ->withPublishedListingCounts()
            ->orderedByRating();

        $heroTopAgents = (clone $topAgentsQuery)->take(3)->get();
        $topAgents = (clone $topAgentsQuery)->take(4)->get();

        $investListings = Listing::published()
            ->where('listing_kind', 'invest')
            ->with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('portal.home', [
            'featured' => $featured,
            'heroTopAgents' => $heroTopAgents,
            'topAgents' => $topAgents,
            'investListings' => $investListings,
            'districts' => City::districtsForForms(),
            'kinds' => config('listing.kinds'),
            'quick' => config('listing.homepage_quick'),
            'portal' => config('portal'),
            'budget_presets' => config('portal.budget_presets_lkr', []),
            'sqft_presets' => config('portal.sqft_presets', []),
        ]);
    }

    public function agentPortfolio(Request $request, User $agent)
    {
        if (! $agent->isAgent() || ! $agent->isApproved()) {
            abort(404);
        }

        $portfolioKinds = ['sale', 'rental', 'invest', 'wanted'];

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

        return view('portal.agent-portfolio', [
            'agent' => $agent,
            'listings' => $listings,
            'activeKind' => $activeKind,
            'kindCounts' => $kindCounts,
            'portfolioKinds' => $portfolioKinds,
            'seo' => Seo::agentPortfolio($agent, $activeKind),
        ]);
    }
}
