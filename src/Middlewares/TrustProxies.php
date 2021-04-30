<?php
namespace OZiTAG\Tager\Backend\Core\Middlewares;

use Illuminate\Http\Request;

class TrustProxies extends \Fideloper\Proxy\TrustProxies
{
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO;
}
