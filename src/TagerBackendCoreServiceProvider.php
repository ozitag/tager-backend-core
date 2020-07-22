<?php

namespace OZiTAG\Tager\Backend\Core;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class TagerBackendCoreServiceProvider extends RouteServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        Route::pattern('id', '[0-9]+');

        if (is_file(base_path('routes/public.php'))) {
            Route::prefix('')->middleware('api')->group(base_path('routes/public.php'));
        }

        parent::boot();
    }
}
