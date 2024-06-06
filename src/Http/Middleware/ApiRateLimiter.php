<?php

namespace Osid\ApiRateLimiter\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimiter
{
    public function handle(Request $request, Closure $next)
    {
        $config = Config::get('api-rate-limiter');

        // Determine the rate limiting key based on IP address and user (if authenticated)
        $key = $this->resolveRateLimitKey($request);

        // Get the rate limiting configuration for the requested route
        $routeConfig = $this->getRouteConfig($request, $config);

        // Check if the rate limit has been exceeded
        if ($this->hasExceededRateLimit($key, $routeConfig)) {
            // Return custom response message
            return response()->json([
                'message' => $config['response_messages']['rate_limit_exceeded']
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        // Increment the request count for the key
        $this->incrementRequestCount($key, $routeConfig);

        return $next($request);
    }

    private function resolveRateLimitKey(Request $request)
    {
        $ip = $request->ip();
        $user = $request->user();

        $key = $ip;

        if (Config::get('api-rate-limiter.user_based') && $user) {
            $key = $user->id;
        }

        return $key;
    }

    private function getRouteConfig(Request $request, array $config)
    {
        $path = $request->path();

        foreach ($config['routes'] as $route => $routeConfig) {
            if (fnmatch($route, $path)) {
                return $routeConfig;
            }
        }

        return $config['global'];
    }

    private function hasExceededRateLimit($key, $routeConfig)
    {
        $limit = $routeConfig['limit'];
        $burst = $routeConfig['burst'];
        $cacheDriver = Config::get('api-rate-limiter.cache_driver');

        $requestCount = Cache::store($cacheDriver)->get($key, 0);

        return $requestCount >= $limit + $burst;
    }

    private function incrementRequestCount($key, $routeConfig)
    {
        $cacheDriver = Config::get('api-rate-limiter.cache_driver');
        $timeWindow = 3600; // 1 hour

        Cache::store($cacheDriver)->increment($key);
        Cache::store($cacheDriver)->put($key, Cache::store($cacheDriver)->get($key, 0), $timeWindow);
    }
}