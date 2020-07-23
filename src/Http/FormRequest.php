<?php

namespace OZiTAG\Tager\Backend\Core\Http;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

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
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag)
            ->status(400)
            ->redirectTo($this->getRedirectUrl());
    }
}
