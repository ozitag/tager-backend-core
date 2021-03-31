<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Core\Facades\Pagination;
use OZiTAG\Tager\Backend\Core\Pagination\Paginator;
use OZiTAG\Tager\Backend\Core\Structures\SortAttributeCollection;

class EloquentRepository implements IEloquentRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * EloquentRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function builder(): Builder
    {
        return $this->model::query();
    }

    /**
     * @param array $defaultValues
     * @return Model
     */
    public function createModelInstance($defaultValues = []): Model
    {
        $className = get_class($this->model);

        $this->model = new $className;
        foreach ($defaultValues as $param => $value) {
            $this->model->{$param} = $value;
        }

        return $this->model;
    }

    public function reset()
    {
        $this->createModelInstance();
        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function set(Model $model): Model
    {
        $this->model = $model;
        return $this->model;
    }

    public function setById($id)
    {
        $this->model = $this->find($id);
        return $this->model;
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Returns the first record in the database.
     *
     * @return Model
     */
    public function first(): ?Model
    {
        return $this->model->first();
    }

    /**
     * Returns all the records.
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param false $paginate
     * @param string|null $query
     * @param array|null $filter
     * @param SortAttributeCollection|null $sortAttributes
     * @return Builder[]|Collection|Model[]|null[]|Paginator
     */
    public function get($paginate = false, ?string $query = null, ?array $filter = [], ?SortAttributeCollection $sortAttributes = null)
    {
        $builder = $query && $this instanceof ISearchable
            ? $this->searchByQuery($query)
            : $this->model->query();

        $builder = $filter && $this instanceof IFilterable
            ? $this->filter($filter, $builder)
            : $builder;

        $builder = $sortAttributes && $this instanceof ISortable
            ? $this->sort($sortAttributes, $builder)
            : $builder;

        if (!$paginate) {
            return $builder->get();
        }

        return $this->paginate($builder);
    }

    /**
     * @param false $paginate
     * @param string|null $query
     * @param array|null $filter
     * @return Paginator
     */
    public function toFlatTree($paginate = false, ?string $query = null, ?array $filter = [])
    {
        $builder = $query && $this instanceof ISearchable ? $this->searchByQuery($query) : $this->model->query();

        $builder = $filter && $this instanceof IFilterable
            ? $this->filter($filter, $builder)
            : $builder;

        $builder = $builder->withDepth()->defaultOrder();

        if (!$paginate) {
            return $builder->get()->toFlatTree();
        }

        $count = (clone $builder)->get()->count();

        return new Paginator(
            $builder->offset(
                Pagination::isOffsetBased()
                    ? Pagination::offset()
                    : Pagination::page() * Pagination::perPage()
            )
                ->limit(Pagination::perPage())
                ->get()->toFlatTree(),
            $count
        );
    }

    /**
     * @param Builder $builder
     * @return Paginator
     */
    public function paginate(Builder $builder = null)
    {
        $builder = $builder ? $builder : $this->model;
        $count = (clone $builder)->get()->count();

        return new Paginator(
            $builder->offset(
                Pagination::isOffsetBased()
                    ? Pagination::offset()
                    : Pagination::page() * Pagination::perPage()
            )
                ->limit(Pagination::perPage())
                ->get(),
            $count
        );
    }

    public function filter(?array $filter = [], ?Builder $builder = null): ?Builder
    {
        $builder = $builder ?? $this->model;
        if (!$filter || empty($filter)) {
            return $builder;
        }

        foreach ($filter as $key => $value) {
            if (is_string($value) && strlen($value) > 0) {
                $builder = $this->filterByKey($builder, (string)$key, $value);
            }
        }

        return $builder;
    }

    public function filterByKey(Builder $builder, string $key, mixed $value): Builder
    {
        return $builder;
    }

    /**
     * Returns the count of all the records.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * @param $attributes
     * @return Model
     */
    public function fillAndSave($attributes)
    {
        $this->model->fill($attributes);
        $this->model->save();

        return $this->model;
    }

    /**
     * @param $attributes
     * @return Model|null
     */
    public function update($attributes)
    {
        $this->model->update($attributes);
        return $this->model->fresh();
    }
}
