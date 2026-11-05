<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Models\TModel;

class Article extends TModel
{
    protected $table = 'articles';

    protected $guarded = [];

    public static bool $hasUUID = true;
}
