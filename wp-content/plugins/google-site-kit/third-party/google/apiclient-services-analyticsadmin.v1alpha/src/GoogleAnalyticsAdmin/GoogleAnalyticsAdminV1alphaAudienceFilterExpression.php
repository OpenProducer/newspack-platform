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

class GoogleAnalyticsAdminV1alphaAudienceFilterExpression extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $andGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList::class;
    protected $andGroupDataType = '';
    protected $dimensionOrMetricFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter::class;
    protected $dimensionOrMetricFilterDataType = '';
    protected $eventFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceEventFilter::class;
    protected $eventFilterDataType = '';
    protected $notExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpression::class;
    protected $notExpressionDataType = '';
    protected $orGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList::class;
    protected $orGroupDataType = '';
    /**
     * A list of expressions to be AND’ed together. It can only contain
     * AudienceFilterExpressions with or_group. This must be set for the top level
     * AudienceFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList $andGroup
     */
    public function setAndGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList $andGroup)
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
     * A filter on a single dimension or metric. This cannot be set on the top
     * level AudienceFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter $dimensionOrMetricFilter
     */
    public function setDimensionOrMetricFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter $dimensionOrMetricFilter)
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
     * Creates a filter that matches a specific event. This cannot be set on the
     * top level AudienceFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceEventFilter $eventFilter
     */
    public function setEventFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceEventFilter $eventFilter)
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
     * A filter expression to be NOT'ed (For example, inverted, complemented). It
     * can only include a dimension_or_metric_filter. This cannot be set on the
     * top level AudienceFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpression $notExpression
     */
    public function setNotExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpression $notExpression)
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
     * A list of expressions to OR’ed together. It cannot contain
     * AudienceFilterExpressions with and_group or or_group.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList $orGroup
     */
    public function setOrGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpressionList $orGroup)
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpression::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudienceFilterExpression');
