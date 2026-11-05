<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Feature;

use OZiTAG\Tager\Backend\Core\Tests\Fixtures\Article;
use OZiTAG\Tager\Backend\Core\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HarnessSmokeTest extends TestCase
{
    #[Test]
    public function the_package_boots_under_the_current_laravel_version(): void
    {
        $this->assertSame(13, (int) explode('.', $this->app->version())[0]);
    }

    #[Test]
    public function the_fixture_schema_is_available(): void
    {
        $article = Article::create(['title' => 'Hello']);

        $this->assertSame('Hello', $article->fresh()->title);
    }
}
