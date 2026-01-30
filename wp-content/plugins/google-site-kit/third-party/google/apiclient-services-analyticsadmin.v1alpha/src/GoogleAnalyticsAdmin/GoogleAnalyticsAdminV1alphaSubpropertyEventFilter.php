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

class GoogleAnalyticsAdminV1alphaSubpropertyEventFilter extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'filterClauses';
    /**
     * Immutable. Resource name of the Subproperty that uses this filter.
     *
     * @var string
     */
    public $applyToProperty;
    protected $filterClausesType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterClause::class;
    protected $filterClausesDataType = 'array';
    /**
     * Output only. Format: properties/{ordinary_property_id}/subpropertyEventFilt
     * ers/{sub_property_event_filter} Example:
     * properties/1234/subpropertyEventFilters/5678
     *
     * @var string
     */
    public $name;
    /**
     * Immutable. Resource name of the Subproperty that uses this filter.
     *
     * @param string $applyToProperty
     */
    public function setApplyToProperty($applyToProperty)
    {
        $this->applyToProperty = $applyToProperty;
    }
    /**
     * @return string
     */
    public function getApplyToProperty()
    {
        return $this->applyToProperty;
    }
    /**
     * Required. Unordered list. Filter clauses that define the
     * SubpropertyEventFilter. All clauses are AND'ed together to determine what
     * data is sent to the subproperty.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilterClause[] $filterClauses
     */
    public function setFilterClauses($filterClauses)
    {
        $this->filterClauses = $filterClauses;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilterClause[]
     */
    public function getFilterClauses()
    {
        return $this->filterClauses;
    }
    /**
     * Output only. Format: properties/{ordinary_property_id}/subpropertyEventFilt
     * ers/{sub_property_event_filter} Example:
     * properties/1234/subpropertyEventFilters/5678
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSubpropertyEventFilter');
