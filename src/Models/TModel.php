<?php

namespace OZiTAG\Tager\Backend\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Core\Models\Traits\FilterableAttributes;

class TModel extends BaseModel
{
    use FilterableAttributes;

    static $defaultOrder = null;

    protected static function boot()
    {
        parent::boot();

        if (static::$defaultOrder) {
            $defaultOrderParts = explode(' ', static::$defaultOrder);
            if (count($defaultOrderParts) == 2 && in_array(strtoupper($defaultOrderParts[1]), ['ASC', 'DESC'])) {
                static::addGlobalScope('order', function (Builder $builder) use ($defaultOrderParts) {
                    $builder->orderBy($defaultOrderParts[0], $defaultOrderParts[1]);
                });
            }
        }
    }
}
