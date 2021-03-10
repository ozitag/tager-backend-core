<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OZiTAG\Tager\Backend\Core\Structures\SortAttributeCollection;

interface ISortable
{
    /**
     * @param SortAttributeCollection|null $attributes
     * @param Builder|null $builder
     * @return Builder|null
     */
    public function sort(?SortAttributeCollection $attributes = null, Builder $builder = null): ?Builder;
}
