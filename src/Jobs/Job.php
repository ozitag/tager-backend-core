<?php

namespace OZiTAG\Tager\Backend\Core\Jobs;

use OZiTAG\Tager\Backend\Core\Traits\JobDispatcherTrait;
use OZiTAG\Tager\Backend\Core\Traits\UserAccess;

abstract class Job
{
    use JobDispatcherTrait, UserAccess;
}
