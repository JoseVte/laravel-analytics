<?php

namespace Josrom\LaravelAnalytics\Contracts;

use Josrom\LaravelAnalytics\Data\Campaign;
use Josrom\LaravelAnalytics\Data\Event;

/**
 * Interface AnalyticsProviderInterface
 *
 * @package Josrom\LaravelAnalytics\Contracts
 */
interface AnalyticsProviderInterface
{
    /**
     * returns the javascript code for embedding the analytics stuff
     *
     * @return string
     */
    public function render(): string;

    /**
     * track a page view
     *
     * @param string|null $page
     * @param string|null $title
     * @param string|null $hittype
     *
     * @return void
     */
    public function trackPage(?string $page, ?string $title, ?string $hittype): void;

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
    public function trackEvent(string $category, string $action, ?string $label, ?int $value): void;

    /**
     * track any custom code
     *
     * @param string $customCode
     *
     * @return void
     */
    public function trackCustom(string $customCode): void;

    /**
     * enable display features
     *
     * @return AnalyticsProviderInterface
     */
    public function enableDisplayFeatures(): AnalyticsProviderInterface;

    /**
     * disable display features
     *
     * @return AnalyticsProviderInterface
     */
    public function disableDisplayFeatures(): AnalyticsProviderInterface;

    /**
     * enable auto tracking
     *
     * @return AnalyticsProviderInterface
     */
    public function enableAutoTracking(): AnalyticsProviderInterface;

    /**
     * disable auto tracking
     *
     * @return AnalyticsProviderInterface
     */
    public function disableAutoTracking(): AnalyticsProviderInterface;

    /**
     * render script block
     *
     * @return AnalyticsProviderInterface
     */
    public function enableScriptBlock(): AnalyticsProviderInterface;

    /**
     * do not render script block
     *
     * @return AnalyticsProviderInterface
     */
    public function disableScriptBlock(): AnalyticsProviderInterface;

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
    ): string;

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
    public function nonInteraction(bool $value = null): bool|AnalyticsProviderInterface;

    /**
     * sets a user id for user tracking
     *
     * @param string $userId
     *
     * @return AnalyticsProviderInterface
     *
     * @see https://developers.google.com/analytics/devguides/collection/analyticsjs/cookies-user-id
     */
    public function setUserId(string $userId): AnalyticsProviderInterface;

    /**
     * unsets a possible given user id
     *
     * @return AnalyticsProviderInterface
     */
    public function unsetUserId(): AnalyticsProviderInterface;

    /**
     * sets a campaign
     *
     * @param Campaign $campaign
     *
     * @return AnalyticsProviderInterface
     */
    public function setCampaign(Campaign $campaign): AnalyticsProviderInterface;

    /**
     * unsets a possible given campaign
     *
     * @return AnalyticsProviderInterface
     */
    public function unsetCampaign(): AnalyticsProviderInterface;

    /**
     * enable ecommerce tracking
     *
     * @return AnalyticsProviderInterface
     */
    public function enableEcommerceTracking(): AnalyticsProviderInterface;

    /**
     * disable ecommerce tracking
     *
     * @return AnalyticsProviderInterface
     */
    public function disableEcommerceTracking(): AnalyticsProviderInterface;

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
    ): AnalyticsProviderInterface;

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
    ): AnalyticsProviderInterface;

    /**
     * sets custom dimensions
     *
     * @param array|string $dimension
     * @param string|null $value
     * @return AnalyticsProviderInterface
     */
    public function setCustom(array|string $dimension, string $value = null): AnalyticsProviderInterface;

    /**
     * set a custom tracking ID (the UA-XXXXXXXX-1 code)
     *
     * @param string $trackingId
     *
     * @return AnalyticsProviderInterface
     */
    public function setTrackingId(string $trackingId): AnalyticsProviderInterface;

    /**
     * set a custom optimize ID (the GTM-XXXXXX code)
     *
     * @param string $optimizeId
     *
     * @return AnalyticsProviderInterface
     */
    public function setOptimizeId(string $optimizeId): AnalyticsProviderInterface;

    /**
     * enables Content Security Polity and sets nonce
     *
     * @return AnalyticsProviderInterface
     */
    public function withCSP(): AnalyticsProviderInterface;

    /**
     * disables Content Security Polity
     *
     * @return AnalyticsProviderInterface
     */
    public function withoutCSP(): AnalyticsProviderInterface;

    /**
     * returns the current Content Security Policy nonce
     *
     * @return string|null
     */
    public function cspNonce(): ?string;
}
