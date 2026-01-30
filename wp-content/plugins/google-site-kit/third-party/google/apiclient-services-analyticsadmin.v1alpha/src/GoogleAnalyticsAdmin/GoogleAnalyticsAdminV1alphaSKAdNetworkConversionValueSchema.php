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

class GoogleAnalyticsAdminV1alphaSKAdNetworkConversionValueSchema extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * If enabled, the GA SDK will set conversion values using this schema
     * definition, and schema will be exported to any Google Ads accounts linked
     * to this property. If disabled, the GA SDK will not automatically set
     * conversion values, and also the schema will not be exported to Ads.
     *
     * @var bool
     */
    public $applyConversionValues;
    /**
     * Output only. Resource name of the schema. This will be child of ONLY an iOS
     * stream, and there can be at most one such child under an iOS stream.
     * Format: properties/{property}/dataStreams/{dataStream}/sKAdNetworkConversio
     * nValueSchema
     *
     * @var string
     */
    public $name;
    protected $postbackWindowOneType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaPostbackWindow::class;
    protected $postbackWindowOneDataType = '';
    protected $postbackWindowThreeType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaPostbackWindow::class;
    protected $postbackWindowThreeDataType = '';
    protected $postbackWindowTwoType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaPostbackWindow::class;
    protected $postbackWindowTwoDataType = '';
    /**
     * If enabled, the GA SDK will set conversion values using this schema
     * definition, and schema will be exported to any Google Ads accounts linked
     * to this property. If disabled, the GA SDK will not automatically set
     * conversion values, and also the schema will not be exported to Ads.
     *
     * @param bool $applyConversionValues
     */
    public function setApplyConversionValues($applyConversionValues)
    {
        $this->applyConversionValues = $applyConversionValues;
    }
    /**
     * @return bool
     */
    public function getApplyConversionValues()
    {
        return $this->applyConversionValues;
    }
    /**
     * Output only. Resource name of the schema. This will be child of ONLY an iOS
     * stream, and there can be at most one such child under an iOS stream.
     * Format: properties/{property}/dataStreams/{dataStream}/sKAdNetworkConversio
     * nValueSchema
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
    /**
     * Required. The conversion value settings for the first postback window.
     * These differ from values for postback window two and three in that they
     * contain a "Fine" grained conversion value (a numeric value). Conversion
     * values for this postback window must be set. The other windows are optional
     * and may inherit this window's settings if unset or disabled.
     *
     * @param GoogleAnalyticsAdminV1alphaPostbackWindow $postbackWindowOne
     */
    public function setPostbackWindowOne(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaPostbackWindow $postbackWindowOne)
    {
        $this->postbackWindowOne = $postbackWindowOne;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaPostbackWindow
     */
    public function getPostbackWindowOne()
    {
        return $this->postbackWindowOne;
    }
    /**
     * The conversion value settings for the third postback window. This field
     * should only be set if the user chose to define different conversion values
     * for this postback window. It is allowed to configure window 3 without
     * setting window 2. In case window 1 & 2 settings are set and
     * enable_postback_window_settings for this postback window is set to false,
     * the schema will inherit settings from postback_window_two.
     *
     * @param GoogleAnalyticsAdminV1alphaPostbackWindow $postbackWindowThree
     */
    public function setPostbackWindowThree(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaPostbackWindow $postbackWindowThree)
    {
        $this->postbackWindowThree = $postbackWindowThree;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaPostbackWindow
     */
    public function getPostbackWindowThree()
    {
        return $this->postbackWindowThree;
    }
    /**
     * The conversion value settings for the second postback window. This field
     * should only be configured if there is a need to define different conversion
     * values for this postback window. If enable_postback_window_settings is set
     * to false for this postback window, the values from postback_window_one will
     * be used.
     *
     * @param GoogleAnalyticsAdminV1alphaPostbackWindow $postbackWindowTwo
     */
    public function setPostbackWindowTwo(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaPostbackWindow $postbackWindowTwo)
    {
        $this->postbackWindowTwo = $postbackWindowTwo;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaPostbackWindow
     */
    public function getPostbackWindowTwo()
    {
        return $this->postbackWindowTwo;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSKAdNetworkConversionValueSchema::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSKAdNetworkConversionValueSchema');
