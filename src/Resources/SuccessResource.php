<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class SuccessResource extends JsonResource
{
    #[Pure]
    public function __construct($resource = null)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    #[ArrayShape(['success' => "bool"])]
    public function toResponse($request): array
    {
        return [
            'success' => true
        ];
    }
}
