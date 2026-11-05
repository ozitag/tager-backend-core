<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Feature;

use OZiTAG\Tager\Backend\Core\Tests\Fixtures\Article;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\PlainArticle;
use OZiTAG\Tager\Backend\Core\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Regression cover for TModel::boot(). Both features below broke on the
 * Laravel 13 upgrade (2.0.0 -> 2.0.1), so they are pinned explicitly.
 */
class TModelTest extends TestCase
{
    #[Test]
    public function a_uuid_model_boots_and_receives_a_uuid_on_create(): void
    {
        $article = Article::create(['title' => 'With UUID']);

        $this->assertNotNull($article->uuid, 'UUID was not assigned by the creating hook');
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
            $article->uuid
        );
    }

    #[Test]
    public function the_uuid_hook_does_not_overwrite_an_existing_uuid_on_update(): void
    {
        $article = Article::create(['title' => 'Keep me']);
        $original = $article->uuid;

        $article->update(['title' => 'Renamed']);

        $this->assertSame($original, $article->fresh()->uuid);
    }

    #[Test]
    public function booting_a_uuid_model_does_not_instantiate_the_model_during_boot(): void
    {
        // Laravel 13 throws a LogicException if boot() touches the model being
        // booted. Resolving the model fresh is what triggers that path.
        Article::clearBootedModels();

        $article = new Article();

        $this->assertInstanceOf(Article::class, $article);
    }

    #[Test]
    public function default_order_is_applied_as_a_global_scope(): void
    {
        PlainArticle::create(['title' => 'low', 'priority' => 1]);
        PlainArticle::create(['title' => 'high', 'priority' => 10]);
        PlainArticle::create(['title' => 'mid', 'priority' => 5]);

        $titles = PlainArticle::query()->pluck('title')->all();

        $this->assertSame(['high', 'mid', 'low'], $titles);
    }

    #[Test]
    public function default_order_without_a_dot_is_qualified_with_the_table_name(): void
    {
        $sql = PlainArticle::query()->toSql();

        $this->assertStringContainsString('articles.priority desc', $sql);
    }
}
