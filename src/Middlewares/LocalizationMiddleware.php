<?php

namespace OZiTAG\Tager\Backend\Core\Middlewares;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class LocalizationMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $locale = strtolower(trim(
                $request->header('Accept-Language')
            )) ?? 'en';

        if (in_array($locale, Config::get('app.available_locales', ['en']))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
