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

class GoogleAnalyticsAdminV1alphaSubpropertyEventFilterClause extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Filter clause type unknown or not specified.
     */
    public const FILTER_CLAUSE_TYPE_FILTER_CLAUSE_TYPE_UNSPECIFIED = 'FILTER_CLAUSE_TYPE_UNSPECIFIED';
    /**
     * Events will be included in the Sub property if the filter clause is met.
     */
    public const FILTER_CLAUSE_TYPE_INCLUDE = 'INCLUDE';
    /**
     * Events will be excluded from the Sub property if the filter clause is met.
     */
    public const FILTER_CLAUSE_TYPE_EXCLUDE = 'EXCLUDE';
    /**
     * Required. The type for the filter clause.
     *
     * @var string
     */
    public $filterClauseType;
    protected $filterExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression::class;
    protected $filterExpressionDataType = '';
    /**
     * Required. The type for the filter clause.
     *
     * Accepted values: FILTER_CLAUSE_TYPE_UNSPECIFIED, INCLUDE, EXCLUDE
     *
     * @param self::FILTER_CLAUSE_TYPE_* $filterClauseType
     */
    public function setFilterClauseType($filterClauseType)
    {
        $this->filterClauseType = $filterClauseType;
    }
    /**
     * @return self::FILTER_CLAUSE_TYPE_*
     */
    public function getFilterClauseType()
    {
        return $this->filterClauseType;
    }
    /**
     * Required. The logical expression for what events are sent to the
     * subproperty.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression $filterExpression
     */
    public function setFilterExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression $filterExpression)
    {
        $this->filterExpression = $filterExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilterExpression
     */
    public function getFilterExpression()
    {
        return $this->filterExpression;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterClause::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSubpropertyEventFilterClause');
