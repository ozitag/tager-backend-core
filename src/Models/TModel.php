<?php

namespace OZiTAG\Tager\Backend\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Core\Models\Observers\UUIDModelObserver;
use OZiTAG\Tager\Backend\Core\Models\Traits\FilterableAttributes;

class TModel extends BaseModel
{
    use FilterableAttributes;

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
