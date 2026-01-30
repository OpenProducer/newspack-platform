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
namespace Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha;

class GoogleAnalyticsAdminV1alphaAudience extends \Google\Site_Kit_Dependencies\Google\Collection
{
    /**
     * Not specified.
     */
    public const EXCLUSION_DURATION_MODE_AUDIENCE_EXCLUSION_DURATION_MODE_UNSPECIFIED = 'AUDIENCE_EXCLUSION_DURATION_MODE_UNSPECIFIED';
    /**
     * Exclude users from the Audience during periods when they meet the filter
     * clause.
     */
    public const EXCLUSION_DURATION_MODE_EXCLUDE_TEMPORARILY = 'EXCLUDE_TEMPORARILY';
    /**
     * Exclude users from the Audience if they've ever met the filter clause.
     */
    public const EXCLUSION_DURATION_MODE_EXCLUDE_PERMANENTLY = 'EXCLUDE_PERMANENTLY';
    protected $collection_key = 'filterClauses';
    /**
     * Output only. It is automatically set by GA to false if this is an NPA
     * Audience and is excluded from ads personalization.
     *
     * @var bool
     */
    public $adsPersonalizationEnabled;
    /**
     * Output only. Time when the Audience was created.
     *
     * @var string
     */
    public $createTime;
    /**
     * Required. The description of the Audience.
     *
     * @var string
     */
    public $description;
    /**
     * Required. The display name of the Audience.
     *
     * @var string
     */
    public $displayName;
    protected $eventTriggerType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceEventTrigger::class;
    protected $eventTriggerDataType = '';
    /**
     * Immutable. Specifies how long an exclusion lasts for users that meet the
     * exclusion filter. It is applied to all EXCLUDE filter clauses and is
     * ignored when there is no EXCLUDE filter clause in the Audience.
     *
     * @var string
     */
    public $exclusionDurationMode;
    protected $filterClausesType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterClause::class;
    protected $filterClausesDataType = 'array';
    /**
     * Required. Immutable. The duration a user should stay in an Audience. It
     * cannot be set to more than 540 days.
     *
     * @var int
     */
    public $membershipDurationDays;
    /**
     * Output only. The resource name for this Audience resource. Format:
     * properties/{propertyId}/audiences/{audienceId}
     *
     * @var string
     */
    public $name;
    /**
     * Output only. It is automatically set by GA to false if this is an NPA
     * Audience and is excluded from ads personalization.
     *
     * @param bool $adsPersonalizationEnabled
     */
    public function setAdsPersonalizationEnabled($adsPersonalizationEnabled)
    {
        $this->adsPersonalizationEnabled = $adsPersonalizationEnabled;
    }
    /**
     * @return bool
     */
    public function getAdsPersonalizationEnabled()
    {
        return $this->adsPersonalizationEnabled;
    }
    /**
     * Output only. Time when the Audience was created.
     *
     * @param string $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }
    /**
     * @return string
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
    /**
     * Required. The description of the Audience.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Required. The display name of the Audience.
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * Optional. Specifies an event to log when a user joins the Audience. If not
     * set, no event is logged when a user joins the Audience.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceEventTrigger $eventTrigger
     */
    public function setEventTrigger(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceEventTrigger $eventTrigger)
    {
        $this->eventTrigger = $eventTrigger;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceEventTrigger
     */
    public function getEventTrigger()
    {
        return $this->eventTrigger;
    }
    /**
     * Immutable. Specifies how long an exclusion lasts for users that meet the
     * exclusion filter. It is applied to all EXCLUDE filter clauses and is
     * ignored when there is no EXCLUDE filter clause in the Audience.
     *
     * Accepted values: AUDIENCE_EXCLUSION_DURATION_MODE_UNSPECIFIED,
     * EXCLUDE_TEMPORARILY, EXCLUDE_PERMANENTLY
     *
     * @param self::EXCLUSION_DURATION_MODE_* $exclusionDurationMode
     */
    public function setExclusionDurationMode($exclusionDurationMode)
    {
        $this->exclusionDurationMode = $exclusionDurationMode;
    }
    /**
     * @return self::EXCLUSION_DURATION_MODE_*
     */
    public function getExclusionDurationMode()
    {
        return $this->exclusionDurationMode;
    }
    /**
     * Required. Immutable. Unordered list. Filter clauses that define the
     * Audience. All clauses will be AND’ed together.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterClause[] $filterClauses
     */
    public function setFilterClauses($filterClauses)
    {
        $this->filterClauses = $filterClauses;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceFilterClause[]
     */
    public function getFilterClauses()
    {
        return $this->filterClauses;
    }
    /**
     * Required. Immutable. The duration a user should stay in an Audience. It
     * cannot be set to more than 540 days.
     *
     * @param int $membershipDurationDays
     */
    public function setMembershipDurationDays($membershipDurationDays)
    {
        $this->membershipDurationDays = $membershipDurationDays;
    }
    /**
     * @return int
     */
    public function getMembershipDurationDays()
    {
        return $this->membershipDurationDays;
    }
    /**
     * Output only. The resource name for this Audience resource. Format:
     * properties/{propertyId}/audiences/{audienceId}
     *
     * @param string $name
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
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudience::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudience');
