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

class GoogleAnalyticsAdminV1alphaReportingIdentitySettings extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Unspecified blending strategy.
     */
    public const REPORTING_IDENTITY_IDENTITY_BLENDING_STRATEGY_UNSPECIFIED = 'IDENTITY_BLENDING_STRATEGY_UNSPECIFIED';
    /**
     * Blended reporting identity strategy.
     */
    public const REPORTING_IDENTITY_BLENDED = 'BLENDED';
    /**
     * Observed reporting identity strategy.
     */
    public const REPORTING_IDENTITY_OBSERVED = 'OBSERVED';
    /**
     * Device-based reporting identity strategy.
     */
    public const REPORTING_IDENTITY_DEVICE_BASED = 'DEVICE_BASED';
    /**
     * Output only. Identifier. Resource name for this reporting identity settings
     * singleton resource. Format:
     * properties/{property_id}/reportingIdentitySettings Example:
     * "properties/1234/reportingIdentitySettings"
     *
     * @var string
     */
    public $name;
    /**
     * The strategy used for identifying user identities in reports.
     *
     * @var string
     */
    public $reportingIdentity;
    /**
     * Output only. Identifier. Resource name for this reporting identity settings
     * singleton resource. Format:
     * properties/{property_id}/reportingIdentitySettings Example:
     * "properties/1234/reportingIdentitySettings"
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
     * The strategy used for identifying user identities in reports.
     *
     * Accepted values: IDENTITY_BLENDING_STRATEGY_UNSPECIFIED, BLENDED, OBSERVED,
     * DEVICE_BASED
     *
     * @param self::REPORTING_IDENTITY_* $reportingIdentity
     */
    public function setReportingIdentity($reportingIdentity)
    {
        $this->reportingIdentity = $reportingIdentity;
    }
    /**
     * @return self::REPORTING_IDENTITY_*
     */
    public function getReportingIdentity()
    {
        return $this->reportingIdentity;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReportingIdentitySettings::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaReportingIdentitySettings');
