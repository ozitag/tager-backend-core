<?php

namespace OZiTAG\Tager\Backend\Core\Http;

use Illuminate\Contracts\Validation\Validator;
use OZiTAG\Tager\Backend\Core\Validation\Exceptions\ValidationException;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        throw (new ValidationException($validator));
    }
}
