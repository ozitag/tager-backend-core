<?php

namespace OZiTAG\Tager\Backend\Core\Pagination;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'perPage' => 'integer|min:1|nullable',
            'offset' => 'integer|min:0|nullable',
            'page' => 'integer|min:0|nullable',
        ];
    }
}
