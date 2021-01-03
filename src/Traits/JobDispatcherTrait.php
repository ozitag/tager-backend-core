<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Illuminate\Foundation\Bus\DispatchesJobs;
use OZiTAG\Tager\Backend\Core\Events\JobStarted;
use OZiTAG\Tager\Backend\Core\Events\OperationStarted;

trait JobDispatcherTrait
{
    use ExceptionHandler, DispatchesJobs;

    /**
     * @param string $job
     * @param array $arguments
     * @return mixed
     */
    protected function runWithHandler(string $job, array $arguments = []) {
        return $this->withExceptionHandler(fn () => $this->run($job, $arguments));
    }

    /**
     * @param $job
     * @param array $arguments
     * @return mixed
     */
    public function run($job, array $arguments = [])
    {
        if (is_object($job)) {
            return $this->dispatchNow($job);
        }
        return $this->dispatchNow( new $job(...$arguments) );
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
        // instantiate and queue the job
        $reflection = new \ReflectionClass($job);
        $jobInstance = $reflection->newInstanceArgs($arguments);
        $jobInstance->onQueue((string) $queue);
        return $this->dispatch($jobInstance);
    }
}
