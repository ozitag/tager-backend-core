<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Feature;

use Illuminate\Http\Request;
use OZiTAG\Tager\Backend\Core\Facades\Pagination;
use OZiTAG\Tager\Backend\Core\Pagination\Paginator;
use OZiTAG\Tager\Backend\Core\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * The admin UI sends page-based parameters, public endpoints send
 * offset-based ones, and the two modes produce different meta shapes.
 */
class PaginationTest extends TestCase
{
    private function withQuery(string $query): void
    {
        $this->app->instance('request', Request::create('/?' . $query));
    }

    #[Test]
    public function it_defaults_to_page_mode_with_a_zero_based_page_index(): void
    {
        $this->withQuery('');

        $this->assertFalse(Pagination::isOffsetBased());
        $this->assertSame(0, Pagination::page());
        $this->assertSame(100, Pagination::perPage(), 'default_page_size fallback');
    }

    #[Test]
    public function page_number_is_translated_from_one_based_to_zero_based(): void
    {
        $this->withQuery('pageNumber=3&pageSize=25');

        $this->assertTrue(Pagination::isPageBased());
        $this->assertSame(2, Pagination::page());
        $this->assertSame(25, Pagination::perPage());
    }

    #[Test]
    public function an_out_of_range_page_number_clamps_to_the_first_page(): void
    {
        $this->withQuery('pageNumber=0');
        $this->assertSame(0, Pagination::page());

        $this->withQuery('pageNumber=-5');
        $this->assertSame(0, Pagination::page());
    }

    #[Test]
    public function the_presence_of_page_offset_switches_the_helper_to_offset_mode(): void
    {
        $this->withQuery('pageOffset=40&pageLimit=20');

        $this->assertTrue(Pagination::isOffsetBased());
        $this->assertSame(40, Pagination::offset());
        $this->assertSame(20, Pagination::perPage(), 'offset mode reads pageLimit, not pageSize');
        $this->assertSame(0, Pagination::page(), 'page index is meaningless in offset mode');
    }

    #[Test]
    public function per_page_is_capped_by_the_configured_maximum(): void
    {
        config()->set('tager-app.pagination.max_page_size', 50);
        $this->withQuery('pageNumber=1&pageSize=1000');

        $this->assertSame(50, Pagination::perPage());
    }

    #[Test]
    public function an_unset_maximum_means_no_cap(): void
    {
        config()->set('tager-app.pagination.max_page_size', -1);
        $this->withQuery('pageNumber=1&pageSize=100000');

        $this->assertSame(100000, Pagination::perPage());
    }

    #[Test]
    public function paginator_meta_in_page_mode_reports_a_one_based_number_and_page_count(): void
    {
        $this->withQuery('pageNumber=2&pageSize=10');

        $meta = (new Paginator([], 95))->getMeta();

        $this->assertSame(95, $meta['total']);
        $this->assertSame(2, $meta['page']['number']);
        $this->assertSame(10, $meta['page']['size']);
        $this->assertSame(10.0, $meta['page']['count'], 'ceil(95 / 10)');
    }

    #[Test]
    public function paginator_meta_in_offset_mode_reports_offset_and_limit(): void
    {
        $this->withQuery('pageOffset=20&pageLimit=5');

        $meta = (new Paginator([], 42))->getMeta();

        $this->assertSame(42, $meta['total']);
        $this->assertSame(['offset' => 20, 'limit' => 5], $meta['page']);
        $this->assertArrayNotHasKey('count', $meta['page']);
    }

    #[Test]
    public function paginator_meta_does_not_divide_by_zero_when_per_page_is_zero(): void
    {
        $this->withQuery('pageNumber=1&pageSize=0');

        $meta = (new Paginator([], 10))->getMeta();

        $this->assertSame(10.0, $meta['page']['count']);
    }
}
