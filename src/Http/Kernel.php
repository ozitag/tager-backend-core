<?php

namespace OZiTAG\Tager\Backend\Core\Http;

use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use OZiTAG\Tager\Backend\Core\Middlewares\DefaultHeadersMiddleware;
use OZiTAG\Tager\Backend\Core\Middlewares\LocalizationMiddleware;
use OZiTAG\Tager\Backend\Core\Middlewares\TrustProxies;

class Kernel extends \Illuminate\Foundation\Http\Kernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        ValidatePostSize::class,
        ConvertEmptyStringsToNull::class,
        TrimStrings::class,
        LocalizationMiddleware::class,
        DefaultHeadersMiddleware::class,
        TrustProxies::class,
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'api.cache' => \OZiTAG\Tager\Backend\HttpCache\Middleware\CacheHttp::class,
        'api.disable-cache' => \OZiTAG\Tager\Backend\HttpCache\Middleware\DoNotCacheHttp::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    ];
}
