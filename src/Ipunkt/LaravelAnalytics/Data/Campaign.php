<?php

namespace Ipunkt\LaravelAnalytics\Data;

/**
 * Class Campaign
 *
 * @package Ipunkt\LaravelAnalytics\Data
 */
class Campaign
{
    /**
     * campaign source
     *
     * @var string
     */
    private string $source = 'newsletter';

    /**
     * campaign medium
     *
     * @var string
     */
    private string $medium = 'email';

    /**
     * campaign name
     *
     * @var string
     */
    private string $name;

    /**
     * campaign keyword
     *
     * @var string
     */
    private string $keyword;

    /**
     * campaign content
     *
     * @var string
     */
    private string $content;

    /**
     * campaign id
     *
     * @var string
     */
    private string $id;

    /**
     * @param string $name
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
    }

    /**
     * set medium
     *
     * @param string $medium
     *
     * @return Campaign
     */
    public function setMedium(string $medium): static
    {
        $this->medium = $medium;

        return $this;
    }

    /**
     * @return string
     */
    public function getMedium(): string
    {
        return $this->medium;
    }

    /**
     * set name
     *
     * @param string $name
     *
     * @return Campaign
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * set source
     *
     * @param string $source
     *
     * @return Campaign
     */
    public function setSource(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * returns Keyword
     *
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * sets keyword
     *
     * @param string $keyword
     *
     * @return Campaign
     */
    public function setKeyword(string $keyword): static
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * returns Content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * sets content
     *
     * @param string $content
     *
     * @return Campaign
     */
    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns Id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * sets id
     *
     * @param string $id
     *
     * @return Campaign
     */
    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }
}
