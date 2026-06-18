<?php

namespace App\Support;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Str;

class Seo
{
    public static function shouldNoIndex(?string $routeName): bool
    {
        if ($routeName === null) {
            return false;
        }

        if (Str::startsWith($routeName, ['admin.', 'agent.', 'owner.'])) {
            return true;
        }

        $privateRoutes = [
            'login',
            'register',
            'register.pending',
            'register.owner',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'password.confirm',
            'verification.notice',
            'verification.verify',
            'verification.resend',
        ];

        return in_array($routeName, $privateRoutes, true);
    }

    /**
     * @param  array<string, mixed>  $filters
     * @param  array<string, array<string, mixed>>  $kinds
     * @return array{title: string, description: string, canonical: string}
     */
    public static function listingIndex(array $filters, array $kinds): array
    {
        $siteName = config('app.name');
        $labelParts = [];

        if (! empty($filters['kind']) && isset($kinds[$filters['kind']])) {
            $labelParts[] = $kinds[$filters['kind']]['label'];
        }

        if (! empty($filters['subtype']) && ! empty($filters['kind']) && isset($kinds[$filters['kind']]['subtypes'][$filters['subtype']])) {
            $labelParts[] = $kinds[$filters['kind']]['subtypes'][$filters['subtype']];
        } elseif (! empty($filters['subtype'])) {
            $labelParts[] = Str::title(str_replace('-', ' ', (string) $filters['subtype']));
        }

        if (! empty($filters['city'])) {
            $labelParts[] = 'in '.$filters['city'];
        }

        if (! empty($filters['q'])) {
            $labelParts[] = 'matching "'.Str::limit($filters['q'], 40).'"';
        }

        if ($labelParts) {
            $heading = implode(' ', $labelParts);
            $title = $heading.' — '.$siteName;
            $description = 'Browse '.$heading.' on '.$siteName.'. Filter by price, area, and bedrooms across Sri Lanka.';
        } else {
            $title = 'Browse property listings — '.$siteName;
            $description = config('portal.seo.default_description');
        }

        return [
            'title' => $title,
            'description' => Str::limit($description, 160, ''),
            'canonical' => route('listings.index', array_filter($filters)),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return array{title: string, description: string, canonical: string}
     */
    public static function landsIndex(array $filters): array
    {
        $siteName = config('app.name');
        $labelParts = ['Land'];

        if (! empty($filters['kind']) && in_array($filters['kind'], ['sale', 'rental'], true)) {
            $labelParts[] = $filters['kind'] === 'sale' ? 'for sale' : 'for rent';
        }

        if (! empty($filters['city'])) {
            $labelParts[] = 'in '.$filters['city'];
        }

        if (! empty($filters['q'])) {
            $labelParts[] = 'matching "'.Str::limit($filters['q'], 40).'"';
        }

        $heading = implode(' ', $labelParts);

        return [
            'title' => $heading.' — '.$siteName,
            'description' => 'Browse land listings for sale and rent across Sri Lanka on '.$siteName.'. Filter by location and price.',
            'canonical' => route('lands.index', array_filter($filters)),
        ];
    }

    /**
     * @return array{title: string, description: string, canonical: string, image: string}
     */
    public static function listingShow(Listing $listing): array
    {
        $siteName = config('app.name');
        $location = collect([$listing->area, $listing->city])->filter()->implode(', ');
        $description = trim(strip_tags((string) $listing->description));

        if ($description === '') {
            $description = $listing->title
                .($location ? ' in '.$location : '')
                .'. '.$listing->kindLabel().' · '.$listing->subtypeLabel()
                .'. Listed on '.$siteName.'.';
        }

        return [
            'title' => $listing->title.' — '.$siteName,
            'description' => Str::limit($description, 160, ''),
            'canonical' => route('listings.show', $listing),
            'image' => $listing->imageUrl(),
        ];
    }

    /**
     * @return array{title: string, description: string, canonical: string, image: string}
     */
    public static function agentPortfolio(User $agent, ?string $activeKind = null): array
    {
        $siteName = config('app.name');
        $agency = $agent->agency_name ?: 'Real estate agent';
        $bio = trim(strip_tags((string) $agent->bio));
        $description = $bio !== ''
            ? Str::limit($bio, 160, '')
            : 'View '.$agent->name.'\'s property portfolio on '.$siteName.'. '.$agency.' with active listings across Sri Lanka.';

        $title = $agent->name.' — '.$agency.' — '.$siteName;
        $params = $activeKind ? ['kind' => $activeKind] : [];

        return [
            'title' => $title,
            'description' => $description,
            'canonical' => route('agents.portfolio', array_merge(['agent' => $agent], $params)),
            'image' => $agent->coverUrl() ?: $agent->avatarUrl(),
        ];
    }
}
