<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\View\View;

class LocateController extends Controller
{
    public function __invoke(): View
    {
        $mapListings = Listing::query()
            ->published()
            ->whereIn('listing_kind', ['sale', 'rental', 'invest'])
            ->with('cityRelation')
            ->get()
            ->map(function (Listing $listing) {
                $lat = $listing->latitude ?? $listing->cityRelation?->latitude;
                $lng = $listing->longitude ?? $listing->cityRelation?->longitude;

                if ($lat === null || $lng === null) {
                    return null;
                }

                return [
                    'id' => $listing->id,
                    'title' => $listing->title,
                    'kind' => $listing->listing_kind,
                    'kind_label' => $listing->kindLabel(),
                    'lat' => (float) $lat,
                    'lng' => (float) $lng,
                    'city' => $listing->city,
                    'price' => $listing->formattedPriceDisplay(),
                    'url' => route('listings.show', $listing),
                ];
            })
            ->filter()
            ->values();

        return view('pages.locate', [
            'mapListings' => $mapListings,
        ]);
    }
}
