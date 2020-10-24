<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

abstract class JsonResource extends BaseJsonResource
{
    abstract function getData();

    /** @var Request */
    protected $request;

    public function withRequest($request)
    {
        $this->request = $request;
    }

    protected function getRequest()
    {
        return $this->request;
    }

    public function toArray($request)
    {
        return $this->getData();
    }
}
