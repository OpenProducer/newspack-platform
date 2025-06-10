<?php

namespace Google\AdsApi\AdManager\v202411;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class getTrafficDataResponse
{

    /**
     * @var \Google\AdsApi\AdManager\v202411\TrafficDataResponse $rval
     */
    protected $rval = null;

    /**
     * @param \Google\AdsApi\AdManager\v202411\TrafficDataResponse $rval
     */
    public function __construct($rval = null)
    {
      $this->rval = $rval;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202411\TrafficDataResponse
     */
    public function getRval()
    {
      return $this->rval;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202411\TrafficDataResponse $rval
     * @return \Google\AdsApi\AdManager\v202411\getTrafficDataResponse
     */
    public function setRval($rval)
    {
      $this->rval = $rval;
      return $this;
    }

}
