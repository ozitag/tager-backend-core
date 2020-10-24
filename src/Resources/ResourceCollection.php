<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

class ResourceCollection extends BaseResourceCollection
{
    private $resourceClass;

    private $formRequest;

    public function __construct($resource, $resourceClass, ?FormRequest $formRequest = null)
    {
        $this->resourceClass = $resourceClass;
        $this->formRequest = $formRequest;

        parent::__construct($resource);
    }

    public function toArray($request)
    {
        $className = $this->resourceClass;

        return $this->collection->transform(function ($item) use ($className) {
            $resource = new $className($item);

            if ($this->formRequest) {
                $resource->withRequest($this->formRequest);
            }

            return $resource;
        });
    }
}
