<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait JobDispatcherTrait
{
    use ExceptionHandler, DispatchesJobs;

    protected function runWithHandler(string $job, array $arguments = [])
    {
        return $this->withExceptionHandler(fn() => $this->run($job, $arguments));
    }

    public function run($job, $arguments = [], $extra = [])
    {
        if ($arguments instanceof Request) {
            $method = $job instanceof ShouldQueue ? 'dispatch' : 'dispatchNow';
            $result = $this->$method($this->marshal($job, $arguments, $extra));
        } else {
            if (!is_object($job)) {
                $job = $this->marshal($job, new Collection(), $arguments);
            }
            $method = $job instanceof ShouldQueue ? 'dispatch' : 'dispatchNow';
            $result = $this->$method($job, $arguments);
        }
        return $result;
    }

    public function runNow($job, array $arguments = [])
    {
        if (is_object($job)) {
            return $this->dispatchNow($job);
        }

        return $this->dispatchNow(new $job(...$arguments));
    }

    public function runInQueue($job, array $arguments = [], $queue = 'default')
    {
        $reflection = new \ReflectionClass($job);
        $jobInstance = $reflection->newInstanceArgs($arguments);
        $jobInstance->onQueue((string)$queue);
        return $this->dispatch($jobInstance);
    }
}
