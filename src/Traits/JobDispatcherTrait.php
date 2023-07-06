<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

trait JobDispatcherTrait
{
    use ExceptionHandler, DispatchesJobs;

    protected static function marshal($command, array $extras = [])
    {
        $injected = [];

        $reflection = new \ReflectionClass($command);
        if ($constructor = $reflection->getConstructor()) {
            $injected = array_map(function ($parameter) use ($command, $extras) {
                return self::getParameterValueForCommand($command, $parameter, $extras);
            }, $constructor->getParameters());
        }

        return $reflection->newInstanceArgs($injected);
    }

    protected static function getParameterValueForCommand($command, \ReflectionParameter $parameter, array $extras = [])
    {
        if (array_key_exists($parameter->name, $extras)) {
            return $extras[$parameter->name];
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new \Exception("Unable to map parameter [{$parameter->name}] to command [{$command}]");
    }

    protected function runWithHandler($job, array $arguments = [])
    {
        return $this->withExceptionHandler(fn() => $this->run($job, $arguments));
    }

    public function run($job, array $arguments = [])
    {
        if (!is_object($job)) {
            $job = $this->marshal($job, $arguments);
        }

        if ($job instanceof ShouldQueue) {
            return $this->runInQueue($job, $arguments);
        } else {
            return $this->dispatchSync($job);
        }
    }

    public function runNow($job, array $arguments = [])
    {
        if (!is_object($job)) {
            $job = $this->marshal($job, $arguments);
        }

        return $this->dispatchSync($job);
    }

    public function runInQueue($job, array $arguments = [], string $queue = null)
    {
        if (!is_object($job)) {
            $job = $this->marshal($job, $arguments);
        }

        Log::channel('queue')->info('Run in Queue: ' . $job::class . ' - ' . json_encode($arguments));

        if ($queue) {
            $job->onQueue($queue);
        }

        return $this->dispatch($job);
    }
}
