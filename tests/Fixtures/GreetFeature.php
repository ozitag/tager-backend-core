<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Features\Feature;

class GreetFeature extends Feature
{
    public function __construct(
        private string $id,
        private string $greeting,
        private string $suffix = '!'
    ) {
    }

    public function handle(): string
    {
        return $this->greeting . ' ' . $this->id . $this->suffix;
    }
}
