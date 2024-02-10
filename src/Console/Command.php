<?php

namespace OZiTAG\Tager\Backend\Core\Console;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;
use OZiTAG\Tager\Backend\Utils\Helpers\DateHelper;

abstract class Command extends BaseCommand
{
    use DispatchesJobs;

    protected string $log = '';

    protected int $logSavePortion = 3;

    protected int $logNum = 0;

    protected bool $lineCompleted = true;

    protected array|\Closure|null $logCallback = null;

    /**
     * beautifier function to be called instead of the
     * laravel function dispatchFromArray.
     * When the $arguments is an instance of Request
     * it will call dispatchFrom instead.
     */
    public function runJob(string|object $job, array $arguments = []): mixed
    {
        if (is_object($job)) {
            return app(Dispatcher::class)->dispatchSync($job);
        } else {
            return app(Dispatcher::class)->dispatchSync(new $job(...$arguments));
        }
    }

    /**
     * Run the given job in the given queue.
     */
    public function runInQueue(string|object $job, array $arguments = [], string $queue = null): mixed
    {
        $reflection = new \ReflectionClass($job);
        $jobInstance = $reflection->newInstanceArgs($arguments);

        if ($queue) {
            $jobInstance->onQueue($queue);
        }

        Log::channel('queue')->info('Run in Queue: ' . $jobInstance::class . ' - ' . json_encode($arguments));

        return $this->dispatch($jobInstance);
    }

    protected function setLogCallback(callable $callback): void
    {
        $this->logCallback = $callback;
    }

    private function triggerSaveLogForCallback(): void
    {
        if ($this->logCallback) {
            call_user_func($this->logCallback, $this->log);
        }
    }

    protected function write(string $message): void
    {
        echo $message . "\n";
    }

    protected function getLogMessage(string $message, bool $lineComplete = true): string
    {
        $prefix = ($this->lineCompleted ? DateHelper::getHumanDateTime() . ' - ' : '');
        $logMessage = $prefix . $message;

        if ($lineComplete) {
            $logMessage .= "\n";
            $this->lineCompleted = true;
        } else {
            $this->lineCompleted = false;
        }

        return $logMessage;
    }

    protected function log(string $message, bool $lineComplete = true): void
    {
        $logMessage = $this->getLogMessage($message, $lineComplete);

        echo $logMessage;

        $this->log .= $logMessage;

        $this->logNum = $this->logNum + 1;

        if ($this->logNum == $this->logSavePortion) {
            $this->triggerSaveLogForCallback();
            $this->logNum = 0;
        }
    }
}
