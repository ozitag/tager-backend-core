<?php

namespace OZiTAG\Tager\Backend\Core\Pagination;

use Illuminate\Contracts\Support\Arrayable;
use OZiTAG\Tager\Backend\Core\Facades\Pagination;

class Paginator extends \Kalnoy\Nestedset\Collection implements Arrayable
{
    protected $items;
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

    public function total() {
        return $this->total;
    }

    public function perPage() {
        return Pagination::perPage();
    }

    public function offset() {
        return Pagination::offset();
    }

    public function getMeta() {
        return [
            'itemsCount' => $this->total - $this->offset(),
            'pagesCount' => ceil(($this->total - $this->offset()) / $this->perPage()),
        ];
    }

    /**
     * Build a list of nodes that retain the order that they were pulled from
     * the database.
     *
     * @param bool $root
     *
     * @return \Kalnoy\Nestedset\Collection
     */
    public function toFlatTree($root = false)
    {
        $result = $this;

        if ($this->isEmpty()) return $result;

        $this->offset(Pagination::offset() + Pagination::page() * Pagination::perPage())
            ->limit(Pagination::perPage())
            ->get();

        $groupedNodes = $this->groupBy($this->first()->getParentIdName());


        return $result->flattenTree($groupedNodes, $this->getRootNodeId($root));
    }
}
