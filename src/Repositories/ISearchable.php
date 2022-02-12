<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface ISearchable
{
    public function searchByQuery(?string $query, Builder $builder = null): ?Builder;
}
