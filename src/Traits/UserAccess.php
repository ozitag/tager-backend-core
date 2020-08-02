<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Illuminate\Support\Facades\Auth;

trait UserAccess
{
    public function user()
    {
        return auth()->guard('api')->user() ?? Auth::user();
    }
}
