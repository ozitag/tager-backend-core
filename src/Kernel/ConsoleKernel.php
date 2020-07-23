<?php

namespace OZiTAG\Tager\Backend\Core\Kernel;

use Illuminate\Foundation\Console\Kernel as BaseConsoleKernel;

class ConsoleKernel extends BaseConsoleKernel
{
    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(app_path('') . '/Console/Commands');

        require base_path('routes/console.php');
    }
}
