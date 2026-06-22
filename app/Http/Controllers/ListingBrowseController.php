<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\HeroCarouselBanner;
use App\Models\Listing;
use App\Support\ListingBrowseUrl;
use App\Support\ListingRules;
use App\Support\Seo;
use Illuminate\Http\Request;

class ListingBrowseController extends Controller
{
    public function index(Request $request)
    {
        $this->mergeBrowseRouteDefaults($request);

        if ($request->input('kind') === 'invest') {
            return redirect()->route('listings.index', array_merge(
                $request->except('kind'),
                ['kind' => 'projects']
            ));
        }

        if ($request->input('quick') === 'invest') {
            return redirect()->route('listings.index', array_merge(
                $request->except('quick'),
                ['quick' => 'projects']
            ));
        }

        if ($request->input('subtype') === 'land' || $request->input('quick') === 'plot') {
            return redirect()->route('lands.index', array_filter($request->only([
                'q', 'city', 'kind', 'price_min', 'price_max', 'area_min', 'area_max', 'bhk',
            ])));
        }

        $query = Listing::published()
            ->fromAgents()
            ->with(['user', 'cityRelation']);

        $this->applyKeywordFilter($query, $request);
        $this->applyCityFilter($query, $request);

        if ($request->filled('quick')) {
            $this->applyQuick($query, (string) $request->input('quick'));
        } else {
            if ($request->filled('kind') && ListingRules::validKind($request->kind)) {
                $query->where('listing_kind', $request->kind);
            }

            if ($request->filled('subtype')) {
                $query->where('property_subtype', $request->subtype);
            }
        }

        $this->applyAdvancedFilters($query, $request);

        $listings = $query->latest()->paginate(12)->withQueryString();

        $filters = $request->only([
            'q', 'city', 'kind', 'subtype', 'quick',
            'price_min', 'price_max', 'area_min', 'area_max', 'bhk',
        ]);
        $kinds = config('listing.kinds');
        $districts = City::districtsForForms();
        $activeKind = $filters['kind'] ?? null;
        $categoryCarousel = HeroCarouselBanner::slidesForListingKind($activeKind);

        return view('listings.index', [
            'listings' => $listings,
            'kinds' => $kinds,
            'districts' => $districts,
            'filters' => $filters,
            'activeKind' => $activeKind,
            'categoryCarousel' => $categoryCarousel,
            'budget_presets' => config('portal.budget_presets_lkr', []),
            'sqft_presets' => config('portal.sqft_presets', []),
            'seo' => Seo::listingIndex($filters, $kinds),
            'browseContext' => null,
            'formAction' => route('listings.index'),
        ]);
    }

    public function lands(Request $request)
    {
        $this->mergeBrowseRouteDefaults($request);

        $query = Listing::published()
            ->fromAgents()
            ->with(['user', 'cityRelation'])
            ->where('property_subtype', 'land')
            ->whereIn('listing_kind', ['sale', 'rental']);

        $this->applyKeywordFilter($query, $request);
        $this->applyCityFilter($query, $request);

        if ($request->filled('kind') && in_array($request->kind, ['sale', 'rental'], true)) {
            $query->where('listing_kind', $request->kind);
        }

        $this->applyAdvancedFilters($query, $request);

        $listings = $query->latest()->paginate(12)->withQueryString();

        $filters = array_merge(
            $request->only(['q', 'city', 'kind', 'price_min', 'price_max', 'area_min', 'area_max', 'bhk']),
            ['subtype' => 'land']
        );
        $kinds = config('listing.kinds');
        $districts = City::districtsForForms();
        $activeKind = $filters['kind'] ?? null;

        return view('listings.index', [
            'listings' => $listings,
            'kinds' => $kinds,
            'districts' => $districts,
            'filters' => $filters,
            'activeKind' => $activeKind,
            'categoryCarousel' => HeroCarouselBanner::slidesForBannerContext('lands'),
            'budget_presets' => config('portal.budget_presets_lkr', []),
            'sqft_presets' => config('portal.sqft_presets', []),
            'seo' => Seo::landsIndex($filters),
            'browseContext' => 'lands',
            'formAction' => route('lands.index'),
        ]);
    }

