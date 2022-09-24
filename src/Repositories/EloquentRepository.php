<?php

namespace OZiTAG\Tager\Backend\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Core\Facades\Pagination;
use OZiTAG\Tager\Backend\Core\Pagination\Paginator;
use OZiTAG\Tager\Backend\Core\Structures\SortAttributeCollection;

class EloquentRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function builder(): Builder
    {
        return $this->model::query();
    }

    public function adminBuilder(): Builder
    {
        return $this->builder();
    }

    public function createModelInstance(array $defaultValues = []): Model
    {
        $className = get_class($this->model);

        $this->model = new $className;
        foreach ($defaultValues as $param => $value) {
            $this->model->{$param} = $value;
        }

        return $this->model;
    }

    public function reset(): self
    {
        $this->createModelInstance();
        return $this;
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function set(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function setById(int $id): self
    {
        $this->model = $this->find($id);

        if (!$this->model) {
            throw new \Exception('Model with id ' . $id . ' not found');
        }

        return $this->set($this->model);
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

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function get(?Builder $builder = null, bool $paginate = false, ?string $query = null, ?array $filter = [], ?string $sort = null)
    {
        $builder = $builder ?: $this->builder();
        $builder = $query && $this instanceof ISearchable
            ? $this->searchByQuery($query)
            : $builder;

        $builder = $filter && $this instanceof IFilterable
            ? $this->filter($filter, $builder)
            : $builder;

        $builder = $sort && $this instanceof ISortable
            ? $this->sort($sort, $builder)
            : $builder;

        return $paginate ? $this->paginate($builder) : $builder->get();
    }

    public function toFlatTree(?Builder $builder = null, bool $paginate = false, ?string $query = null, ?array $filter = [], ?string $sort = null)
    {
        $builder = $builder ?: $this->builder();

        $builder = $filter && $this instanceof IFilterable
            ? $this->filter($filter, $builder)
            : $builder;

        $builder = $sort && $this instanceof ISortable
            ? $this->sort($sort, $builder)
            : $builder;

        $builder = $builder->withDepth()->defaultOrder();

        if ($query || $filter) {
            if (!$paginate) {
                return $builder->get();
            } else {
                return $this->paginate($builder);
            }
        }

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
                ->get(),
            $count
        );
    }

    public function paginate(Builder $builder = null): Paginator
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

    public function count(): int
    {
        return $this->model->count();
    }

    public function fillAndSave(array $attributes): Model
    {
        $this->model->fill($attributes);
        $this->model->save();

        return $this->model;
    }

    public function update(array $attributes): ?Model
    {
        $this->model->update($attributes);
        return $this->model->fresh();
    }
}
