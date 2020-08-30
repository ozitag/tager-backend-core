<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait ExceptionHandler
{
    /**
     * @param \Closure $callback
     * @param string $logLevel
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function withExceptionHandler(\Closure $callback, string $logLevel = 'error')
    {
        try {
            return $callback();
        } catch (\Exception $exception) {
            $this->logException($exception, $logLevel)
                ->resolveException($exception);
        }
    }

    /**
     * @param \Exception $exception
     * @param string $logLevel
     * @return $this
     */
    protected function logException(\Exception $exception, string $logLevel) {
        Log::$logLevel($exception->getMessage(), $exception->getFile(), $exception->getLine());
        return $this;
    }

    /**
     * @param \Exception $exception
     */
    protected function resolveException(\Exception $exception) {
        throw new $exception;
    }
}
