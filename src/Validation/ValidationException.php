<?php

namespace OZiTAG\Tager\Backend\Core\Validation;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\ValidationException as BaseValidationException;

class ValidationException extends BaseValidationException
{
    /**
     * Create a new validation exception from a plain array of messages.
     *
     * @param string $field
     * @param $message
     * @param null $code
     * @return ValidationException
     */
    public static function field(string $field, $message, $code = null)
    {
        return new static(tap(ValidatorFacade::make([], []), function ($validator) use ($field, $message, $code) {
            $validator->resetMessageBag();
            $validator->errors()->set($field, [
                'message' => $message,
                'code' => $code ?? strtoupper(str_replace(' ', '_', $message)),
            ]);
        }));
    }
}
