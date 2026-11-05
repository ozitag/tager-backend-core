<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Feature;

use Illuminate\Support\Facades\Bus;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\NoArgsJob;
use Illuminate\Foundation\Bus\PendingDispatch;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\SpyQueuedJob;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\SumFeature;
use OZiTAG\Tager\Backend\Core\Tests\Fixtures\SumJob;
use OZiTAG\Tager\Backend\Core\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * JobDispatcherTrait is the spine of the Feature/Job architecture: every
 * module in the ecosystem reaches the database through it.
 */
class JobDispatcherTest extends TestCase
{
    private function feature(): SumFeature
    {
        return new SumFeature(1, 2);
    }

    #[Test]
    public function it_marshals_named_arguments_into_the_job_constructor(): void
    {
        $result = $this->feature()->run(SumJob::class, ['a' => 2, 'b' => 3]);

        $this->assertSame(5, $result);
    }

    #[Test]
    public function it_falls_back_to_constructor_defaults_for_omitted_arguments(): void
    {
        $result = $this->feature()->run(SumJob::class, ['a' => 5]);

        $this->assertSame(15, $result, 'default $b = 10 was not applied');
    }

    #[Test]
    public function it_handles_a_job_without_a_constructor(): void
    {
        $this->assertSame('no-args', $this->feature()->run(NoArgsJob::class));
    }

    #[Test]
    public function it_throws_when_a_required_argument_cannot_be_mapped(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unable to map parameter [a]');

        $this->feature()->run(SumJob::class, ['b' => 1]);
    }

    #[Test]
    public function it_accepts_an_already_instantiated_job(): void
    {
        $this->assertSame(7, $this->feature()->run(new SumJob(3, 4)));
    }

    #[Test]
    public function run_now_executes_a_queueable_job_and_returns_its_result(): void
    {
        // runNow() goes through Dispatcher::dispatchNow(). dispatchSync() was
        // unusable here: it routes a ShouldQueue command onto the "sync"
        // connection and hands back the queue push result (0) instead of the
        // value handle() returned.
        SpyQueuedJob::reset();

        $result = $this->feature()->runNow(SpyQueuedJob::class);

        $this->assertSame(1, SpyQueuedJob::$runs);
        $this->assertSame('ran', $result);
    }

    #[Test]
    public function run_returns_a_pending_dispatch_for_a_should_queue_job(): void
    {
        // run() sends ShouldQueue jobs through runInQueue(), which returns a
        // PendingDispatch. The job is only dispatched when that object is
        // destructed, so the return value is never the job's result.
        SpyQueuedJob::reset();

        $result = $this->feature()->run(SpyQueuedJob::class);

        $this->assertInstanceOf(PendingDispatch::class, $result);
        $this->assertSame(0, SpyQueuedJob::$runs, 'dispatch is deferred until the PendingDispatch is destructed');
    }

    #[Test]
    public function run_routes_a_should_queue_job_to_the_queue(): void
    {
        Bus::fake();

        $this->feature()->run(SpyQueuedJob::class);

        Bus::assertDispatched(SpyQueuedJob::class);
    }

    #[Test]
    public function a_feature_dispatched_through_the_container_resolves_its_handle_arguments(): void
    {
        $this->assertSame(3, dispatch_sync(new SumFeature(1, 2)));
    }
}
