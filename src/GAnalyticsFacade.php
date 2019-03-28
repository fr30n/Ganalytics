<?php

namespace Fr3on\GAnalytics;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fr3on\GAnalytics\GAnalytics
 */
class GAnalyticsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ganalytics';
    }
}
