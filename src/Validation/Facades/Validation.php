<?php

namespace OZiTAG\Tager\Backend\Core\Validation\Facades;

use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Support\Facades\Facade;
use OZiTAG\Tager\Backend\Core\Validation\Support\ValidationManager;


/**
 * Class Validation
 * @package OZiTAG\Tager\Backend\Core\Validation\Facades
 * @method static isMultiErrorsSupport()
 * @method static throw(string $fieldName, string $message = null, ?string $rule = null)
 * @method static bool|ValidatorContract validate(array $data, array $rules, array $messages = [], bool $throw = true)
 * @method static ?string getCodePrefix()
 * @method static string getCode(mixed $rule, ?string $param = null)
 * @method static array getFormattedMessage(?string $fieldName = null, ?string $code = null, ?string $message = null)
 */
class Validation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ValidationManager::class;
    }
}
