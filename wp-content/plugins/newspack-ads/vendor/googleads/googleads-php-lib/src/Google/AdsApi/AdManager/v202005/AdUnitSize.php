<?php

namespace Google\AdsApi\AdManager\v202005;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class AdUnitSize
{

    /**
     * @var \Google\AdsApi\AdManager\v202005\Size $size
     */
    protected $size = null;

    /**
     * @var string $environmentType
     */
    protected $environmentType = null;

    /**
     * @var \Google\AdsApi\AdManager\v202005\AdUnitSize[] $companions
     */
    protected $companions = null;

    /**
     * @var string $fullDisplayString
     */
    protected $fullDisplayString = null;

    /**
     * @param \Google\AdsApi\AdManager\v202005\Size $size
     * @param string $environmentType
     * @param \Google\AdsApi\AdManager\v202005\AdUnitSize[] $companions
     * @param string $fullDisplayString
     */
    public function __construct($size = null, $environmentType = null, array $companions = null, $fullDisplayString = null)
    {
      $this->size = $size;
      $this->environmentType = $environmentType;
      $this->companions = $companions;
      $this->fullDisplayString = $fullDisplayString;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202005\Size
     */
    public function getSize()
    {
      return $this->size;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202005\Size $size
     * @return \Google\AdsApi\AdManager\v202005\AdUnitSize
     */
    public function setSize($size)
    {
      $this->size = $size;
      return $this;
    }

    /**
     * @return string
     */
    public function getEnvironmentType()
    {
      return $this->environmentType;
    }

    /**
     * @param string $environmentType
     * @return \Google\AdsApi\AdManager\v202005\AdUnitSize
     */
    public function setEnvironmentType($environmentType)
    {
      $this->environmentType = $environmentType;
      return $this;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202005\AdUnitSize[]
     */
    public function getCompanions()
    {
      return $this->companions;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202005\AdUnitSize[]|null $companions
     * @return \Google\AdsApi\AdManager\v202005\AdUnitSize
     */
    public function setCompanions(array $companions = null)
    {
      $this->companions = $companions;
      return $this;
    }

    /**
     * @return string
     */
    public function getFullDisplayString()
    {
      return $this->fullDisplayString;
    }

    /**
     * @param string $fullDisplayString
     * @return \Google\AdsApi\AdManager\v202005\AdUnitSize
     */
    public function setFullDisplayString($fullDisplayString)
    {
      $this->fullDisplayString = $fullDisplayString;
      return $this;
    }

}
