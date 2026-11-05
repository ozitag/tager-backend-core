<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use OZiTAG\Tager\Backend\Core\Features\ModelFeature;

class ShowArticleFeature extends ModelFeature
{
    public function handle(): Model
    {
        return $this->model();
    }
}
