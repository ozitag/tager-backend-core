<?php

namespace OZiTAG\Tager\Backend\Core\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'filter' => 'array|nullable',
        ];
    }
}
