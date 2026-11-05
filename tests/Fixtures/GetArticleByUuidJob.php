<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Jobs\Job;

class GetArticleByUuidJob extends Job
{
    public function __construct(private int|string $id)
    {
    }

    public function handle(): ?Article
    {
        return Article::query()->where('uuid', $this->id)->first();
    }
}
