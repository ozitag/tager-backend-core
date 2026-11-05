<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Features\Feature;

class StaticFeature extends Feature
{
    public function handle(): string
    {
        return 'static';
    }
}
