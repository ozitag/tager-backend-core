<?php

namespace OZiTAG\Tager\Backend\Core\Enums;

class Enum extends \BenSampo\Enum\Enum
{
    public static function label(?string $value): string
    {
        return 'Unknown';
    }

    public static function labelList(): array
    {
        $data = [];

        foreach (static::getValues() as $value) {
            $data[] = [
                'name' => $value,
                'label' => static::label($value)
            ];
        }

        return $data;
    }

    public static function hasLabel(string $label): bool
    {
        foreach (static::getValues() as $value) {
            if (static::label($value) == $label) {
                return true;
            }
        }

        return false;
    }
}
