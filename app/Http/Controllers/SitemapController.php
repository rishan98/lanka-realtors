<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticUrls = [
            ['loc' => route('portal.home'), 'priority' => '1.0'],
            ['loc' => route('listings.index'), 'priority' => '0.9'],
            ['loc' => route('find-realtor'), 'priority' => '0.8'],
            ['loc' => route('projects'), 'priority' => '0.7'],
            ['loc' => route('locate'), 'priority' => '0.7'],
            ['loc' => route('owners'), 'priority' => '0.7'],
            ['loc' => route('about'), 'priority' => '0.6'],
            ['loc' => route('contact'), 'priority' => '0.6'],
        ];

        $listings = Listing::published()
            ->select(['id', 'updated_at'])
            ->latest('updated_at')
            ->get();

        $agents = User::query()
            ->agents()
            ->approved()
            ->select(['id', 'updated_at'])
            ->latest('updated_at')
            ->get();

        return response()
            ->view('sitemap', compact('staticUrls', 'listings', 'agents'))
            ->header('Content-Type', 'application/xml');
    }
}
