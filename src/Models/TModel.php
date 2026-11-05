<?php

namespace OZiTAG\Tager\Backend\Core\Models;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Model as BaseModel;
use OZiTAG\Tager\Backend\Core\Models\Observers\UUIDModelObserver;

class TModel extends BaseModel
{
    static string $defaultOrder = '';

    static bool $hasUUID = false;

    protected static function boot()
    {
        parent::boot();

        if (static::$hasUUID) {
            /**
             * Model::observe() создаёт экземпляр модели, а Laravel 13 запрещает
             * обращаться к модели во время её же boot(): bootIfNotBooted бросает
             * LogicException «may not be called on model ... while it is being booted».
             * Регистрируем единственный хук обсервера напрямую — модель при этом
             * не создаётся.
             */
            static::registerModelEvent('creating', UUIDModelObserver::class . '@creating');
        }

        if (static::$defaultOrder) {
            static::addGlobalScope('order', function (BuilderContract $builder) {
                if (str_contains(static::$defaultOrder, '.') === false) {
                    $defaultOrder = (app(static::class))->getTable() . '.' . static::$defaultOrder;
                } else {
                    $defaultOrder = static::$defaultOrder;
                }

                $builder->orderByRaw($defaultOrder);
            });
        }
    }


    /**
     * Set the keys for a save update query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery(?string $keyName = null): mixed
    {
        if (is_null($keyName)) {
            $keyName = $this->getKeyName();
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }

        return $this->getAttribute($keyName);
    }
}
