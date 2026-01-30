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

class GoogleAnalyticsAdminV1alphaAccessFilterExpression extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $accessFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilter::class;
    protected $accessFilterDataType = '';
    protected $andGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilterExpressionList::class;
    protected $andGroupDataType = '';
    protected $notExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilterExpression::class;
    protected $notExpressionDataType = '';
    protected $orGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilterExpressionList::class;
    protected $orGroupDataType = '';
    /**
     * A primitive filter. In the same FilterExpression, all of the filter's field
     * names need to be either all dimensions or all metrics.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessFilter $accessFilter
     */
    public function setAccessFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilter $accessFilter)
    {
        $this->accessFilter = $accessFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessFilter
     */
    public function getAccessFilter()
    {
        return $this->accessFilter;
    }
    /**
     * Each of the FilterExpressions in the and_group has an AND relationship.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessFilterExpressionList $andGroup
     */
    public function setAndGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilterExpressionList $andGroup)
    {
        $this->andGroup = $andGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessFilterExpressionList
     */
    public function getAndGroup()
    {
        return $this->andGroup;
    }
    /**
     * The FilterExpression is NOT of not_expression.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessFilterExpression $notExpression
     */
    public function setNotExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilterExpression $notExpression)
    {
        $this->notExpression = $notExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessFilterExpression
     */
    public function getNotExpression()
    {
        return $this->notExpression;
    }
    /**
     * Each of the FilterExpressions in the or_group has an OR relationship.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessFilterExpressionList $orGroup
     */
    public function setOrGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilterExpressionList $orGroup)
    {
        $this->orGroup = $orGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessFilterExpressionList
     */
    public function getOrGroup()
    {
        return $this->orGroup;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessFilterExpression::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAccessFilterExpression');
