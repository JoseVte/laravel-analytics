<?php

namespace Josrom\LaravelAnalytics\Data\Renderer;

/**
 * Interface Renderer
 * @package Josrom\LaravelAnalytics\Data\Renderer
 */
interface Renderer
{
    /**
     * Renders data
     *
     * @return string
     */
    public function render(): string;
}
