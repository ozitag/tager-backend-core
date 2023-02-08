<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use OZiTAG\Tager\Backend\Core\Structures\SortAttributeCollection;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

interface ISortable
{
    public function sort(?string $sort = null, BuilderContract $builder = null): ?BuilderContract;
}
