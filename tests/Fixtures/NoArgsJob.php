<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Jobs\Job;

class NoArgsJob extends Job
{
    public function handle(): string
    {
        return 'no-args';
    }
}
