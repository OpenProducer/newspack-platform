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

class GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $filterConditionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterCondition::class;
    protected $filterConditionDataType = '';
    protected $notExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression::class;
    protected $notExpressionDataType = '';
    protected $orGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpressionList::class;
    protected $orGroupDataType = '';
    /**
     * Creates a filter that matches a specific event. This cannot be set on the
     * top level SubpropertyEventFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilterCondition $filterCondition
     */
    public function setFilterCondition(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterCondition $filterCondition)
    {
        $this->filterCondition = $filterCondition;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilterCondition
     */
    public function getFilterCondition()
    {
        return $this->filterCondition;
    }
    /**
     * A filter expression to be NOT'ed (inverted, complemented). It can only
     * include a filter. This cannot be set on the top level
     * SubpropertyEventFilterExpression.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression $notExpression
     */
    public function setNotExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression $notExpression)
    {
        $this->notExpression = $notExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression
     */
    public function getNotExpression()
    {
        return $this->notExpression;
    }
    /**
     * A list of expressions to OR’ed together. Must only contain not_expression
     * or filter_condition expressions.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpressionList $orGroup
     */
    public function setOrGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpressionList $orGroup)
    {
        $this->orGroup = $orGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpressionList
     */
    public function getOrGroup()
    {
        return $this->orGroup;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression');
