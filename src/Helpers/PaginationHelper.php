<?php

namespace OZiTAG\Tager\Backend\Core\Helpers;


use Illuminate\Support\Facades\Request;

class PaginationHelper
{
    const MAX_LIMIT = 1001;

    public function page(): int
    {
        $current = (int) Request::get('page', 0);

        if ($current === 0 || $current < 0 || $this->perPage() === self::MAX_LIMIT) {
            return 0;
        }

        return $current - 1;
    }

    public function perPage(): int
    {
        return (int) Request::get('perPage', self::MAX_LIMIT);
    }

    public function offset(): int
    {
        return (int) Request::get('offset', 0);
    }
}
