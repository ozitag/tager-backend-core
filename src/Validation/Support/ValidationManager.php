<?php

namespace OZiTAG\Tager\Backend\Core\Validation\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Core\Validation\Contracts\IRule;
use OZiTAG\Tager\Backend\Core\Validation\Exceptions\LiteValidationException;
use OZiTAG\Tager\Backend\Core\Validation\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class ValidationManager
{
    public function isMultiErrorsSupport(): bool
    {
        return (boolean)config::get('tager-app.validation.multipleErrors');
    }

    /**
     * @throws LiteValidationException
     */
    public function throw(?string $fieldName = null, string $message = null, ?string $rule = null): void
    {
        throw new LiteValidationException($message, $fieldName, $rule);
    }

    public function validate(array $data, array $rules, array $messages = [], bool $throw = true): bool|ValidatorContract
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            if (!$throw) {
                return $validator;
            }
            throw (new ValidationException($validator));
        }

        return true;
    }

    public function getCodePrefix(): ?string
    {
        return (string)config::get('tager-app.validation.codePrefix') ?? null;
    }

    public function getCode(mixed $rule, ?string $param = null): string
    {
        if (!$rule) {
            return $this->createFullCode('CUSTOM');
        }
        if (is_string($rule)) {
            $code = Config::get("tager-validation-codes.$rule" . ($param ? ".$param" : null));
            return $this->createFullCode(
                $code ?: Str::upper($rule . ($param ? "_$param" : null))
            );
        }
        return $this->getCodeByCustomRule($rule);
    }

    public function getFormattedMessage(
        ?string $fieldName = null, ?string $code = null, ?string $message = null
    ): array
    {
        $formatJson = json_encode(config::get('tager-app.validation.errorFormat'));

        $params = [
            'fieldName' => $fieldName,
            'code' => $code,
            'message' => $message,
        ];

        $errorMessageJson = preg_replace_callback(
            '/#(\w+)/', fn($i) => count($i) === 2 ? ($params[$i[1]] ?? null) : null, $formatJson
        );

        return (array)json_decode($errorMessageJson);
    }

    protected function getCodeByCustomRule(mixed $rule): string
    {
        if ($rule instanceof IRule) {
            return $this->createFullCode($rule->code());
        }
        return $this->createFullCode(strtoupper(substr(strrchr($rule::class, "\\"), 1)));
    }

    protected function createFullCode(...$parts): string
    {
        return array_filter($parts)
            ? implode('_', [$this->getCodePrefix(), ...$parts])
            : implode('_', [$this->getCodePrefix(), 'NONE_CODE']);
    }
}
