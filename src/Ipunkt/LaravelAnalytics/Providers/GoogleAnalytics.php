<?php

namespace Josrom\LaravelAnalytics\Providers;

use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Josrom\LaravelAnalytics\Contracts\AnalyticsProviderInterface;
use Josrom\LaravelAnalytics\Data\Campaign;
use Josrom\LaravelAnalytics\Data\Event;
use Josrom\LaravelAnalytics\Data\Renderer\CampaignRenderer;
use Josrom\LaravelAnalytics\TrackingBag;
use JsonException;

/**
 * Class GoogleAnalytics
 *
 * @package Josrom\LaravelAnalytics\Providers
 */
class GoogleAnalytics implements AnalyticsProviderInterface
{
    /**
     * tracking id
     *
     * @var string|null
     */
    private ?string $trackingId;

    /**
     * optimize id
     *
     * @var string
     */
    private string $optimizeId;

    /**
     * tracking domain
     *
     * @var string
     */
    private string $trackingDomain;

    /**
     * tracker name
     *
     * @var string
     */
    private string $trackerName;

    /**
     * display features plugin enabled or disabled
     *
     * @var bool
     */
    private bool $displayFeatures;

    /**
     * ecommerce tracking plugin enabled or disabled
     *
     * @var bool
     */
    private bool $ecommerceTracking = false;

    /**
     * anonymize users ip
     *
     * @var bool
     */
    private bool $anonymizeIp;

    /**
     * auto tracking the page view
     *
     * @var bool
     */
    private bool $autoTrack;

    /**
     * debug mode
     *
     * @var bool
     */
    private bool $debug;

    /**
     * for event tracking it can mark track as non-interactive so the bounce-rate calculation ignores that tracking
     *
     * @var bool
     */
    private bool $nonInteraction = false;

    /**
     * session tracking bag
     *
     * @var TrackingBag
     */
    private TrackingBag $trackingBag;

    /**
     * use https for the tracking measurement url
     *
     * @var bool
     */
    private bool $secureTrackingUrl = true;

    /**
     * a user id for tracking
     *
     * @var string|null
     */
    private ?string $userId = null;

    /**
     * a campaign for tracking
     *
     * @var Campaign|null
     */
    private ?Campaign $campaign = null;

    /**
     * should the script block be rendered?
     *
     * @var bool
     */
    private bool $renderScriptBlock = true;

    /**
     * Content Security Nonce
     *
     * @var null
     */
    private mixed $cspNonce = null;

    /**
     * setting options via constructor
     *
     * @param array $options
     *
     * @throws InvalidArgumentException when tracking id not set
     */
    public function __construct(array $options = [])
    {
        $this->trackingId = Arr::get($options, 'tracking_id');
        $this->optimizeId = Arr::get($options, 'optimize_id');
        $this->trackingDomain = Arr::get($options, 'tracking_domain', 'auto');
        $this->trackerName = Arr::get($options, 'tracker_name', 't0');
        $this->displayFeatures = Arr::get($options, 'display_features', false);
        $this->anonymizeIp = Arr::get($options, 'anonymize_ip', false);
        $this->autoTrack = Arr::get($options, 'auto_track', false);
        $this->debug = Arr::get($options, 'debug', false);

        if ($this->trackingId === null) {
            throw new InvalidArgumentException('Argument tracking_id can not be null');
        }

        $this->trackingBag = new TrackingBag;
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
        $allowedHitTypes = ['pageview', 'appview', 'event', 'transaction', 'item', 'social', 'exception', 'timing'];
        if ($hittype === null) {
            $hittype = $allowedHitTypes[0];
        }

        if (!in_array($hittype, $allowedHitTypes, true)) {
            return;
        }

        $trackingCode = "ga('send', 'pageview');";

        if ($page !== null || $title !== null || $hittype !== null) {
            $page = ($page === null) ? "window.location.protocol + '//' + window.location.hostname + window.location.pathname + window.location.search" : "'$page'";
            $title = ($title === null) ? "document.title" : "'$title'";

            $trackingCode = "ga('send', {'hitType': '$hittype', 'page': $page, 'title': $title});";
        }

        $this->trackingBag->add($trackingCode);
    }

