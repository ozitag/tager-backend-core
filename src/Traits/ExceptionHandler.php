<?php

namespace OZiTAG\Tager\Backend\Core\Traits;

use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

trait ExceptionHandler
{
    /**
     * @param Closure $callback
     * @param string $logLevel
     * @return Authenticatable|null
     */
    protected function withExceptionHandler(Closure $callback, string $logLevel = 'error')
    {
        try {
            return $callback();
        } catch (Throwable $exception) {
            return $this->logException($exception, $logLevel)
                ->resolveException($exception);
        }
    }

    /**
     * @param Throwable $exception
     * @param string $logLevel
     * @return $this
     */
    protected function logException(Throwable $exception, string $logLevel)
    {
        Log::$logLevel([$exception->getMessage(), $exception->getFile(), $exception->getLine()]);
        return $this;
    }

    /**
     * @param Throwable $exception
     */
    protected function resolveException(Throwable $exception)
    {
        throw new $exception;
    }
}
