<?php

namespace OZiTAG\Tager\Backend\Core\Utils;

class TagerVariables
{
    /** @var string[] */
    private static array $variableJobs = [];

    /** @var array */
    private static array $variableDefaultValues = [];

    public function getValue(string $variable): mixed
    {
        if (!array_key_exists($variable, self::$variableDefaultValues)) {
            return self::$variableDefaultValues[$variable] ?? null;
        }

        $jobClassName = self::$variableJobs[$variable];

        try {
            $value = dispatch_now(new $jobClassName);
        } catch (\Exception $exception) {
            $value = null;
        }

        return $value;
    }

    public function isExisted(string $variable): bool
    {
        return array_key_exists($variable, self::$variableJobs);
    }

    public function processText(string $text): string
    {
        return preg_replace_callback('#\{(.+?)\}#si', function ($item) {
            if ($this->isExisted($item[1])) {
                return $this->getValue($item[1]);
            } else {
                return $item[0];
            }
        }, $text);
    }

    public static function register(string $variableName, string $jobClassName, mixed $defaultValue = null)
    {
        self::$variableJobs[$variableName] = $jobClassName;

        self::$variableDefaultValues[$variableName] = $defaultValue;
    }
}