    /**
     * track an event
     *
     * @param string $category
     * @param string $action
     * @param string|null $label
     * @param int|null $value
     */
    public function trackEvent(string $category, string $action, ?string $label, ?int $value): void
    {
        $command = '';
        if ($label !== null) {
            $command .= ", '$label'";
            if (is_numeric($value)) {
                $command .= ", $value";
            }
        }

        $trackingCode = "ga('send', 'event', '$category', '$action'$command);";

        $this->trackingBag->add($trackingCode);
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
     * @throws JsonException
     * @throws JsonException
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
        // Call to enable ecommerce tracking automatically
        $this->enableEcommerceTracking();

        $parameters = ['id' => $id];

        if (!is_null($affiliation)) {
            $parameters['affiliation'] = $affiliation;
        }

        if (!is_null($revenue)) {
            $parameters['revenue'] = $revenue;
        }

        if (!is_null($shipping)) {
            $parameters['shipping'] = $shipping;
        }

        if (!is_null($tax)) {
            $parameters['tax'] = $tax;
        }

        if (!is_null($currency)) {
            $parameters['currency'] = $currency;
        }

        $jsonParameters = json_encode($parameters, JSON_THROW_ON_ERROR);
        $trackingCode = "ga('ecommerce:addTransaction', $jsonParameters);";

        $this->trackingBag->add($trackingCode);

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
     * @throws JsonException
     * @throws JsonException
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
        // Call to enable ecommerce tracking automatically
        $this->enableEcommerceTracking();

        $parameters = [
            'id' => $id,
            'name' => $name,
        ];

        if (!is_null($sku)) {
            $parameters['sku'] = $sku;
        }

        if (!is_null($category)) {
            $parameters['category'] = $category;
        }

        if (!is_null($price)) {
            $parameters['price'] = $price;
        }

        if (!is_null($quantity)) {
            $parameters['quantity'] = $quantity;
        }

        if (!is_null($currency)) {
            $parameters['currency'] = $currency;
        }

        $jsonParameters = json_encode($parameters, JSON_THROW_ON_ERROR);
        $trackingCode = "ga('ecommerce:addItem', $jsonParameters);";

        $this->trackingBag->add($trackingCode);

        return $this;
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
        $this->trackingBag->add($customCode);
    }

    /**
     * enable display features
     *
     * @return GoogleAnalytics
     */
    public function enableDisplayFeatures(): static
    {
        $this->displayFeatures = true;

        return $this;
    }

    /**
     * disable display features
     *
     * @return GoogleAnalytics
     */
    public function disableDisplayFeatures(): static
    {
        $this->displayFeatures = false;

        return $this;
    }

    /**
     * enable ecommerce tracking
     *
     * @return GoogleAnalytics
     */
    public function enableEcommerceTracking(): static
    {
        $this->ecommerceTracking = true;

        return $this;
    }

    /**
     * disable ecommerce tracking
     *
     * @return GoogleAnalytics
     */
    public function disableEcommerceTracking(): static
    {
        $this->ecommerceTracking = false;

        return $this;
    }

    /**
     * enable auto tracking
     *
     * @return GoogleAnalytics
     */
    public function enableAutoTracking(): static
    {
        $this->autoTrack = true;

        return $this;
    }

    /**
     * disable auto tracking
     *
     * @return GoogleAnalytics
     */
    public function disableAutoTracking(): static
    {
        $this->autoTrack = false;

        return $this;
    }

    /**
     * render script block
     *
     * @return GoogleAnalytics
     */
    public function enableScriptBlock(): static
    {
        $this->renderScriptBlock = true;

        return $this;
    }

    /**
     * do not render script block
     *
     * @return GoogleAnalytics
     */
    public function disableScriptBlock(): static
    {
        $this->renderScriptBlock = false;

        return $this;
    }

    /**
     * returns the javascript embedding code
     *
     * @return string
     */
    public function render(): string
    {
        $script[] = $this->_getJavascriptTemplateBlockBegin();

        $trackingUserId = (null === $this->userId)
            ? ''
            : sprintf(", {'userId': '%s'}", $this->userId);

        if ($this->debug) {
            $script[] = "ga('create', '$this->trackingId', { 'cookieDomain': 'none' }, '$this->trackerName'$trackingUserId);";
        } else {
            $script[] = "ga('create', '$this->trackingId', '$this->trackingDomain', '$this->trackerName'$trackingUserId);";
        }

        if ($this->ecommerceTracking) {
            $script[] = "ga('require', 'ecommerce');";
        }

        if ($this->displayFeatures) {
            $script[] = "ga('require', 'displayfeatures');";
        }

        if ($this->optimizeId) {
            $script[] = "ga('require', '$this->optimizeId');";
        }

        if ($this->anonymizeIp) {
            $script[] = "ga('set', 'anonymizeIp', true);";
        }

        if ($this->nonInteraction) {
            $script[] = "ga('set', 'nonInteraction', true);";
        }

        if ($this->campaign instanceof Campaign) {
            $script[] = (new CampaignRenderer($this->campaign))->render();
        }

        $trackingStack = $this->trackingBag->get();
        if (count($trackingStack)) {
            $script[] = implode("\n", $trackingStack);
        }

        if ($this->autoTrack) {
            $script[] = "ga('send', 'pageview');";
        }

        if ($this->ecommerceTracking) {
            $script[] = "ga('ecommerce:send');";
        }

        $script[] = $this->_getJavascriptTemplateBlockEnd();

        return implode('', $script);
    }

