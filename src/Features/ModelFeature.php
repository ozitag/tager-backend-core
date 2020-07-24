<?php

namespace OZiTAG\Tager\Backend\Core\Features;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;

class ModelFeature extends Feature
{
    /** @var string */
    private $jobGetByIdClass;

    /** @var EloquentRepository */
    private $repository;

    /** @var integer */
    protected $id;

    public function __construct($id, $jobGetByIdClass, ?EloquentRepository $repository = null)
    {
        $this->id = $id;

        $this->jobGetByIdClass = $jobGetByIdClass;

        $this->repository = $repository;
    }

    protected function model()
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
