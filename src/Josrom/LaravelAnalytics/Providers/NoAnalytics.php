<?php

namespace Josrom\LaravelAnalytics\Providers;

use Josrom\LaravelAnalytics\Contracts\AnalyticsProviderInterface;
use Josrom\LaravelAnalytics\Data\Campaign;
use Josrom\LaravelAnalytics\Data\Event;

/**
 * Class NoAnalytics
 *
 * @package Josrom\LaravelAnalytics\Providers
 */
class NoAnalytics implements AnalyticsProviderInterface
{
    /**
     * returns the javascript code for embedding the analytics stuff
     *
     * @return string
     */
    public function render(): string
    {
        return '';
    }

    /**
     * track a page view
     *
     * @param string|null $page
     * @param string|null $title
     * @param string|null $hittype
     *
     * @return void
     */
    public function trackPage(?string $page, ?string $title, ?string $hittype): void
    {
    }

    /**
     * track an event
     *
     * @param string $category
     * @param string $action
     * @param string|null $label
     * @param int|null $value
     *
     * @return void
     */
    public function trackEvent(string $category, string $action, ?string $label, ?int $value): void
    {
    }

    /**
     * track any custom code
     *
     * @param string $customCode
     *
     * @return void
     */
    public function trackCustom(string $customCode): void
    {
    }

    /**
     * enable display features
     *
     * @return NoAnalytics
     */
    public function enableDisplayFeatures(): static
    {
        return $this;
    }

    /**
     * disable display features
     *
     * @return NoAnalytics
     */
    public function disableDisplayFeatures(): static
    {
        return $this;
    }

    /**
     * enable auto tracking
     *
     * @return NoAnalytics
     */
    public function enableAutoTracking(): static
    {
        return $this;
    }

    /**
     * disable auto tracking
     *
     * @return NoAnalytics
     */
    public function disableAutoTracking(): static
    {
        return $this;
    }

    /**
     * render script block
     *
     * @return NoAnalytics
     */
    public function enableScriptBlock(): static
    {
        return $this;
    }

    /**
     * do not render script block
     *
     * @return NoAnalytics
     */
    public function disableScriptBlock(): static
    {
        return $this;
    }

    /**
     * assembles an url for tracking measurement without javascript
     *
     * e.g. for tracking email open events within a newsletter
     *
     * @param string $metricName
     * @param mixed $metricValue
     * @param Event $event
     * @param Campaign $campaign
     * @param string|null $clientId
     * @param array $params
     *
     * @return string
     */
    public function trackMeasurementUrl(
        string   $metricName,
        mixed    $metricValue,
        Event    $event,
        Campaign $campaign,
        string   $clientId = null,
        array    $params = []
    ): string
    {
        return '';
    }

    /**
     * sets or gets nonInteraction
     *
     * setting: $this->nonInteraction(true)->render();
     * getting: if ($this->nonInteraction()) echo 'non-interaction set';
     *
     * @param boolean|null $value
     *
     * @return bool|AnalyticsProviderInterface
     */
    public function nonInteraction(bool $value = null): bool|AnalyticsProviderInterface
    {
        if (null === $value) {
            return false;
        }

        return $this;
    }

    /**
     * sets a user id for user tracking
     *
     * @param string $userId
     *
     * @return AnalyticsProviderInterface
     *
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/cookies-user-id
     */
    public function setUserId(string $userId): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * unsets a possible given user id
     *
     * @return AnalyticsProviderInterface
     */
    public function unsetUserId(): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * sets custom dimensions
     *
     * @param array|string $dimension
     * @param string|null $value
     * @return AnalyticsProviderInterface
     */
    public function setCustom(array|string $dimension, string $value = null): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * sets a campaign
     *
     * @param Campaign $campaign
     * @return AnalyticsProviderInterface
     */
    public function setCampaign(Campaign $campaign): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * unsets a possible given campaign
     *
     * @return AnalyticsProviderInterface
     */
    public function unsetCampaign(): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * enable ecommerce tracking
     *
     * @return AnalyticsProviderInterface
     */
    public function enableEcommerceTracking(): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * disable ecommerce tracking
     *
     * @return AnalyticsProviderInterface
     */
    public function disableEcommerceTracking(): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * ecommerce tracking - add transaction
     *
     * @param string $id
     * @param string|null $affiliation
     * @param float|null $revenue
     * @param float|null $shipping
     * @param float|null $tax
     * @param string|null $currency
     *
     * @return AnalyticsProviderInterface
     */
    public function ecommerceAddTransaction(
        string $id,
        string $affiliation = null,
        float  $revenue = null,
        float  $shipping = null,
        float  $tax = null,
        string $currency = null
    ): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * ecommerce tracking - add item
     *
     * @param string $id
     * @param string $name
     * @param string|null $sku
     * @param string|null $category
     * @param float|null $price
     * @param int|null $quantity
     * @param string|null $currency
     *
     * @return AnalyticsProviderInterface
     */
    public function ecommerceAddItem(
        string $id,
        string $name,
        string $sku = null,
        string $category = null,
        float  $price = null,
        int    $quantity = null,
        string $currency = null
    ): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * enables Content Security Polity and sets nonce
     *
     * @return AnalyticsProviderInterface
     */
    public function withCSP(): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * disables Content Security Polity
     *
     * @return AnalyticsProviderInterface
     */
    public function withoutCSP(): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * returns the current Content Security Policy nonce
     *
     * @return string|null
     */
    public function cspNonce(): ?string
    {
        return null;
    }

    /**
     * set a custom tracking ID (the UA-XXXXXXXX-1 code)
     *
     * @param string $trackingId
     *
     * @return AnalyticsProviderInterface
     */
    public function setTrackingId(string $trackingId): AnalyticsProviderInterface
    {
        return $this;
    }

    /**
     * set a custom optimize ID (the GTM-XXXXXX code)
     *
     * @param string $optimizeId
     *
     * @return AnalyticsProviderInterface
     */
    public function setOptimizeId(string $optimizeId): AnalyticsProviderInterface
    {
        return $this;
    }
}
