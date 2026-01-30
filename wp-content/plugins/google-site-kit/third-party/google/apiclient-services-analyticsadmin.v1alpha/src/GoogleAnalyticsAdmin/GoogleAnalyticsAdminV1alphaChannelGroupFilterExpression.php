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

class GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $andGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList::class;
    protected $andGroupDataType = '';
    protected $filterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilter::class;
    protected $filterDataType = '';
    protected $notExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression::class;
    protected $notExpressionDataType = '';
    protected $orGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList::class;
    protected $orGroupDataType = '';
    /**
     * A list of expressions to be AND’ed together. It can only contain
     * ChannelGroupFilterExpressions with or_group. This must be set for the top
     * level ChannelGroupFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList $andGroup
     */
    public function setAndGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList $andGroup)
    {
        $this->andGroup = $andGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList
     */
    public function getAndGroup()
    {
        return $this->andGroup;
    }
    /**
     * A filter on a single dimension. This cannot be set on the top level
     * ChannelGroupFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaChannelGroupFilter $filter
     */
    public function setFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilter $filter)
    {
        $this->filter = $filter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChannelGroupFilter
     */
    public function getFilter()
    {
        return $this->filter;
    }
    /**
     * A filter expression to be NOT'ed (that is inverted, complemented). It can
     * only include a dimension_or_metric_filter. This cannot be set on the top
     * level ChannelGroupFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression $notExpression
     */
    public function setNotExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression $notExpression)
    {
        $this->notExpression = $notExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression
     */
    public function getNotExpression()
    {
        return $this->notExpression;
    }
    /**
     * A list of expressions to OR’ed together. It cannot contain
     * ChannelGroupFilterExpressions with and_group or or_group.
     *
     * @param GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList $orGroup
     */
    public function setOrGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList $orGroup)
    {
        $this->orGroup = $orGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChannelGroupFilterExpressionList
     */
    public function getOrGroup()
    {
        return $this->orGroup;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression');
