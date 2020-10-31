<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use OZiTAG\Tager\Backend\Core\Pagination\Paginator;

class ResourceCollection extends BaseResourceCollection
{
    protected $originResource;

    /**
     * ResourceCollection constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->originResource = $resource;
        parent::__construct($resource);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            self::$wrap => $this->collection->toArray(),
        ];

        if($this->originResource instanceof Paginator) {
            $data['meta'] = $this->originResource->getMeta();
        }

        return $data;
    }
}
