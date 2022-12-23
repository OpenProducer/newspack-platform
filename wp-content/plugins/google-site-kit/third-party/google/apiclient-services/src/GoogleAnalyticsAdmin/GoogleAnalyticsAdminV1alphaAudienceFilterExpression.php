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
namespace Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin;

class GoogleAnalyticsAdminV1alphaAudienceFilterExpression extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $andGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList::class;
    protected $andGroupDataType = '';
    protected $dimensionOrMetricFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter::class;
    protected $dimensionOrMetricFilterDataType = '';
    protected $eventFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceEventFilter::class;
    protected $eventFilterDataType = '';
    protected $notExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceFilterExpression::class;
    protected $notExpressionDataType = '';
    protected $orGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList::class;
    protected $orGroupDataType = '';
    /**
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList
     */
    public function setAndGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList $andGroup)
    {
        $this->andGroup = $andGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList
     */
    public function getAndGroup()
    {
        return $this->andGroup;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter
     */
    public function setDimensionOrMetricFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter $dimensionOrMetricFilter)
    {
        $this->dimensionOrMetricFilter = $dimensionOrMetricFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter
     */
    public function getDimensionOrMetricFilter()
    {
        return $this->dimensionOrMetricFilter;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAudienceEventFilter
     */
    public function setEventFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceEventFilter $eventFilter)
    {
        $this->eventFilter = $eventFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceEventFilter
     */
    public function getEventFilter()
    {
        return $this->eventFilter;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpression
     */
    public function setNotExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceFilterExpression $notExpression)
    {
        $this->notExpression = $notExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceFilterExpression
     */
    public function getNotExpression()
    {
        return $this->notExpression;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList
     */
    public function setOrGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList $orGroup)
    {
        $this->orGroup = $orGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList
     */
    public function getOrGroup()
    {
        return $this->orGroup;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAudienceFilterExpression::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaAudienceFilterExpression');
