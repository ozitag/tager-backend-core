<?php

namespace OZiTAG\Tager\Backend\Core\Pagination;

use Illuminate\Contracts\Support\Arrayable;
use Kalnoy\Nestedset\Collection;
use OZiTAG\Tager\Backend\Core\Facades\Pagination;

class Paginator extends Collection implements Arrayable
{
    protected int $total;

    /**
     * Paginator constructor.
     * @param array $items
     * @param int $count
     */
    public function __construct($items = [], int $count = 0)
    {
        $this->total = $count;
        parent::__construct($items);
    }

    public function getMeta() {
        $pageMeta = Pagination::isOffsetBased() ? [
            'offset' => Pagination::offset(),
            'limit' => Pagination::perPage(),
        ] : [
            'number' => Pagination::page() + 1,
            'size' => Pagination::perPage(),
            'count' => ceil($this->total / (Pagination::perPage() ?? 1)),
        ];

        return [
            'page' => $pageMeta,
            'total' => $this->total
        ];
    }
}
