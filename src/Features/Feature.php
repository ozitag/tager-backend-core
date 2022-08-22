<?php

namespace OZiTAG\Tager\Backend\Core\Features;

use Illuminate\Support\Facades\App;
use OZiTAG\Tager\Backend\Core\Http\Requests\FilterRequest;
use OZiTAG\Tager\Backend\Core\Http\Requests\PaginationRequest;
use OZiTAG\Tager\Backend\Core\Http\Requests\QueryRequest;
use OZiTAG\Tager\Backend\Core\Http\Requests\SortRequest;
use OZiTAG\Tager\Backend\Core\Traits\JobDispatcherTrait;
use OZiTAG\Tager\Backend\Core\Traits\UserAccess;

class Feature
{
    use JobDispatcherTrait, UserAccess;

    public function registerRequest(string $request)
    {
        App::make($request);
    }

    public function registerPaginationRequest(): self
    {
        $this->registerRequest(PaginationRequest::class);
        return $this;
    }

    public function registerQueryRequest(): self
    {
        $this->registerRequest(QueryRequest::class);
        return $this;
    }

    public function registerSortRequest(): self
    {
        $this->registerRequest(SortRequest::class);
        return $this;
    }

    public function registerFilterRequest(): self
    {
        $this->registerRequest(FilterRequest::class);
        return $this;
    }
}
