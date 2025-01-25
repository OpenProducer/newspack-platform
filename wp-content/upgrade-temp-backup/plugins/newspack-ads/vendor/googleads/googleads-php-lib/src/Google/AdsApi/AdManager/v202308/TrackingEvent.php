<?php

namespace Google\AdsApi\AdManager\v202308;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class TrackingEvent
{

    /**
     * @var \Google\AdsApi\AdManager\v202308\TrackingEventPing[] $pings
     */
    protected $pings = null;

    /**
     * @param \Google\AdsApi\AdManager\v202308\TrackingEventPing[] $pings
     */
    public function __construct(array $pings = null)
    {
      $this->pings = $pings;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202308\TrackingEventPing[]
     */
    public function getPings()
    {
      return $this->pings;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202308\TrackingEventPing[]|null $pings
     * @return \Google\AdsApi\AdManager\v202308\TrackingEvent
     */
    public function setPings(array $pings = null)
    {
      $this->pings = $pings;
      return $this;
    }

}
