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
namespace Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle;

class UserEntitlementsPlan extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'planEntitlements';
    /**
     * @var string
     */
    public $name;
    protected $planEntitlementsType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PlanEntitlement::class;
    protected $planEntitlementsDataType = 'array';
    /**
     * @var string
     */
    public $planId;
    /**
     * @var string
     */
    public $planType;
    /**
     * @var string
     */
    public $publicationId;
    protected $purchaseInfoType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PurchaseInfo::class;
    protected $purchaseInfoDataType = '';
    /**
     * @var string
     */
    public $readerId;
    protected $recurringPlanDetailsType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\RecurringPlanDetails::class;
    protected $recurringPlanDetailsDataType = '';
    /**
     * @var string
     */
    public $userId;
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
     * @param PlanEntitlement[]
     */
    public function setPlanEntitlements($planEntitlements)
    {
        $this->planEntitlements = $planEntitlements;
    }
    /**
     * @return PlanEntitlement[]
     */
    public function getPlanEntitlements()
    {
        return $this->planEntitlements;
    }
    /**
     * @param string
     */
    public function setPlanId($planId)
    {
        $this->planId = $planId;
    }
    /**
     * @return string
     */
    public function getPlanId()
    {
        return $this->planId;
    }
    /**
     * @param string
     */
    public function setPlanType($planType)
    {
        $this->planType = $planType;
    }
    /**
     * @return string
     */
    public function getPlanType()
    {
        return $this->planType;
    }
    /**
     * @param string
     */
    public function setPublicationId($publicationId)
    {
        $this->publicationId = $publicationId;
    }
    /**
     * @return string
     */
    public function getPublicationId()
    {
        return $this->publicationId;
    }
    /**
     * @param PurchaseInfo
     */
    public function setPurchaseInfo(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PurchaseInfo $purchaseInfo)
    {
        $this->purchaseInfo = $purchaseInfo;
    }
    /**
     * @return PurchaseInfo
     */
    public function getPurchaseInfo()
    {
        return $this->purchaseInfo;
    }
    /**
     * @param string
     */
    public function setReaderId($readerId)
    {
        $this->readerId = $readerId;
    }
    /**
     * @return string
     */
    public function getReaderId()
    {
        return $this->readerId;
    }
    /**
     * @param RecurringPlanDetails
     */
    public function setRecurringPlanDetails(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\RecurringPlanDetails $recurringPlanDetails)
    {
        $this->recurringPlanDetails = $recurringPlanDetails;
    }
    /**
     * @return RecurringPlanDetails
     */
    public function getRecurringPlanDetails()
    {
        return $this->recurringPlanDetails;
    }
    /**
     * @param string
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\UserEntitlementsPlan::class, 'Google\\Site_Kit_Dependencies\\Google_Service_SubscribewithGoogle_UserEntitlementsPlan');
