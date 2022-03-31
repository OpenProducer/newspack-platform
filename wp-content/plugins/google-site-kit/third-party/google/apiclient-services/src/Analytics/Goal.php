<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace Google\Site_Kit_Dependencies\Google\Service\Analytics;

class Goal extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * @var string
     */
    public $accountId;
    /**
     * @var bool
     */
    public $active;
    /**
     * @var string
     */
    public $created;
    protected $eventDetailsType = \Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalEventDetails::class;
    protected $eventDetailsDataType = '';
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $internalWebPropertyId;
    /**
     * @var string
     */
    public $kind;
    /**
     * @var string
     */
    public $name;
    protected $parentLinkType = \Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalParentLink::class;
    protected $parentLinkDataType = '';
    /**
     * @var string
     */
    public $profileId;
    /**
     * @var string
     */
    public $selfLink;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $updated;
    protected $urlDestinationDetailsType = \Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalUrlDestinationDetails::class;
    protected $urlDestinationDetailsDataType = '';
    /**
     * @var float
     */
    public $value;
    protected $visitNumPagesDetailsType = \Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalVisitNumPagesDetails::class;
    protected $visitNumPagesDetailsDataType = '';
    protected $visitTimeOnSiteDetailsType = \Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalVisitTimeOnSiteDetails::class;
    protected $visitTimeOnSiteDetailsDataType = '';
    /**
     * @var string
     */
    public $webPropertyId;
    /**
     * @param string
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
    /**
     * @param bool
     */
    public function setActive($active)
    {
        $this->active = $active;
    }
    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * @param string
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }
    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->created;
    }
    /**
     * @param GoalEventDetails
     */
    public function setEventDetails(\Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalEventDetails $eventDetails)
    {
        $this->eventDetails = $eventDetails;
    }
    /**
     * @return GoalEventDetails
     */
    public function getEventDetails()
    {
        return $this->eventDetails;
    }
    /**
     * @param string
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @param string
     */
    public function setInternalWebPropertyId($internalWebPropertyId)
    {
        $this->internalWebPropertyId = $internalWebPropertyId;
    }
    /**
     * @return string
     */
    public function getInternalWebPropertyId()
    {
        return $this->internalWebPropertyId;
    }
    /**
     * @param string
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }
    /**
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param GoalParentLink
     */
    public function setParentLink(\Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalParentLink $parentLink)
    {
        $this->parentLink = $parentLink;
    }
    /**
     * @return GoalParentLink
     */
    public function getParentLink()
    {
        return $this->parentLink;
    }
    /**
     * @param string
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
    }
    /**
     * @return string
     */
    public function getProfileId()
    {
        return $this->profileId;
    }
    /**
     * @param string
     */
    public function setSelfLink($selfLink)
    {
        $this->selfLink = $selfLink;
    }
    /**
     * @return string
     */
    public function getSelfLink()
    {
        return $this->selfLink;
    }
    /**
     * @param string
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param string
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }
    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    /**
     * @param GoalUrlDestinationDetails
     */
    public function setUrlDestinationDetails(\Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalUrlDestinationDetails $urlDestinationDetails)
    {
        $this->urlDestinationDetails = $urlDestinationDetails;
    }
    /**
     * @return GoalUrlDestinationDetails
     */
    public function getUrlDestinationDetails()
    {
        return $this->urlDestinationDetails;
    }
    /**
     * @param float
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * @param GoalVisitNumPagesDetails
     */
    public function setVisitNumPagesDetails(\Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalVisitNumPagesDetails $visitNumPagesDetails)
    {
        $this->visitNumPagesDetails = $visitNumPagesDetails;
    }
    /**
     * @return GoalVisitNumPagesDetails
     */
    public function getVisitNumPagesDetails()
    {
        return $this->visitNumPagesDetails;
    }
    /**
     * @param GoalVisitTimeOnSiteDetails
     */
    public function setVisitTimeOnSiteDetails(\Google\Site_Kit_Dependencies\Google\Service\Analytics\GoalVisitTimeOnSiteDetails $visitTimeOnSiteDetails)
    {
        $this->visitTimeOnSiteDetails = $visitTimeOnSiteDetails;
    }
    /**
     * @return GoalVisitTimeOnSiteDetails
     */
    public function getVisitTimeOnSiteDetails()
    {
        return $this->visitTimeOnSiteDetails;
    }
    /**
     * @param string
     */
    public function setWebPropertyId($webPropertyId)
    {
        $this->webPropertyId = $webPropertyId;
    }
    /**
     * @return string
     */
    public function getWebPropertyId()
    {
        return $this->webPropertyId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\Analytics\Goal::class, 'Google\\Site_Kit_Dependencies\\Google_Service_Analytics_Goal');
