<?php

namespace Josrom\LaravelAnalytics\Data;

/**
 * Class Event
 *
 * @package Josrom\LaravelAnalytics\Data
 */
class Event
{
    /**
     * event category
     *
     * @var string
     */
    private string $category = 'email';

    /**
     * event action
     *
     * @var string
     */
    private string $action = 'open';

    /**
     * event label
     *
     * @var string
     */
    private string $label;

    /**
     * hit type
     *
     * @var string
     */
    private string $hitType = 'event';

    /**
     * set action
     *
     * @param string $action
     *
     * @return Event
     */
    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    /**
     * returns action
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * set category
     *
     * @param string $category
     *
     * @return Event
     */
    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * returns category
     *
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * sets hit type
     *
     * @param string $hitType
     *
     * @return Event
     */
    public function setHitType(string $hitType): static
    {
        $this->hitType = $hitType;

        return $this;
    }

    /**
     * returns hit type
     *
     * @return string
     */
    public function getHitType(): string
    {
        return $this->hitType;
    }

    /**
     * sets label
     *
     * @param string $label
     *
     * @return Event
     */
    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * returns label
     *
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
}
