<?php

namespace Osid\ApiRateLimiter;

use Illuminate\Support\ServiceProvider;

class ApiRateLimiterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api-rate-limiter.php', 'api-rate-limiter');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/api-rate-limiter.php' => config_path('api-rate-limiter.php'),
            ], 'config');
        }

        // Register Middleware
        $router = $this->app['router'];
        $router->aliasMiddleware('api.rate.limiter', \Osid\ApiRateLimiter\Http\Middleware\ApiRateLimiter::class);
    }
}
