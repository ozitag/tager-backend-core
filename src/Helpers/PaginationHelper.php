<?php

namespace OZiTAG\Tager\Backend\Core\Helpers;


use Illuminate\Support\Facades\Request;

class PaginationHelper
{
    const MAX_LIMIT = 1001;

    public function page(): int
    {
        if ($this->isOffsetBased()) {
            return 0;
        }

        if ($this->perPage() === self::MAX_LIMIT) {
            return 0;
        }

        $current = (int) Request::get('pageNumber', 0);

        if ($current === 0 || $current < 0) {
            return 0;
        }

        return $current - 1;
    }

    public function perPage(): int
    {
        return (int) Request::get($this->isOffsetBased() ? 'pageLimit' : 'pageSize', self::MAX_LIMIT);
    }

    public function offset(): int
    {
        return (int) Request::get('pageOffset', 0);
    }

    public function isOffsetBased(): bool {
        return Request::get('pageOffset', null) !== null;
    }

    public function isPageBased(): bool {
        return Request::get('pageNumber', null) !== null;
    }
}
