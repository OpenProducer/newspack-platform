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

class GoogleAnalyticsAdminV1alphaPostbackWindow extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'conversionValues';
    protected $conversionValuesType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaConversionValues::class;
    protected $conversionValuesDataType = 'array';
    /**
     * If enable_postback_window_settings is true, conversion_values must be
     * populated and will be used for determining when and how to set the
     * Conversion Value on a client device and exporting schema to linked Ads
     * accounts. If false, the settings are not used, but are retained in case
     * they may be used in the future. This must always be true for
     * postback_window_one.
     *
     * @var bool
     */
    public $postbackWindowSettingsEnabled;
    /**
     * Ordering of the repeated field will be used to prioritize the conversion
     * value settings. Lower indexed entries are prioritized higher. The first
     * conversion value setting that evaluates to true will be selected. It must
     * have at least one entry if enable_postback_window_settings is set to true.
     * It can have maximum of 128 entries.
     *
     * @param GoogleAnalyticsAdminV1alphaConversionValues[] $conversionValues
     */
    public function setConversionValues($conversionValues)
    {
        $this->conversionValues = $conversionValues;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaConversionValues[]
     */
    public function getConversionValues()
    {
        return $this->conversionValues;
    }
    /**
     * If enable_postback_window_settings is true, conversion_values must be
     * populated and will be used for determining when and how to set the
     * Conversion Value on a client device and exporting schema to linked Ads
     * accounts. If false, the settings are not used, but are retained in case
     * they may be used in the future. This must always be true for
     * postback_window_one.
     *
     * @param bool $postbackWindowSettingsEnabled
     */
    public function setPostbackWindowSettingsEnabled($postbackWindowSettingsEnabled)
    {
        $this->postbackWindowSettingsEnabled = $postbackWindowSettingsEnabled;
    }
    /**
     * @return bool
     */
    public function getPostbackWindowSettingsEnabled()
    {
        return $this->postbackWindowSettingsEnabled;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaPostbackWindow::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaPostbackWindow');
