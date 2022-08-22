<?php

namespace OZiTAG\Tager\Backend\Core\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class SortRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sort' => 'string|max:255|nullable',
        ];
    }
}
