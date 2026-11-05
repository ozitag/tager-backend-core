<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Jobs\Job;

class SumJob extends Job
{
    public function __construct(private int $a, private int $b = 10)
    {
    }

    public function handle(): int
    {
        return $this->a + $this->b;
    }
}
