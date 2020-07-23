<?php

namespace OZiTAG\Tager\Backend\Core\Controllers;

use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, MarshalTrait;

    /**
     * Serve the given feature with the given arguments.
     *
     * @param string $feature
     * @param array $arguments
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function serve($feature, $arguments = [])
    {
        return $this->dispatchNow($this->marshal($feature, new Collection(), $arguments));
    }
}
