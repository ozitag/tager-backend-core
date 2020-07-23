<?php

namespace OZiTAG\Tager\Backend\Core\Kernel;

class HttpKernel extends Illuminate\Foundation\Http\Kernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Fruitcake\Cors\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'api.cache' => OZiTAG\Tager\Backend\HttpCache\Middleware\CacheHttp::class,
        'api.disable-cache' => OZiTAG\Tager\Backend\HttpCache\Middleware\DoNotCacheHttp::class
    ];
}
