<?php

namespace Josrom\LaravelAnalytics;

use Illuminate\Support\Facades\Session;
use Josrom\LaravelAnalytics\Contracts\TrackingBagInterface;

/**
 * Class TrackingBag
 *
 * @package Josrom\LaravelAnalytics
 */
class TrackingBag implements TrackingBagInterface
{
    /**
     * session identifier
     *
     * @var string
     */
    private string $sessionIdentifier = 'analytics.tracking';

    /**
     * adds a tracking
     *
     * @param string $tracking
     */
    public function add(string $tracking): void
    {
        $sessionTracks = [];
        if (Session::has($this->sessionIdentifier)) {
            $sessionTracks = Session::get($this->sessionIdentifier);
        }

        //	prevent duplicates in session
        $trackingKey = md5($tracking);
        $sessionTracks[$trackingKey] = $tracking;

        Session::flash($this->sessionIdentifier, $sessionTracks);
    }

    /**
     * returns all trackings with forgetting it
     *
     * @return array
     */
    public function get(): array
    {
        $trackings = [];
        if (Session::has($this->sessionIdentifier)) {
            $trackings = Session::get($this->sessionIdentifier);
            Session::forget($this->sessionIdentifier);
        }

        return $trackings;
    }
}
