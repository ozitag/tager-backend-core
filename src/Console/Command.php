<?php

namespace OZiTAG\Tager\Backend\Core\Console;

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;
use OZiTAG\Tager\Backend\Core\Events\JobStarted;
use OZiTAG\Tager\Backend\Core\Events\OperationStarted;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Core\Traits\ExceptionHandler;
use OZiTAG\Tager\Backend\Core\Traits\MarshalTrait;
use OZiTAG\Tager\Backend\Utils\Helpers\DateHelper;

abstract class Command extends BaseCommand
{
    use DispatchesJobs;

    /** @var string */
    protected $log;

    /** @var int */
    protected $logSavePortion = 3;

    /** @var int */
    private $logNum = 0;

    /** @var bool */
    private $lineCompleted = true;

    /** @var \Closure */
    private $logCallback;

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
        $reflection = new \ReflectionClass($job);
        $jobInstance = $reflection->newInstanceArgs($arguments);
        $jobInstance->onQueue((string)$queue);
        return $this->dispatch($jobInstance);
    }

    protected function setLogCallback($callback)
    {
        $this->logCallback = $callback;
    }

    private function triggerSaveLogForCallback()
    {
        if ($this->logCallback) {
            call_user_func($this->logCallback, $this->log);
        }
    }

    protected function write($message)
    {
        echo $message . "\n";
    }

    protected function log($message, $lineComplete = true)
    {
        $prefix = ($this->lineCompleted ? DateHelper::getHumanDateTime() . ' - ' : '');
        $logMessage = $prefix . $message;

        if ($lineComplete) {
            $logMessage .= "\n";
            $this->lineCompleted = true;
        } else {
            $this->lineCompleted = false;
        }

        echo $logMessage;

        $this->log .= $logMessage;

        $this->logNum = $this->logNum + 1;

        if ($this->logNum == $this->logSavePortion) {
            $this->triggerSaveLogForCallback();
            $this->logNum = 0;
        }
    }
}
