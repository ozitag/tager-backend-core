<?php

namespace OZiTAG\Tager\Backend\Core\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'pageOffset' => 'integer|min:0|required_with:pageLimit',
            'pageLimit' => 'integer|min:1',
            'pageNumber' => 'integer|min:1|required_with:pageSize',
            'pageSize' => 'integer|min:1',
        ];
    }
}
