<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Feature;

use Illuminate\Http\Request;
use OZiTAG\Tager\Backend\Core\Pagination\Paginator;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\Article;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\ArticleRepository;
use OZiTAG\Tager\Backend\Core\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class EloquentRepositoryTest extends TestCase
{
    private ArticleRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ArticleRepository();
    }

    private function seedArticles(): void
    {
        Article::create(['title' => 'Alpha', 'priority' => 1]);
        Article::create(['title' => 'Beta', 'priority' => 2]);
        Article::create(['title' => 'Alpha Two', 'priority' => 2]);
    }

    private function withQuery(string $query): void
    {
        $this->app->instance('request', Request::create('/?' . $query));
    }

    #[Test]
    public function it_exposes_the_underlying_table_name(): void
    {
        $this->assertSame('articles', $this->repository->getTableName());
    }

    #[Test]
    public function find_returns_null_instead_of_throwing_for_a_missing_id(): void
    {
        $this->assertNull($this->repository->find(404));
    }

    #[Test]
    public function set_by_id_throws_a_catchable_exception_for_a_missing_id(): void
    {
        // Regression: the null returned by find() used to be assigned to the
        // typed property `protected Model $model` first, raising a TypeError
        // and leaving the intended guard below it as dead code. TypeError
        // extends Error, so `catch (\Exception)` in calling code missed it and
        // the request 500'd.
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Model with id 404 not found');

        $this->repository->setById(404);
    }

    #[Test]
    public function set_by_id_accepts_a_string_identifier(): void
    {
        $article = Article::create(['title' => 'String key']);

        $this->assertTrue(
            $article->is($this->repository->setById((string) $article->id)->find($article->id))
        );
    }

    #[Test]
    public function set_by_id_reports_a_missing_string_identifier_the_same_way(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Model with id some-uuid not found');

        $this->repository->setById('some-uuid');
    }

    #[Test]
    public function set_by_id_leaves_the_previous_model_in_place_when_it_throws(): void
    {
        $article = Article::create(['title' => 'Still here']);
        $this->repository->set($article);

        try {
            $this->repository->setById(404);
        } catch (\Exception) {
            // expected
        }

        $this->assertTrue($article->is($this->repository->find($article->id)));
    }

    #[Test]
    public function create_model_instance_applies_default_values_without_persisting(): void
    {
        $model = $this->repository->createModelInstance(['title' => 'Draft']);

        $this->assertSame('Draft', $model->title);
        $this->assertFalse($model->exists);
        $this->assertSame(0, Article::query()->count());
    }

    #[Test]
    public function get_applies_the_searchable_contract(): void
    {
        $this->seedArticles();

        $titles = $this->repository->get(null, false, 'Alpha')->pluck('title')->all();

        sort($titles);
        $this->assertSame(['Alpha', 'Alpha Two'], $titles);
    }

    #[Test]
    public function get_applies_the_filterable_contract(): void
    {
        $this->seedArticles();

        $titles = $this->repository->get(null, false, null, ['priority' => 2])->pluck('title')->all();

        sort($titles);
        $this->assertSame(['Alpha Two', 'Beta'], $titles);
    }

    #[Test]
    public function get_applies_the_sortable_contract(): void
    {
        $this->seedArticles();

        $titles = $this->repository->get(null, false, null, [], '-title')->pluck('title')->all();

        $this->assertSame(['Beta', 'Alpha Two', 'Alpha'], $titles);
    }

    #[Test]
    public function paginate_returns_a_paginator_carrying_the_unpaginated_total(): void
    {
        $this->seedArticles();
        $this->withQuery('pageNumber=1&pageSize=2');

        $result = $this->repository->paginate($this->repository->builder());

        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertCount(2, $result, 'page is limited to pageSize');
        $this->assertSame(3, $result->getMeta()['total'], 'total counts every matching row');
    }

    #[Test]
    public function paginate_honours_the_second_page(): void
    {
        $this->seedArticles();
        $this->withQuery('pageNumber=2&pageSize=2');

        $result = $this->repository->paginate($this->repository->builder());

        $this->assertCount(1, $result);
    }

    #[Test]
    public function paginate_honours_offset_mode(): void
    {
        $this->seedArticles();
        $this->withQuery('pageOffset=1&pageLimit=10');

        $result = $this->repository->paginate($this->repository->builder());

        $this->assertCount(2, $result);
        $this->assertSame(3, $result->getMeta()['total']);
    }

    #[Test]
    public function get_combines_search_filter_and_sort(): void
    {
        $this->seedArticles();

        $titles = $this->repository
            ->get(null, false, 'Alpha', ['priority' => 2], 'title')
            ->pluck('title')->all();

        $this->assertSame(['Alpha Two'], $titles);
    }
}
