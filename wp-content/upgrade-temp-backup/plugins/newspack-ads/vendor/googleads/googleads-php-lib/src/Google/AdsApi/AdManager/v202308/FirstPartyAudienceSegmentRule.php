<?php

namespace Google\AdsApi\AdManager\v202308;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class FirstPartyAudienceSegmentRule
{

    /**
     * @var \Google\AdsApi\AdManager\v202308\InventoryTargeting $inventoryRule
     */
    protected $inventoryRule = null;

    /**
     * @var \Google\AdsApi\AdManager\v202308\CustomCriteriaSet $customCriteriaRule
     */
    protected $customCriteriaRule = null;

    /**
     * @param \Google\AdsApi\AdManager\v202308\InventoryTargeting $inventoryRule
     * @param \Google\AdsApi\AdManager\v202308\CustomCriteriaSet $customCriteriaRule
     */
    public function __construct($inventoryRule = null, $customCriteriaRule = null)
    {
      $this->inventoryRule = $inventoryRule;
      $this->customCriteriaRule = $customCriteriaRule;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202308\InventoryTargeting
     */
    public function getInventoryRule()
    {
      return $this->inventoryRule;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202308\InventoryTargeting $inventoryRule
     * @return \Google\AdsApi\AdManager\v202308\FirstPartyAudienceSegmentRule
     */
    public function setInventoryRule($inventoryRule)
    {
      $this->inventoryRule = $inventoryRule;
      return $this;
    }

    /**
     * @return \Google\AdsApi\AdManager\v202308\CustomCriteriaSet
     */
    public function getCustomCriteriaRule()
    {
      return $this->customCriteriaRule;
    }

    /**
     * @param \Google\AdsApi\AdManager\v202308\CustomCriteriaSet $customCriteriaRule
     * @return \Google\AdsApi\AdManager\v202308\FirstPartyAudienceSegmentRule
     */
    public function setCustomCriteriaRule($customCriteriaRule)
    {
      $this->customCriteriaRule = $customCriteriaRule;
      return $this;
    }

}
