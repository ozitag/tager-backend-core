<?php

namespace OZiTAG\Tager\Backend\Core\Console;

use Illuminate\Foundation\Console\Kernel as BaseConsoleKernel;

class Kernel extends BaseConsoleKernel
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
