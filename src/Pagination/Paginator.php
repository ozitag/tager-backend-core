<?php

namespace OZiTAG\Tager\Backend\Core\Pagination;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use OZiTAG\Tager\Backend\Core\Facades\Pagination;

class Paginator extends Collection implements Arrayable
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
}
