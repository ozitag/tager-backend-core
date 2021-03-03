<?php

namespace OZiTAG\Tager\Backend\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use OZiTAG\Tager\Backend\Core\Models\Observers\UUIDModelObserver;

class TModel extends BaseModel
{
    static $defaultOrder = null;

    static $hasUUID = false;

    protected static function boot()
    {
        parent::boot();

        if(static::$hasUUID){
            self::observe(UUIDModelObserver::class);
        }

        if (static::$defaultOrder) {
            static::addGlobalScope('order', function (Builder $builder) {
                $builder->orderByRaw(static::$defaultOrder);
            });
        }
    }
}
