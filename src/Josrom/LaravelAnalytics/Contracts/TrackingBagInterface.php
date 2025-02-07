<?php

namespace Josrom\LaravelAnalytics\Contracts;

/**
 * Interface TrackingBagInterface
 *
 * @package Josrom\LaravelAnalytics\Contracts
 */
interface TrackingBagInterface
{
    /**
     * adds a tracking
     *
     * @param string $tracking
     *
     * @return void
     */
    public function add(string $tracking): void;

    /**
     * returns all tracking
     *
     * @return array
     */
    public function get(): array;
}
