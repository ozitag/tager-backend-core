<?php

namespace OZiTAG\Tager\Backend\Core\Features;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Model;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;

class ModelFeature extends Feature
{
    public function __construct(
        protected int                 $id,
        protected ?string             $jobGetByIdClass,
        protected ?EloquentRepository $repository = null,
        protected ?BuilderContract            $queryBuilder)
    {
    }

    protected function model(): Model
    {
        if ($this->jobGetByIdClass) {
            $model = $this->run($this->jobGetByIdClass, ['id' => $this->id]);
        } else if ($this->queryBuilder) {
            $model = $this->queryBuilder->where('id', $this->id)->first();
        } else {
            $model = $this->repository->find($this->id);
        }

        if (!$model) {
            abort(404, 'Not Found');
        }

        return $model;
    }
}
