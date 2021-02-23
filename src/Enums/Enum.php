<?php

namespace OZiTAG\Tager\Backend\Core\Enums;

class Enum extends \BenSampo\Enum\Enum
{
    public static function label(string $value): string
    {
        return 'Unknown';
    }

    public static function labelList(): array
    {
        $data = [];

        foreach (self::getValues() as $value) {
            $data[] = [
                'name' => $value,
                'label' => self::label($value)
            ];
        }

        return $data;
    }
}
