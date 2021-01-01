<?php

namespace OZiTAG\Tager\Backend\Core\Controllers;

use OZiTAG\Tager\Backend\Core\Traits\FeatureDispatcherTrait;
use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use FeatureDispatcherTrait;
}
