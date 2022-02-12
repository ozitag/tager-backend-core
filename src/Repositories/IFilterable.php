<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface IFilterable
{
    public function filter(?array $filter = [], Builder $builder = null): ?Builder;
}
