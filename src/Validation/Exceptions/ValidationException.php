<?php

namespace OZiTAG\Tager\Backend\Core\Validation\Exceptions;

use Illuminate\Validation\ValidationException as BaseValidationException;
use OZiTAG\Tager\Backend\Core\Validation\Facades\Validation;

class ValidationException extends BaseValidationException
{
    public $status = 400;

    public function __construct($validator = null, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);
    }

    public function errors(): array
    {
        $messages = $this->validator->errors()->messages();

        if (!Validation::isMultiErrorsSupport()) {
            foreach ($messages as $attribute => $errors) {
                $messages[$attribute] = is_array($errors) ? array_shift($errors) : null;
            }
        }

        return $messages;
    }

    protected static function summarize($validator)
    {
        return 'The given data was invalid.';
    }
}
