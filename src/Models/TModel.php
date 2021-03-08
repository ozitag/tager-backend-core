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


    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if(!is_array($keys)){
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName){
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    /**
     * Get the primary key value for a save query.
     *
     * @param mixed $keyName
     * @return mixed
     */
    protected function getKeyForSaveQuery($keyName = null)
    {
        if(is_null($keyName)){
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
