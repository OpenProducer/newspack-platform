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

class GoogleAnalyticsAdminV1alphaAdSenseLink extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Immutable. The AdSense ad client code that the Google Analytics property is
     * linked to. Example format: "ca-pub-1234567890"
     *
     * @var string
     */
    public $adClientCode;
    /**
     * Output only. The resource name for this AdSense Link resource. Format:
     * properties/{propertyId}/adSenseLinks/{linkId} Example:
     * properties/1234/adSenseLinks/6789
     *
     * @var string
     */
    public $name;
    /**
     * Immutable. The AdSense ad client code that the Google Analytics property is
     * linked to. Example format: "ca-pub-1234567890"
     *
     * @param string $adClientCode
     */
    public function setAdClientCode($adClientCode)
    {
        $this->adClientCode = $adClientCode;
    }
    /**
     * @return string
     */
    public function getAdClientCode()
    {
        return $this->adClientCode;
    }
    /**
     * Output only. The resource name for this AdSense Link resource. Format:
     * properties/{propertyId}/adSenseLinks/{linkId} Example:
     * properties/1234/adSenseLinks/6789
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAdSenseLink::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAdSenseLink');
