<?php

namespace OZiTAG\Tager\Backend\Core\Models;

use \Illuminate\Database\Eloquent\Model as BaseModel;
use OZiTAG\Tager\Backend\Core\Models\Traits\FilterableAttributes;

class TModel extends BaseModel
{
    use FilterableAttributes;
}
