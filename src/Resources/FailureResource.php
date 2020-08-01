<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FailureResource extends JsonResource
{
    private $errorMessage;

    public function __construct($errorMessage)
    {
        $this->errorMessage = $errorMessage;
        parent::__construct([]);
    }

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toResponse($request)
    {
        return [
            'success' => false,
            'error' => $this->errorMessage
        ];
    }
}
