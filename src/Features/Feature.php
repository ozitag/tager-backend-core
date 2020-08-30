<?php

namespace OZiTAG\Tager\Backend\Core\Features;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Core\Traits\JobDispatcherTrait;
use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;
use OZiTAG\Tager\Backend\Core\Traits\UserAccess;

class Feature
{
    use MarshalTrait, DispatchesJobs, JobDispatcherTrait, UserAccess;
}
