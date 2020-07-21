<?php

namespace OZiTAG\Tager\Backend\Core;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

/**
 * An abstract Operation that can be managed with a queue
 * when extended the operation will be queued by default.
 */
abstract class QueueableOperation extends Operation implements ShouldQueue
{
    use SerializesModels;
    use InteractsWithQueue;
    use Queueable;
}
