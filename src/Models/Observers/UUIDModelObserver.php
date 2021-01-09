<?php

namespace OZiTAG\Tager\Backend\Core\Models\Observers;

use App\Helpers\ModelHelper;
use App\Models\UUIDModel;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Class UUIDModelObserver
 *
 * @package App\Observers
 */
final class UUIDModelObserver
{
    public function creating(TModel $model)
    {
        $model->uuid = Str::uuid();

        return null;
    }
}
