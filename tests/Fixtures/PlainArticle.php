<?php

namespace OZiTAG\Tager\Backend\Core\Tests\Fixtures;

use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Same table as {@see Article}, but without the UUID hook and with a
 * default ordering, so the two TModel features can be asserted in isolation.
 */
class PlainArticle extends TModel
{
    protected $table = 'articles';

    protected $guarded = [];

    public static bool $hasUUID = false;

    public static string $defaultOrder = 'priority desc';
}
