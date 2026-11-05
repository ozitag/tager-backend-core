<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use Illuminate\Contracts\Database\Eloquent\Builder;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Core\Repositories\IFilterable;
use OZiTAG\Tager\Backend\Core\Repositories\ISearchable;
use OZiTAG\Tager\Backend\Core\Repositories\ISortable;

class ArticleRepository extends EloquentRepository implements ISearchable, IFilterable, ISortable
{
    public function __construct(?Article $model = null)
    {
        parent::__construct($model ?? new Article());
    }

    public function searchByQuery(?string $query, ?Builder $builder = null): ?Builder
    {
        $builder = $builder ?? $this->builder();

        return $builder->where('title', 'like', '%' . $query . '%');
    }

    public function filter(?array $filter = [], ?Builder $builder = null): ?Builder
    {
        $builder = $builder ?? $this->builder();

        if (!empty($filter['priority'])) {
            $builder->where('priority', (int) $filter['priority']);
        }

        return $builder;
    }

    public function sort(?string $sort = null, ?Builder $builder = null): ?Builder
    {
        $builder = $builder ?? $this->builder();

        $direction = str_starts_with((string) $sort, '-') ? 'desc' : 'asc';
        $field = ltrim((string) $sort, '-');

        return $builder->orderBy($field, $direction);
    }
}
