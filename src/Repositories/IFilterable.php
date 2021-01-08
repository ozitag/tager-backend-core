<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface IFilterable
{
    /**
     * @param array|null $filter
     * @param Builder|null $builder
     * @return Builder|null
     */
    public function filter(?array $filter = [], Builder $builder = null): ?Builder;
}
