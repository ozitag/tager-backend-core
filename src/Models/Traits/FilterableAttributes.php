<?php

namespace OZiTAG\Tager\Backend\Core\Models\Traits;

use JetBrains\PhpStorm\Pure;

trait FilterableAttributes
{
    protected array $filterable = [];

    #[Pure]
    public function isFilterable(string $key): bool
    {
        if (in_array($key, $this->getFilterable())) {
            return true;
        }

        return false;
    }

    public function getFilterable(): array {
        return $this->filterable;
    }
}
