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

class GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $andGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpressionList::class;
    protected $andGroupDataType = '';
    protected $filterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilter::class;
    protected $filterDataType = '';
    protected $notExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression::class;
    protected $notExpressionDataType = '';
    /**
     * A list of expressions to be AND’ed together. It must contain a
     * ExpandedDataSetFilterExpression with either not_expression or
     * dimension_filter. This must be set for the top level
     * ExpandedDataSetFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpressionList $andGroup
     */
    public function setAndGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpressionList $andGroup)
    {
        $this->andGroup = $andGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpressionList
     */
    public function getAndGroup()
    {
        return $this->andGroup;
    }
    /**
     * A filter on a single dimension. This cannot be set on the top level
     * ExpandedDataSetFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaExpandedDataSetFilter $filter
     */
    public function setFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilter $filter)
    {
        $this->filter = $filter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaExpandedDataSetFilter
     */
    public function getFilter()
    {
        return $this->filter;
    }
    /**
     * A filter expression to be NOT'ed (that is, inverted, complemented). It must
     * include a dimension_filter. This cannot be set on the top level
     * ExpandedDataSetFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression $notExpression
     */
    public function setNotExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression $notExpression)
    {
        $this->notExpression = $notExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression
     */
    public function getNotExpression()
    {
        return $this->notExpression;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression');
