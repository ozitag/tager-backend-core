<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource as BaseJsonResource;

abstract class JsonResource extends BaseJsonResource
{
    abstract function getData();

    protected Request $request;

    public function withRequest(Request $request)
    {
        $this->request = $request;
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return $this->getData();
    }
}
