<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;

class SpyQueuedJob extends QueueJob
{
    public static int $runs = 0;

    public static function reset(): void
    {
        static::$runs = 0;
    }

    public function handle(): string
    {
        static::$runs++;

        return 'ran';
    }
}
