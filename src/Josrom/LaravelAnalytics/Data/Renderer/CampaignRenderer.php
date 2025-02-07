<?php

namespace Josrom\LaravelAnalytics\Data\Renderer;

use Josrom\LaravelAnalytics\Data\Campaign;

class CampaignRenderer implements Renderer
{
    /**
     * campaign to render
     *
     * @var Campaign
     */
    private Campaign $campaign;

    /**
     * CampaignRenderer constructor.
     * @param Campaign $campaign
     */
    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Renders data
     *
     * @return string
     */
    public function render(): string
    {
        return $this->renderName()
            . $this->renderSource()
            . $this->renderMedium()
            . $this->renderKeyword()
            . $this->renderContent()
            . $this->renderId();
    }

    /**
     * returns the rendered name
     *
     * @return string
     */
    private function renderName(): string
    {
        $name = $this->campaign->getName();

        return empty($name) ? '' : "ga('set', 'campaignName', '$name');";
    }

    /**
     * returns the rendered source
     *
     * @return string
     */
    private function renderSource(): string
    {
        $source = $this->campaign->getSource();

        return empty($source) ? '' : "ga('set', 'campaignSource', '$source');";
    }

    /**
     * returns the rendered medium
     *
     * @return string
     */
    private function renderMedium(): string
    {
        $medium = $this->campaign->getMedium();

        return empty($medium) ? '' : "ga('set', 'campaignMedium', '$medium');";
    }

    /**
     * returns the rendered keyword
     *
     * @return string
     */
    private function renderKeyword(): string
    {
        $keyword = $this->campaign->getKeyword();

        return empty($keyword) ? '' : "ga('set', 'campaignKeyword', '$keyword');";
    }

    /**
     * returns the rendered content
     *
     * @return string
     */
    private function renderContent(): string
    {
        $content = $this->campaign->getContent();

        return empty($content) ? '' : "ga('set', 'campaignContent', '$content');";
    }

    /**
     * returns the rendered id
     *
     * @return string
     */
    private function renderId(): string
    {
        $id = $this->campaign->getId();

        return empty($id) ? '' : "ga('set', 'campaignId', '$id');";
    }
}
