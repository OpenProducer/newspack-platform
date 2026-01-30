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

class GoogleAnalyticsAdminV1alphaProvisionSubpropertyRequest extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Synchronization mode unknown or not specified.
     */
    public const CUSTOM_DIMENSION_AND_METRIC_SYNCHRONIZATION_MODE_SYNCHRONIZATION_MODE_UNSPECIFIED = 'SYNCHRONIZATION_MODE_UNSPECIFIED';
    /**
     * Entities are not synchronized. Local edits are allowed on the subproperty.
     */
    public const CUSTOM_DIMENSION_AND_METRIC_SYNCHRONIZATION_MODE_NONE = 'NONE';
    /**
     * Entities are synchronized from parent property. Local mutations are not
     * allowed on the subproperty (Create / Update / Delete)
     */
    public const CUSTOM_DIMENSION_AND_METRIC_SYNCHRONIZATION_MODE_ALL = 'ALL';
    /**
     * Optional. The subproperty feature synchronization mode for Custom
     * Dimensions and Metrics
     *
     * @var string
     */
    public $customDimensionAndMetricSynchronizationMode;
    protected $subpropertyType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty::class;
    protected $subpropertyDataType = '';
    protected $subpropertyEventFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilter::class;
    protected $subpropertyEventFilterDataType = '';
    /**
     * Optional. The subproperty feature synchronization mode for Custom
     * Dimensions and Metrics
     *
     * Accepted values: SYNCHRONIZATION_MODE_UNSPECIFIED, NONE, ALL
     *
     * @param self::CUSTOM_DIMENSION_AND_METRIC_SYNCHRONIZATION_MODE_* $customDimensionAndMetricSynchronizationMode
     */
    public function setCustomDimensionAndMetricSynchronizationMode($customDimensionAndMetricSynchronizationMode)
    {
        $this->customDimensionAndMetricSynchronizationMode = $customDimensionAndMetricSynchronizationMode;
    }
    /**
     * @return self::CUSTOM_DIMENSION_AND_METRIC_SYNCHRONIZATION_MODE_*
     */
    public function getCustomDimensionAndMetricSynchronizationMode()
    {
        return $this->customDimensionAndMetricSynchronizationMode;
    }
    /**
     * Required. The subproperty to create.
     *
     * @param GoogleAnalyticsAdminV1alphaProperty $subproperty
     */
    public function setSubproperty(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty $subproperty)
    {
        $this->subproperty = $subproperty;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaProperty
     */
    public function getSubproperty()
    {
        return $this->subproperty;
    }
    /**
     * Optional. The subproperty event filter to create on an ordinary property.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilter $subpropertyEventFilter
     */
    public function setSubpropertyEventFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilter $subpropertyEventFilter)
    {
        $this->subpropertyEventFilter = $subpropertyEventFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilter
     */
    public function getSubpropertyEventFilter()
    {
        return $this->subpropertyEventFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProvisionSubpropertyRequest::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaProvisionSubpropertyRequest');
