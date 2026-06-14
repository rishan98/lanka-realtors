<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiryReceived;
use App\Models\City;
use App\Models\ContactInquiry;
use App\Models\Listing;
use App\Models\User;
use App\Support\ListingRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SitePageController extends Controller
{
    public function projects()
    {
        return view('pages.projects');
    }

    public function wanted()
    {
        return redirect()->route('listings.index', ['kind' => 'wanted']);
    }

    public function findRealtor()
    {
        $agents = User::query()
            ->agents()
            ->approved()
            ->withPublishedListingCounts()
            ->orderedByRating()
            ->get();

        return view('pages.find-realtor', compact('agents'));
    }

    public function owners(Request $request)
    {
        $query = Listing::published()
            ->fromOwners()
            ->with(['user', 'cityRelation']);

        if ($request->filled('q')) {
            $term = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->q).'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('area', 'like', $term)
                    ->orWhereHas('cityRelation', function ($cityQuery) use ($term) {
                        $cityQuery->where('name', 'like', $term);
                    });
            });
        }

        if ($request->filled('city')) {
            $resolvedCity = City::resolveFilter((string) $request->city);
            if ($resolvedCity) {
                $query->whereIn('city_id', $resolvedCity->filterCityIds());
            } else {
                $city = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->city).'%';
                $query->where('city', 'like', $city);
            }
        }

        if ($request->filled('kind') && ListingRules::validKind($request->kind)) {
            $query->where('listing_kind', $request->kind);
        }

        $listings = $query->latest()->paginate(12)->withQueryString();
        $filters = $request->only(['q', 'city', 'kind']);

        return view('pages.owners', [
            'listings' => $listings,
            'kinds' => config('listing.kinds'),
            'districts' => City::districtsForForms(),
            'filters' => $filters,
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'portal' => config('portal'),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'company' => config('portal.company', []),
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $inquiry = ContactInquiry::create($validated);

        $notifyEmail = config('portal.company.email');
        if ($notifyEmail) {
            try {
                Mail::to($notifyEmail)->send(new ContactInquiryReceived($inquiry));
            } catch (\Throwable $e) {
                Log::warning('Contact form saved but notification email failed.', [
                    'inquiry_id' => $inquiry->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('contact')
            ->with('status', 'Thank you for your message. We will get back to you soon.');
    }
}
