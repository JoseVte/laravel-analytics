<?php

namespace Josrom\LaravelAnalytics;

use Illuminate\Support\Facades\Facade;
use Josrom\LaravelAnalytics\Contracts\AnalyticsProviderInterface;

/**
 * Class AnalyticsFacade
 *
 * @package Josrom\LaravelAnalytics
 */
class AnalyticsFacade extends Facade
{
    /**
     * facade accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return AnalyticsProviderInterface::class;
    }
}
