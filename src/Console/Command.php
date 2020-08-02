<?php

namespace OZiTAG\Tager\Backend\Core\Console;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use OZiTAG\Tager\Backend\Core\Events\JobStarted;
use OZiTAG\Tager\Backend\Core\Events\OperationStarted;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;

abstract class Command extends BaseCommand
{
    use DispatchesJobs;
    use MarshalTrait;

    abstract function handle();

    /**
     * beautifier function to be called instead of the
     * laravel function dispatchFromArray.
     * When the $arguments is an instance of Request
     * it will call dispatchFrom instead.
     *
     * @param string $job
     * @param array $arguments
     *
     * @return mixed
     */
    public function runJob($job, $arguments = [])
    {
        if (!is_object($job)) {
            $job = $this->marshal($job, new Collection(), $arguments);
        }

        if ($job instanceof Operation) {
            event(new OperationStarted(get_class($job), $arguments));
        }

        if ($job instanceof Job) {
            event(new JobStarted(get_class($job), $arguments));
        }

        return $this->dispatch($job, $arguments);
    }

    /**
     * Run the given job in the given queue.
     *
     * @param string $job
     * @param array $arguments
     * @param string $queue
     *
     * @return mixed
     * @throws \ReflectionException
     */
    public function runInQueue($job, array $arguments = [], $queue = 'default')
    {
        $reflection = new \ReflectionClass($job);
        $jobInstance = $reflection->newInstanceArgs($arguments);
        $jobInstance->onQueue((string)$queue);
        return $this->dispatch($jobInstance);
    }
}
