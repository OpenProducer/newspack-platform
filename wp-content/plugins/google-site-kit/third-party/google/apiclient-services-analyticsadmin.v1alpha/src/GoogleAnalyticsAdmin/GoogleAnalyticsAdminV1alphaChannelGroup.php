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

class GoogleAnalyticsAdminV1alphaChannelGroup extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'groupingRule';
    /**
     * The description of the Channel Group. Max length of 256 characters.
     *
     * @var string
     */
    public $description;
    /**
     * Required. The display name of the Channel Group. Max length of 80
     * characters.
     *
     * @var string
     */
    public $displayName;
    protected $groupingRuleType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaGroupingRule::class;
    protected $groupingRuleDataType = 'array';
    /**
     * Output only. The resource name for this Channel Group resource. Format:
     * properties/{property}/channelGroups/{channel_group}
     *
     * @var string
     */
    public $name;
    /**
     * Optional. If true, this channel group will be used as the default channel
     * group for reports. Only one channel group can be set as `primary` at any
     * time. If the `primary` field gets set on a channel group, it will get unset
     * on the previous primary channel group. The Google Analytics predefined
     * channel group is the primary by default.
     *
     * @var bool
     */
    public $primary;
    /**
     * Output only. If true, then this channel group is the Default Channel Group
     * predefined by Google Analytics. Display name and grouping rules cannot be
     * updated for this channel group.
     *
     * @var bool
     */
    public $systemDefined;
    /**
     * The description of the Channel Group. Max length of 256 characters.
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
     * Required. The display name of the Channel Group. Max length of 80
     * characters.
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
     * Required. The grouping rules of channels. Maximum number of rules is 50.
     *
     * @param GoogleAnalyticsAdminV1alphaGroupingRule[] $groupingRule
     */
    public function setGroupingRule($groupingRule)
    {
        $this->groupingRule = $groupingRule;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaGroupingRule[]
     */
    public function getGroupingRule()
    {
        return $this->groupingRule;
    }
    /**
     * Output only. The resource name for this Channel Group resource. Format:
     * properties/{property}/channelGroups/{channel_group}
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
    /**
     * Optional. If true, this channel group will be used as the default channel
     * group for reports. Only one channel group can be set as `primary` at any
     * time. If the `primary` field gets set on a channel group, it will get unset
     * on the previous primary channel group. The Google Analytics predefined
     * channel group is the primary by default.
     *
     * @param bool $primary
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;
    }
    /**
     * @return bool
     */
    public function getPrimary()
    {
        return $this->primary;
    }
    /**
     * Output only. If true, then this channel group is the Default Channel Group
     * predefined by Google Analytics. Display name and grouping rules cannot be
     * updated for this channel group.
     *
     * @param bool $systemDefined
     */
    public function setSystemDefined($systemDefined)
    {
        $this->systemDefined = $systemDefined;
    }
    /**
     * @return bool
     */
    public function getSystemDefined()
    {
        return $this->systemDefined;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroup::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaChannelGroup');
