<?php

namespace OZiTAG\Tager\Backend\Core\Features;

use Illuminate\Database\Eloquent\Model;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;

class ModelFeature extends Feature
{
    protected ?string $jobGetByIdClass;

    protected ?EloquentRepository $repository;

    protected int $id;

    public function __construct(int $id, ?string $jobGetByIdClass, ?EloquentRepository $repository = null)
    {
        $this->id = $id;

        $this->jobGetByIdClass = $jobGetByIdClass;

        $this->repository = $repository;
    }

    protected function model(): Model
    {
        if ($this->jobGetByIdClass) {
            $model = $this->run($this->jobGetByIdClass, ['id' => $this->id]);
        } else {
            $model = $this->repository->find($this->id);
        }

        if (!$model) {
            abort(404, 'Not Found');
        }

        return $model;
    }
}
