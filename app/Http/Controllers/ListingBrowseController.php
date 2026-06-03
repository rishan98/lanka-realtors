<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Support\ListingRules;
use Illuminate\Http\Request;

class ListingBrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Listing::published()->with('user');

        if ($request->filled('q')) {
            $term = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->q).'%';
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('area', 'like', $term);
            });
        }

        if ($request->filled('city')) {
            $city = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->city).'%';
            $query->where('city', 'like', $city);
        }

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

        return view('listings.index', [
            'listings' => $listings,
            'kinds' => config('listing.kinds'),
            'filters' => $request->only([
                'q', 'city', 'kind', 'subtype', 'quick',
                'price_min', 'price_max', 'area_min', 'area_max', 'bhk',
            ]),
            'budget_presets' => config('portal.budget_presets_lkr', []),
            'sqft_presets' => config('portal.sqft_presets', []),
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

        $similarQuery = Listing::published()
            ->where('id', '!=', $listing->id)
            ->where('listing_kind', $listing->listing_kind);

        if ($listing->city) {
            $similarQuery->where('city', $listing->city);
        }

        $similarListings = $similarQuery->latest()->take(4)->get();

        return view('listings.show', [
            'listing' => $listing,
            'similarListings' => $similarListings,
        ]);
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
