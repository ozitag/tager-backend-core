<?php

namespace OZiTAG\Tager\Backend\Core\Validation\Exceptions;

use JetBrains\PhpStorm\Pure;
use OZiTAG\Tager\Backend\Core\Validation\Facades\Validation;

class LiteValidationException extends \Exception
{
    public function __construct(
        protected         $fieldMessage,
        protected ?string $attribute = null,
        protected ?string $rule = null,
        protected         $code = 400,
    )
    {
        if (empty($attribute)) {
            parent::__construct($fieldMessage);
        } else {
            parent::__construct('The given data was invalid.');
        }
    }

    public function getResponse(): \Illuminate\Http\JsonResponse
    {
        if (empty($this->attribute)) {
            return response()->json([
                'message' => $this->message,
                'errors' => new \stdClass()
            ], $this->code);
        }

        $errors = Validation::getFormattedMessage(
            $this->attribute, Validation::getCode($this->rule), $this->fieldMessage,
        );

        $format = Validation::isMultiErrorsSupport() ? [$errors] : $errors;

        return response()->json([
            'message' => $this->message,
            'errors' => [
                $this->attribute => $format
            ],
        ], $this->code);
    }
}
