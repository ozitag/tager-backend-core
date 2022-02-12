<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OZiTAG\Tager\Backend\Core\Structures\SortAttributeCollection;

interface ISortable
{
    public function sort(?SortAttributeCollection $attributes = null, Builder $builder = null): ?Builder;
}
