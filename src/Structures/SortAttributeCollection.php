<?php

namespace OZiTAG\Tager\Backend\Core\Structures;

use Illuminate\Http\Request;
use OZiTAG\Tager\Backend\Core\Enums\SortDirection;

class SortAttributeCollection
{
    /** @var SortAttribute[] */
    private array $attributes = [];

    public static function loadFromRequest(Request $request): ?static
    {
        $result = new static();

        $data = $request->get('sort');
        if (empty($data)) {
            return $result;
        }

        if (!is_array($data)) {
            $data = [$data];
        }

        foreach ($data as $attribute) {
            $parts = explode(',', $attribute);
            if (count($parts) > 1 && !in_array(strtolower($parts[1]), SortDirection::getValues())) {
                continue;
            }

            $result->add($parts[0], $parts[1] ?? SortDirection::ASC);
        }

        return $result;
    }

    private function add(string $attribute, string $direction)
    {
        if (in_array($direction, SortDirection::getValues())) {
            $this->attributes[] = new SortAttribute($attribute, $direction);
        }
    }

    /**
     * @return SortAttribute
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
