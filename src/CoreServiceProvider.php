<?php

namespace OZiTAG\Tager\Backend\Core;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use OZiTAG\Tager\Backend\Core\Routing\ApiResource;
use OZiTAG\Tager\Backend\Core\Routing\ResourceRegistrar;
use OZiTAG\Tager\Backend\Core\Validation\Validator;

class CoreServiceProvider extends RouteServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        ApiResource::register();

        $this->app->bind(\Illuminate\Routing\ResourceRegistrar::class, function () {
            return new ResourceRegistrar(app()->make(Router::class));
        });
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
            Route::prefix('')->group(base_path('routes/public.php'));
        }

        if (is_file(base_path('routes/web.php'))) {
            Route::prefix('')->group(base_path('routes/web.php'));
        }

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'tager-core');

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'tager-app');
        $this->mergeConfigFrom(__DIR__ . '/../config/validation-codes.php', 'tager-validation-codes');

        \Illuminate\Support\Facades\Validator::resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });

        parent::boot();
    }
}
