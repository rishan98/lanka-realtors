<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $baseUrl = rtrim((string) config('app.url'), '/');
        if ($baseUrl === '' || str_starts_with($baseUrl, 'http://localhost')) {
            $baseUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
        }

        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /agent',
            'Disallow: /owner',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /password',
            'Disallow: /home',
            '',
            'Sitemap: '.$baseUrl.'/sitemap.xml',
        ];

        return response(implode("\n", $lines)."\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
