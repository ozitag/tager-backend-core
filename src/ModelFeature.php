<?php

namespace OZiTAG\Tager\Backend\Core;

class ModelFeature extends Feature
{
    private $jobGetByIdClass;

    protected $id;

    public function __construct($id, $jobGetByIdClass)
    {
        $this->id = $id;

        $this->jobGetByIdClass = $jobGetByIdClass;
    }

    protected function model()
    {
        $model = $this->run($this->jobGetByIdClass, ['id' => $this->id]);

        if (!$model) {
            abort(404, 'Not Found');
        }

        return $model;
    }
}
