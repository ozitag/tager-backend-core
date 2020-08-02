<?php

namespace OZiTAG\Tager\Backend\Core\Jobs;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Core\Traits\JobDispatcherTrait;
use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;
use OZiTAG\Tager\Backend\Core\Traits\UserAccess;

abstract class Operation
{
    use MarshalTrait;
    use DispatchesJobs;
    use JobDispatcherTrait;
    use UserAccess;
}
