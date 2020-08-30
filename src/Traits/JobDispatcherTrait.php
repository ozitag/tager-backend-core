<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use OZiTAG\Tager\Backend\Core\Events\JobStarted;
use OZiTAG\Tager\Backend\Core\Events\OperationStarted;

trait JobDispatcherTrait
{
    use ExceptionHandler;
    /**
     * @param string $job
     * @param array $arguments
     * @return mixed
     */
    protected function runWithHandler(string $job, array $arguments = []) {
        return $this->withExceptionHandler(fn () => $this->run($job, $arguments));
    }

    /**
     * beautifier function to be called instead of the
     * laravel function dispatchFromArray.
     * When the $arguments is an instance of Request
     * it will call dispatchFrom instead.
     *
     * @param string                         $job
     * @param array|Request $arguments
     * @param array                          $extra
     *
     * @return mixed
     */
    public function run($job, $arguments = [], $extra = [])
    {
        if ($arguments instanceof Request) {
            $result = $this->dispatch($this->marshal($job, $arguments, $extra));
        } else {
            if (!is_object($job)) {
                $job = $this->marshal($job, new Collection(), $arguments);
            }
            if ($job instanceof Operation) {
                event(new OperationStarted(get_class($job), $arguments));
            }
            if ($job instanceof Job) {
                event(new JobStarted(get_class($job), $arguments));
            }
            $result = $this->dispatch($job, $arguments);
        }
        return $result;
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
        $reflection = new ReflectionClass($job);
        $jobInstance = $reflection->newInstanceArgs($arguments);
        $jobInstance->onQueue((string) $queue);
        return $this->dispatch($jobInstance);
    }
}
