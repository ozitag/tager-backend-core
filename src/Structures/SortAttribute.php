<?php

namespace OZiTAG\Tager\Backend\Core\Structures;

use OZiTAG\Tager\Backend\Core\Enums\SortDirection;

class SortAttribute
{
    public string $attribute;

    public string $direction;

    public function __construct(string $attribute, string $direction = SortDirection::ASC)
    {
        $this->attribute = $attribute;

        $this->direction = in_array($direction, SortDirection::getValues()) ? $direction : SortDirection::ASC;
    }
}