    /**
     * sets or gets nonInteraction
     *
     * setting: $this->nonInteraction(true)->render();
     * getting: if ($this->nonInteraction()) echo 'non-interaction set';
     *
     * @param boolean|null $value
     *
     * @return bool|$this
     */
    public function nonInteraction(bool $value = null): bool|static
    {
        if (null === $value) {
            return $this->nonInteraction;
        }

        $this->nonInteraction = ($value === true);

        return $this;
    }

    /**
     * make the tracking measurement url insecure
     *
     * @return GoogleAnalytics
     */
    public function unsecureMeasurementUrl(): static
    {
        $this->secureTrackingUrl = false;

        return $this;
    }

    /**
     * use the secured version of the tracking measurement url
     *
     * @return GoogleAnalytics
     */
    public function secureMeasurementUrl(): static
    {
        $this->secureTrackingUrl = false;

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
        $uniqueId = $clientId ?? uniqid('track_', true);

        if ($event->getLabel() === '') {
            $event->setLabel($uniqueId);
        }

        if ($campaign->getName() === '') {
            $campaign->setName('Campaign ' . date('Y-m-d'));
        }

        $protocol = $this->secureTrackingUrl ? 'https' : 'http';

        $defaults = [
            'url' => $protocol . '://www.google-analytics.com/collect?',
            'params' => [
                'v' => 1,    //	protocol version
                'tid' => $this->trackingId,    //	tracking id
                'cid' => $uniqueId,    //	client id
                't' => $event->getHitType(),
                'ec' => $event->getCategory(),
                'ea' => $event->getAction(),
                'el' => $event->getLabel(),
                'cs' => $campaign->getSource(),
                'cm' => $campaign->getMedium(),
                'cn' => $campaign->getName(),
                $metricName => $metricValue,    //	metric data
            ],
        ];

        $url = $params['url'] ?? $defaults['url'];
        $url = rtrim($url, '?') . '?';

        if (isset($params['url'])) {
            unset($params['url']);
        }

        $params = array_merge($defaults['params'], $params);
        $queryParams = [];
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $queryParams[] = sprintf('%s=%s', $key, $value);
            }
        }

        return $url . implode('&', $queryParams);
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
        $this->userId = $userId;

        return $this;
    }

    /**
     * unset a possible given user id
     *
     * @return AnalyticsProviderInterface
     */
    public function unsetUserId(): AnalyticsProviderInterface
    {
        return $this->setUserId(null);
    }

    /**
     * sets custom dimensions
     *
     * @param array|string $dimension
     * @param string|null $value
     * @return AnalyticsProviderInterface
     * @throws JsonException
     * @throws JsonException
     */
    public function setCustom(array|string $dimension, string $value = null): AnalyticsProviderInterface
    {
        if ($value === null && is_array($dimension)) {
            $params = json_encode($dimension, JSON_THROW_ON_ERROR);
            $trackingCode = "ga('set', $params);";
        } else {
            $trackingCode = "ga('set', '$dimension', '$value');";
        }

        $this->trackCustom($trackingCode);

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
        $this->campaign = $campaign;

        return $this;
    }

    /**
     * unset a possible given campaign
     *
     * @return AnalyticsProviderInterface
     */
    public function unsetCampaign(): AnalyticsProviderInterface
    {
        $this->campaign = null;

        return $this;
    }

    /**
     * enables Content Security Polity and sets nonce
     *
     * @return AnalyticsProviderInterface
     * @throws Exception
     */
    public function withCSP(): AnalyticsProviderInterface
    {
        if ($this->cspNonce === null) {
            $this->cspNonce = 'nonce-' . random_int(0, PHP_INT_MAX);
        }

        return $this;
    }

    /**
     * disables Content Security Polity
     *
     * @return AnalyticsProviderInterface
     */
    public function withoutCSP(): AnalyticsProviderInterface
    {
        $this->cspNonce = null;

        return $this;
    }

    /**
     * returns the current Content Security Policy nonce
     *
     * @return string|null
     */
    public function cspNonce(): ?string
    {
        return $this->cspNonce;
    }

    /**
     * returns start block
     *
     * @return string
     */
    protected function _getJavascriptTemplateBlockBegin(): string
    {
        $appendix = $this->debug ? '_debug' : '';

        $scriptTag = ($this->cspNonce === null)
            ? '<script>'
            : '<script nonce="' . $this->cspNonce . '">';

        return ($this->renderScriptBlock)
            ? $scriptTag . "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics$appendix.js','ga');"
            : '';
    }

    /**
     * returns end block
     *
     * @return string
     */
    protected function _getJavascriptTemplateBlockEnd(): string
    {
        return ($this->renderScriptBlock)
            ? '</script>'
            : '';
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
        $this->trackingId = $trackingId;

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
        $this->optimizeId = $optimizeId;

        return $this;
    }
}
