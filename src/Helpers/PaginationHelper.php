<?php

namespace OZiTAG\Tager\Backend\Core\Helpers;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class PaginationHelper
{
    public function page(): int
    {
        if ($this->isOffsetBased()) {
            return 0;
        }

        $current = (int)Request::get('pageNumber', 0);

        if ($current < 1) {
            return 0;
        }

        return $current - 1;
    }

    public function perPage(): int
    {
        $per_page = (int)Request::get($this->isOffsetBased() ? 'pageLimit' : 'pageSize', $this->defaultPageSize());

        if ($per_page > $this->maxPageSize()) {
            return $this->defaultPageSize();
        }

        return $per_page;
    }

    public function offset(): int
    {
        return (int)Request::get('pageOffset', 0);
    }

    public function isOffsetBased(): bool
    {
        return Request::get('pageOffset', null) !== null;
    }

    public function isPageBased(): bool
    {
        return Request::get('pageNumber', null) !== null;
    }

    public function defaultPageSize(): int
    {
        return (int)Config::get('tager-app.pagination.default_page_size');
    }

    public function maxPageSize(): int
    {
        $result = (int)Config::get('tager-app.pagination.max_page_size');
        if ($result == -1) {
            return PHP_INT_MAX;
        } else {
            return $result;
        }
    }
}
