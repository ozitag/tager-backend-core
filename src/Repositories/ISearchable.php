<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Model;

interface ISearchable
{
    public function searchByQuery(?string $query, BuilderContract $builder = null): ?BuilderContract;
}
