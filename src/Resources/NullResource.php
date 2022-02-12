<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class NullResource extends JsonResource
{
    #[Pure]
    public function __construct($resource = null)
    {
        parent::__construct($resource);
    }

    /**
     * @param Request $request
     */
    #[ArrayShape(['data' => "null"])]
    public function toResponse($request): array
    {
        return [
            'data' => null
        ];
    }
}
