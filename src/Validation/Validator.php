<?php

namespace OZiTAG\Tager\Backend\Core\Validation;

use Illuminate\Support\Str;
use Illuminate\Validation\Validator as BaseValidator;
use OZiTAG\Tager\Backend\Core\Support\MessageBag;

class Validator extends BaseValidator
{
    public function resetMessageBag() {
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


    /**
     * Validate an attribute using a custom rule object.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Illuminate\Contracts\Validation\Rule  $rule
     * @return void
     */
    protected function validateUsingCustomRule($attribute, $value, $rule)
    {
        if (!$rule->passes($attribute, $value)) {

            if(!($this->messages instanceof MessageBag)) {
                $this->resetMessageBag();
            }

            $this->failedRules[$attribute][get_class($rule)] = [];

            $messages = $rule->message();
            $messages = $messages ? (array) $messages : [get_class($rule)];
            $messageType = config()->get('tager-core.multiple_validation_errors') ? 'add' : 'set';

            foreach ($messages as $message) {
                $this->messages->$messageType($attribute, [
                    'code' => $this->getCode(class_basename($rule)),
                    'message' => $message ?? $this->getMessage(class_basename($rule)),
                ]);
            }
        }
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
