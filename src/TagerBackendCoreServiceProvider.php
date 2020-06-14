<?php

namespace OZiTAG\Tager\Backend\Core;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TagerBackendCoreServiceProvider extends ServiceProvider
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

        if (is_file(base_path('routes/public.php'))) {
            Route::prefix('')->group(base_path('routes/public.php'));
        }
    }
}
