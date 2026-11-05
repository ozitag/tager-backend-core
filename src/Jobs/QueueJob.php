<?php

namespace OZiTAG\Tager\Backend\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


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

    /**
     * Run a nested job immediately and return its handler's result.
     *
     * See JobDispatcherTrait::runNow() for why dispatch_sync() is unsuitable
     * when the nested job is itself queueable.
     */
    public function run($job, array $arguments = [])
    {
        if (!is_object($job)) {
            $job = $this->marshal($job, $arguments);
        }

        return app(Dispatcher::class)->dispatchNow($job);
    }
}
