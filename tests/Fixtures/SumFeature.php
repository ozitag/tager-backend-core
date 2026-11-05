<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Features\Feature;

class SumFeature extends Feature
{
    public function __construct(private int $a, private int $b)
    {
    }

    public function handle(): int
    {
        return $this->run(SumJob::class, ['a' => $this->a, 'b' => $this->b]);
    }
}
