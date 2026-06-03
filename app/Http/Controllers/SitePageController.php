<?php

namespace App\Http\Controllers;

use App\Models\User;

class SitePageController extends Controller
{
    public function investNow()
    {
        return view('pages.invest');
    }

    public function wanted()
    {
        return redirect()->route('listings.index', ['kind' => 'wanted']);
    }

    public function findRealtor()
    {
        $agents = User::query()
            ->agents()
            ->withCount([
                'listings as published_sale_count' => function ($q) {
                    $q->where('status', 'published')->where('listing_kind', 'sale');
                },
                'listings as published_rent_count' => function ($q) {
                    $q->where('status', 'published')->where('listing_kind', 'rental');
                },
            ])
            ->orderByDesc('is_preferred')
            ->orderByRaw('(COALESCE(published_sale_count, 0) + COALESCE(published_rent_count, 0)) DESC')
            ->get();

        return view('pages.find-realtor', compact('agents'));
    }

    public function grabMe()
    {
        return view('pages.grab-me');
    }
}
