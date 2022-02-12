<?php

namespace OZiTAG\Tager\Backend\Core\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class QueryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'query' => 'string|max:255|nullable',
        ];
    }
}
