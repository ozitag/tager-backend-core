<?php

namespace OZiTAG\Tager\Backend\Core\Http;

use Illuminate\Contracts\Validation\Validator;
use OZiTAG\Tager\Backend\Validation\Exceptions\ValidationException;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator));
    }
}
