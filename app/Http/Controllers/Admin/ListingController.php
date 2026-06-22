<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = Listing::query()
            ->with('user')
            ->latest();

        if ($status === 'published') {
            $query->published();
        } elseif ($status === 'draft') {
            $query->where('status', 'draft');
        }

        if ($request->filled('q')) {
            $term = '%'.str_replace(['%', '_'], ['\\%', '\\_'], $request->q).'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'like', $term)
                            ->orWhere('email', 'like', $term);
                    });
            });
        }

        $listings = $query->paginate(20)->withQueryString();

        return view('admin.listings.index', [
            'listings' => $listings,
            'filters' => [
                'status' => $status,
                'q' => $request->q,
            ],
            'counts' => [
                'total' => Listing::count(),
                'published' => Listing::published()->count(),
                'draft' => Listing::where('status', 'draft')->count(),
            ],
        ]);
    }

    public function activate(Listing $listing): RedirectResponse
    {
        if ($listing->isPublished()) {
            return back()->with('status', 'That ad is already active.');
        }

        $listing->update(['status' => 'published']);

        return back()->with('status', 'Ad activated and visible on the site.');
    }

    public function deactivate(Listing $listing): RedirectResponse
    {
        if ($listing->isDraft()) {
            return back()->with('status', 'That ad is already deactivated.');
        }

        $listing->update(['status' => 'draft']);

        return back()->with('status', 'Ad deactivated and hidden from the site.');
    }
}
