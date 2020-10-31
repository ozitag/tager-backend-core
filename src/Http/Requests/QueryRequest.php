<?php

namespace OZiTAG\Tager\Backend\Core\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class QueryRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'query' => 'string|max:255|nullable',
        ];
    }
}
