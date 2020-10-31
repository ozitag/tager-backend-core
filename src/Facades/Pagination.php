<?php

namespace OZiTAG\Tager\Backend\Core\Facades;

use Illuminate\Support\Facades\Facade;
use OZiTAG\Tager\Backend\Core\Helpers\PaginationHelper;

/**
 * Class Paginator
 * @package OZiTAG\Tager\Backend\Core\Facades
 * @method static int page()
 * @method static int pageForClient()
 * @method static int perPage()
 * @method static int offset()
 */
class Pagination extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PaginationHelper::class;
    }
}
