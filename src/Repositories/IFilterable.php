<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

interface IFilterable
{
    public function filter(?array $filter = [], BuilderContract $builder = null): ?BuilderContract;
}
