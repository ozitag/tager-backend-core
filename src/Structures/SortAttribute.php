<?php

namespace OZiTAG\Tager\Backend\Core\Structures;

use OZiTAG\Tager\Backend\Core\Enums\SortDirection;

class SortAttribute
{
    public string $attribute;

    public SortDirection $direction;

    public function __construct(string $attribute, SortDirection $direction = SortDirection::Asc)
    {
        $this->attribute = $attribute;

        $this->direction = in_array($direction, SortDirection::cases()) ? $direction : SortDirection::Asc;
    }
}
