<?php

namespace OZiTAG\Tager\Backend\Core\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filter' => 'array|nullable',
        ];
    }
}
