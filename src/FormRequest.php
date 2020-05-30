<?php

namespace OZiTAG\Tager\Backend\Core;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

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
        $errorMessages = $validator->errors()->getMessages();

        $errors = [];
        foreach ($errorMessages as $errorField => $errorFieldData) {
            foreach ($errorFieldData as $errorFieldDatum) {

                if (mb_substr($errorFieldDatum, 0, mb_strlen('validation.')) == 'validation.') {
                    $data = explode('.', $errorFieldDatum);
                    if ($data[1] == 'required') {
                        $errors[$errorField] = [
                            'code' => 'VALIDATION_REQUIRED',
                            'message' => 'Field required'
                        ];
                    } else if ($data[1] == 'numeric') {
                        $errors[$errorField] = [
                            'code' => 'VALIDATION_NUMERIC',
                            'message' => 'Should be a number'
                        ];
                    } else if ($data[1] == 'date') {
                        $errors[$errorField] = [
                            'code' => 'VALIDATION_DATE',
                            'message' => 'Invalid date'
                        ];
                    } else {
                        $errors[$errorField] = [
                            'code' => $data[1],
                            'message' => $data[1]
                        ];
                    }
                } else{
                    $errors[$errorField] = [
                        'code' => 'UNKNOWN_ERROR',
                        'message' => $errorFieldDatum
                    ];
                }

                break;
            }
        }

        throw new HttpResponseException(response()->json([
            'errors' => $errors,
        ], 400));
    }
}
