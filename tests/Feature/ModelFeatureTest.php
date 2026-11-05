<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Feature;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\Article;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\ArticleRepository;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\GetArticleByUuidJob;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\ShowArticleFeature;
use OZiTAG\Tager\Backend\Core\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * ModelFeature resolves a model through one of three strategies. The
 * int|string $id signature exists because string/UUID identifiers regressed
 * on the Laravel 13 upgrade, so each strategy is covered for both key types.
 */
class ModelFeatureTest extends TestCase
{
    #[Test]
    public function it_resolves_a_model_through_the_repository(): void
    {
        $article = Article::create(['title' => 'Via repository']);

        $feature = new ShowArticleFeature($article->id, null, new ArticleRepository());

        $this->assertTrue($article->is(dispatch_sync($feature)));
    }

    #[Test]
    public function it_resolves_a_model_through_an_explicit_query_builder(): void
    {
        $article = Article::create(['title' => 'Via builder']);

        $feature = new ShowArticleFeature($article->id, null, null, Article::query());

        $this->assertTrue($article->is(dispatch_sync($feature)));
    }

    #[Test]
    public function it_resolves_a_model_through_a_get_by_id_job(): void
    {
        $article = Article::create(['title' => 'Via job']);

        $feature = new ShowArticleFeature($article->uuid, GetArticleByUuidJob::class);

        $this->assertTrue($article->is(dispatch_sync($feature)));
    }

    #[Test]
    public function it_accepts_a_string_identifier(): void
    {
        $article = Article::create(['title' => 'String id']);

        $feature = new ShowArticleFeature((string) $article->id, null, new ArticleRepository());

        $this->assertTrue($article->is(dispatch_sync($feature)));
    }

    #[Test]
    public function it_aborts_with_404_when_the_repository_finds_nothing(): void
    {
        $this->expectException(NotFoundHttpException::class);

        dispatch_sync(new ShowArticleFeature(99999, null, new ArticleRepository()));
    }

    #[Test]
    public function it_aborts_with_404_when_the_builder_finds_nothing(): void
    {
        $this->expectException(NotFoundHttpException::class);

        dispatch_sync(new ShowArticleFeature(99999, null, null, Article::query()));
    }

    #[Test]
    public function it_aborts_with_404_when_the_job_returns_null(): void
    {
        $this->expectException(NotFoundHttpException::class);

        dispatch_sync(new ShowArticleFeature('no-such-uuid', GetArticleByUuidJob::class));
    }
}