    public function show(Listing $listing)
    {
        if ($listing->status !== 'published') {
            abort(404);
        }

        $listing->load(['user' => function ($query) {
            $query->withCount([
                'listings as published_sale_count' => function ($q) {
                    $q->where('status', 'published')->where('listing_kind', 'sale');
                },
                'listings as published_rent_count' => function ($q) {
                    $q->where('status', 'published')->where('listing_kind', 'rental');
                },
            ]);
        }]);

        $listing->recordView();

        $similarListings = Listing::similarTo($listing, 4);

        return view('listings.show', [
            'listing' => $listing,
            'similarListings' => $similarListings,
            'seo' => Seo::listingShow($listing),
        ]);
    }

    protected function mergeBrowseRouteDefaults(Request $request): void
    {
        $route = $request->route();
        if ($route === null) {
            return;
        }

        foreach (['kind', 'subtype'] as $param) {
            $value = $route->parameter($param);
            if (is_string($value) && $value !== '' && ! $request->filled($param)) {
                $request->merge([$param => $value]);
            }
        }

        $citySlug = $route->parameter('citySlug');
        if (is_string($citySlug) && $citySlug !== '' && ! $request->filled('city')) {
            $resolvedCity = City::resolveFilter($citySlug);
            $request->merge(['city' => $resolvedCity?->name ?? $citySlug]);
        }
    }

    protected function applyKeywordFilter($query, Request $request): void
    {
        if (! $request->filled('q')) {
            return;
        }

        $term = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->q).'%';
        $query->where(function ($q) use ($term) {
            $q->where('title', 'like', $term)
                ->orWhere('description', 'like', $term)
                ->orWhere('city', 'like', $term)
                ->orWhere('area', 'like', $term)
                ->orWhereHas('cityRelation', function ($cityQuery) use ($term) {
                    $cityQuery->where('name', 'like', $term);
                });
        });
    }

    protected function applyCityFilter($query, Request $request): void
    {
        if (! $request->filled('city')) {
            return;
        }

        $resolvedCity = City::resolveFilter((string) $request->city);
        if ($resolvedCity) {
            $query->whereIn('city_id', $resolvedCity->filterCityIds());
        } else {
            $city = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->city).'%';
            $query->where('city', 'like', $city);
        }
    }

    protected function applyAdvancedFilters($query, Request $request): void
    {
        if ($request->filled('price_min')) {
            $query->where('price', '>=', (float) $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', (float) $request->input('price_max'));
        }
        if ($request->filled('area_min')) {
            $query->where('built_area_sqft', '>=', (int) $request->input('area_min'));
        }
        if ($request->filled('area_max')) {
            $query->where('built_area_sqft', '<=', (int) $request->input('area_max'));
        }

        if ($request->filled('bhk')) {
            $v = (string) $request->input('bhk');
            if ($v === '5' || $v === '5plus') {
                $query->where('bedrooms', '>=', 5);
            } elseif (is_numeric($v)) {
                $query->where('bedrooms', (int) $v);
            }
        }
    }

    protected function applyQuick($query, string $quick): void
    {
        $map = config('listing.homepage_quick.'.$quick);
        if (! is_array($map)) {
            return;
        }

        if (isset($map['kind'])) {
            $query->where('listing_kind', $map['kind']);
        }

        if (isset($map['subtype'])) {
            $query->where('property_subtype', $map['subtype']);
            if ($quick === 'commercial') {
                $query->whereIn('listing_kind', ['sale', 'rental']);
            }
            if ($quick === 'apartments') {
                $query->whereIn('listing_kind', ['sale', 'rental']);
            }
        }
    }
}
