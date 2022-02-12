<?php

namespace OZiTAG\Tager\Backend\Core\Models\Observers;

use OZiTAG\Tager\Backend\Core\Models\TModel;
use OZiTAG\Tager\Backend\Utils\Helpers\UUID;

/**
 * Class UUIDModelObserver
 *
 * @package App\Observers
 */
final class UUIDModelObserver
{
    public function creating(TModel $model)
    {
        $model->uuid = UUID::get(4);

        return null;
    }
}
