<?php

namespace OZiTAG\Tager\Backend\Core\Pagination;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;
use Kalnoy\Nestedset\Collection;
use OZiTAG\Tager\Backend\Core\Facades\Pagination;

class Paginator extends Collection implements Arrayable
{
    protected int $total;

    public function __construct(\Illuminate\Database\Eloquent\Collection $items, int $count = 0)
    {
        $this->total = $count;

        parent::__construct($items);
    }

    #[ArrayShape(['page' => "array", 'total' => "int"])]
    public function getMeta(): array
    {
        $perPage = Pagination::perPage();

        $pageMeta = Pagination::isOffsetBased() ? [
            'offset' => Pagination::offset(),
            'limit' => Pagination::perPage(),
        ] : [
            'number' => Pagination::page() + 1,
            'size' => Pagination::perPage(),
            'count' => ceil($this->total / ($perPage > 0 ? $perPage : 1)),
        ];

        return [
            'page' => $pageMeta,
            'total' => $this->total
        ];
    }
}
