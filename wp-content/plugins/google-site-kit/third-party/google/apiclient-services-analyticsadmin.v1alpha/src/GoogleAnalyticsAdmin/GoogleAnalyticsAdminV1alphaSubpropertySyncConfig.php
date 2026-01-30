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

class GoogleAnalyticsAdminV1alphaSubpropertySyncConfig extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Synchronization mode unknown or not specified.
     */
    public const CUSTOM_DIMENSION_AND_METRIC_SYNC_MODE_SYNCHRONIZATION_MODE_UNSPECIFIED = 'SYNCHRONIZATION_MODE_UNSPECIFIED';
    /**
     * Entities are not synchronized. Local edits are allowed on the subproperty.
     */
    public const CUSTOM_DIMENSION_AND_METRIC_SYNC_MODE_NONE = 'NONE';
    /**
     * Entities are synchronized from parent property. Local mutations are not
     * allowed on the subproperty (Create / Update / Delete)
     */
    public const CUSTOM_DIMENSION_AND_METRIC_SYNC_MODE_ALL = 'ALL';
    /**
     * Output only. Immutable. Resource name of the subproperty that these
     * settings apply to.
     *
     * @var string
     */
    public $applyToProperty;
    /**
     * Required. Specifies the Custom Dimension / Metric synchronization mode for
     * the subproperty. If set to ALL, Custom Dimension / Metric synchronization
     * will be immediately enabled. Local configuration of Custom Dimensions /
     * Metrics will not be allowed on the subproperty so long as the
     * synchronization mode is set to ALL. If set to NONE, Custom Dimensions /
     * Metric synchronization is disabled. Custom Dimensions / Metrics must be
     * configured explicitly on the Subproperty.
     *
     * @var string
     */
    public $customDimensionAndMetricSyncMode;
    /**
     * Output only. Identifier. Format:
     * properties/{ordinary_property_id}/subpropertySyncConfigs/{subproperty_id}
     * Example: properties/1234/subpropertySyncConfigs/5678
     *
     * @var string
     */
    public $name;
    /**
     * Output only. Immutable. Resource name of the subproperty that these
     * settings apply to.
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
     * Required. Specifies the Custom Dimension / Metric synchronization mode for
     * the subproperty. If set to ALL, Custom Dimension / Metric synchronization
     * will be immediately enabled. Local configuration of Custom Dimensions /
     * Metrics will not be allowed on the subproperty so long as the
     * synchronization mode is set to ALL. If set to NONE, Custom Dimensions /
     * Metric synchronization is disabled. Custom Dimensions / Metrics must be
     * configured explicitly on the Subproperty.
     *
     * Accepted values: SYNCHRONIZATION_MODE_UNSPECIFIED, NONE, ALL
     *
     * @param self::CUSTOM_DIMENSION_AND_METRIC_SYNC_MODE_* $customDimensionAndMetricSyncMode
     */
    public function setCustomDimensionAndMetricSyncMode($customDimensionAndMetricSyncMode)
    {
        $this->customDimensionAndMetricSyncMode = $customDimensionAndMetricSyncMode;
    }
    /**
     * @return self::CUSTOM_DIMENSION_AND_METRIC_SYNC_MODE_*
     */
    public function getCustomDimensionAndMetricSyncMode()
    {
        return $this->customDimensionAndMetricSyncMode;
    }
    /**
     * Output only. Identifier. Format:
     * properties/{ordinary_property_id}/subpropertySyncConfigs/{subproperty_id}
     * Example: properties/1234/subpropertySyncConfigs/5678
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertySyncConfig::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSubpropertySyncConfig');
