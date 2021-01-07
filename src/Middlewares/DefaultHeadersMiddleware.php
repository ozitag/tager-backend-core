<?php
namespace OZiTAG\Tager\Backend\Core\Middlewares;

use Illuminate\Support\Facades\Config;

class DefaultHeadersMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $headers = Config::get('tager-app.default_headers') ?? [];

        foreach ($headers as $header => $value) {
            $request->headers->set($header, $value);
        }

        return $next($request);
    }
}
