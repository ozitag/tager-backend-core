<?php

namespace OZiTAG\Tager\Backend\Core\Features;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\App;
use OZiTAG\Tager\Backend\Core\Pagination\PaginationRequest;
use OZiTAG\Tager\Backend\Core\Traits\JobDispatcherTrait;
use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;
use OZiTAG\Tager\Backend\Core\Traits\UserAccess;

class Feature
{
    use MarshalTrait, DispatchesJobs, JobDispatcherTrait, UserAccess;

    public function registerRequest(string $request) {
        App::make($request);
    }

    public function registerPaginationRequest() {
        $this->registerRequest(PaginationRequest::class);
    }
}
