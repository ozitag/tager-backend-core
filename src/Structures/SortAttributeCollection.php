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
        $data = $request->get('sort_by');
        if (empty($data)) {
            return null;
        }

        $result = new static();

        $attributes = explode(',', $data);

        foreach ($attributes as $attribute) {
            $firstSymbol = substr($attribute, 0, 1);

            if ($firstSymbol == '+' || $firstSymbol == '-') {
                $direction = $firstSymbol == '+' ? SortDirection::ASC : SortDirection::DESC;
                $attribute = substr($attribute, 1);
            } else {
                $direction = SortDirection::ASC;
                $attribute = $attribute;
            }

            $result->add($attribute, $direction);
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
