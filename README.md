# Laravel API Rate Limiter

A robust API rate limiter and throttling package for Laravel, designed to manage and control the flow of requests to your APIs, protecting against abuse and ensuring fair usage.

## Installation

1. **Require the Package:**

   Run the following command to install the package via Composer:

   ```bash
   composer require osid/laravel-api-rate-limiter
   ```

2. **Publish Configuration:**

   Publish the configuration file using the artisan command:

   ```bash
   php artisan vendor:publish --provider="osid\ApiRateLimiter\ApiRateLimiterServiceProvider" --tag=config
   ```

   This will create a configuration file at `config/api-rate-limiter.php`.

## Configuration

The `api-rate-limiter.php` configuration file allows you to define rate limits and burst settings for your API routes. Here’s an example configuration:

```php
return [
    'global' => [
        'limit' => 1000, // Requests per hour
        'burst' => 50, // Burst limit
    ],
    'routes' => [
        'api/v1/users' => [
            'limit' => 500,
            'burst' => 20,
        ],
        'api/v1/orders' => [
            'limit' => 200,
            'burst' => 10,
        ],
    ],
    'user_based' => true, // Enable user-specific limits
    'ip_based' => true, // Enable IP-based limits
    'cache_driver' => 'redis', // Cache driver to use
    'response_messages' => [
        'rate_limit_exceeded' => 'Too many requests. Please try again later.',
    ],
];
```

- **Global Settings:**
  - `limit`: The maximum number of requests allowed per hour globally.
  - `burst`: Additional requests allowed in short bursts.

- **Route-Specific Settings:**
  - Define limits and burst settings for specific routes.

- **User-Based and IP-Based Limits:**
  - `user_based`: Enable rate limits based on authenticated users.
  - `ip_based`: Enable rate limits based on client IP addresses.

- **Cache Driver:**
  - `cache_driver`: Specify the cache driver to use (e.g., `redis`, `memcached`).

- **Response Messages:**
  - Customize the response message when the rate limit is exceeded.

## Applying Middleware

To apply the rate limiting middleware to your API routes, add it to the routes in your `routes/api.php` file.

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

Route::middleware(['api.rate.limiter'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/orders', [OrderController::class, 'index']);
    // Other routes
});
```

## Example Usage

1. **Global Rate Limiting:**

   To apply global rate limits, ensure the `global` configuration is set in `config/api-rate-limiter.php`. This will apply to all routes not specifically configured.

2. **Route-Specific Rate Limiting:**

   To apply rate limits to specific routes, define them under the `routes` key in `config/api-rate-limiter.php`.

```php
'api/v1/users' => [
    'limit' => 500,
    'burst' => 20,
],
'api/v1/orders' => [
    'limit' => 200,
    'burst' => 10,
],
```

3. **Custom Response Messages:**

   Customize the message returned when the rate limit is exceeded by modifying the `response_messages` key in the configuration.

```php
'response_messages' => [
    'rate_limit_exceeded' => 'Too many requests. Please try again later.',
],
```

## Testing

Use tools like Postman or cURL to test the rate limiting functionality. Make several requests to your API endpoints to ensure the rate limits and burst settings are working as expected.

## Conclusion

This package provides a flexible and powerful way to manage API request rates in Laravel applications, ensuring fair usage and protecting against abuse. For any issues or contributions, please refer to the package repository.
