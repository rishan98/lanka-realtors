<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/agent/dashboard';

    public static function homeFor(?User $user): string
    {
        if (! $user) {
            return self::HOME;
        }

        switch ($user->role) {
            case User::ROLE_ADMIN:
                return '/admin/dashboard';
            case User::ROLE_OWNER:
                return '/owner/dashboard';
            default:
                return '/agent/dashboard';
        }
    }

    public static function portalPathFor(User $user, string $path, ?string $query = null): string
    {
        $path = ltrim($path, '/');

        if ($user->isOwner() && str_starts_with($path, 'agent/')) {
            if (str_starts_with($path, 'agent/reviews')) {
                return self::appendQuery('/owner/dashboard', $query);
            }

            $path = 'owner/'.substr($path, strlen('agent/'));
        } elseif ($user->isAgent() && str_starts_with($path, 'owner/')) {
            $path = 'agent/'.substr($path, strlen('owner/'));
        }

        return self::appendQuery('/'.$path, $query);
    }

    public static function portalUrlFor(User $user, string $url): string
    {
        $parts = parse_url($url);
        $path = ltrim($parts['path'] ?? '', '/');
        $query = isset($parts['query']) ? $parts['query'] : null;

        if ($path === '') {
            return $url;
        }

        $mapped = self::portalPathFor($user, $path, $query);

        if (! empty($parts['host'])) {
            $scheme = ($parts['scheme'] ?? 'http').'://';
            $host = $parts['host'];
            $port = isset($parts['port']) ? ':'.$parts['port'] : '';

            return $scheme.$host.$port.$mapped;
        }

        return url($mapped);
    }

    private static function appendQuery(string $path, ?string $query): string
    {
        if ($query === null || $query === '') {
            return $path;
        }

        return $path.'?'.$query;
    }

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
