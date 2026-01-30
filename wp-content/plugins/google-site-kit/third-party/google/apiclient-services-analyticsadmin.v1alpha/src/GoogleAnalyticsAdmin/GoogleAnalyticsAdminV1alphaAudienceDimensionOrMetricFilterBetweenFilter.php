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

class GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterBetweenFilter extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $fromValueType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue::class;
    protected $fromValueDataType = '';
    protected $toValueType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue::class;
    protected $toValueDataType = '';
    /**
     * Required. Begins with this number, inclusive.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue $fromValue
     */
    public function setFromValue(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue $fromValue)
    {
        $this->fromValue = $fromValue;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue
     */
    public function getFromValue()
    {
        return $this->fromValue;
    }
    /**
     * Required. Ends with this number, inclusive.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue $toValue
     */
    public function setToValue(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue $toValue)
    {
        $this->toValue = $toValue;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericValue
     */
    public function getToValue()
    {
        return $this->toValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterBetweenFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterBetweenFilter');
