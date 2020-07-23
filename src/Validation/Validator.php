<?php

namespace OZiTAG\Tager\Backend\Core\Validation;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as BaseValidator;
use OZiTAG\Tager\Backend\Core\Support\MessageBag;

class Validator extends BaseValidator
{
    protected function resetMessageBag() {
        $this->messages = new MessageBag;
    }

    /**
     * Add a failed rule and error message to the collection.
     *
     * @param  string  $attribute
     * @param  string  $rule
     * @param  array  $parameters
     * @return void
     */
    public function addFailure($attribute, $rule, $parameters = [])
    {
        if (! $this->messages) {
            $this->passes();
        }

        if(!($this->messages instanceof MessageBag)) {
            $this->resetMessageBag();
        }

        $attribute = str_replace('__asterisk__', '*', $attribute);

        if (in_array($rule, $this->excludeRules)) {
            return $this->excludeAttribute($attribute);
        }

        $messageType = config()->get('tager-core.multiple_validation_errors') ? 'add' : 'set';

        $this->messages->$messageType($attribute, [
            'code' => $this->getCode($rule, $parameters),
            'message' => $this->getMessage($rule, $parameters),
        ]);

        $this->failedRules[$attribute][$rule] = $parameters;
    }

    protected function getMessage($rule, $parameters = [])
    {
        switch (Str::lower($rule)) {
            case 'required':
                return 'Field required';
            case 'date':
                return 'Invalid date';
            case 'numeric':
                return 'Should be a number';
            default:
                return implode(' ', [$rule, ...$parameters]);
        }
    }

    protected function getCode($rule, $parameters = [])
    {
        switch (Str::lower($rule)) {
            case 'required':
                return 'VALIDATION_REQUIRED';
            case 'date':
                return 'VALIDATION_DATE';
            case 'numeric':
                return 'VALIDATION_NUMERIC';
            default:
                return implode('_', ['INVALID', Str::upper($rule), ...$parameters]);
        }
    }
}
