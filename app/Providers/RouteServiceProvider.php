<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('v1/auth', function (Request $request) {
            return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::prefix('v1')->group(
                function () {
                    Route::middleware('auth')
                        ->prefix('auth')
                        ->group(base_path('routes/auth.php'));
                    Route::middleware('customer')
                        ->prefix('customer')
                        ->group(base_path('routes/customer.php'));
                    Route::middleware('dash')
                        ->prefix('dash')
                        ->group(base_path('routes/dash.php'));
                    Route::middleware('web')
                        ->group(base_path('routes/web.php'));
                }
            );
        });
    }
}