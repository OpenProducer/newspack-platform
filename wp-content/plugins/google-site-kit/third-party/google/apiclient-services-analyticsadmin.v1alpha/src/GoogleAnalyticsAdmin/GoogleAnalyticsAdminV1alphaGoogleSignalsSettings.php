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

class GoogleAnalyticsAdminV1alphaGoogleSignalsSettings extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Google Signals consent value defaults to
     * GOOGLE_SIGNALS_CONSENT_UNSPECIFIED. This will be treated as
     * GOOGLE_SIGNALS_CONSENT_NOT_CONSENTED.
     */
    public const CONSENT_GOOGLE_SIGNALS_CONSENT_UNSPECIFIED = 'GOOGLE_SIGNALS_CONSENT_UNSPECIFIED';
    /**
     * Terms of service have been accepted
     */
    public const CONSENT_GOOGLE_SIGNALS_CONSENT_CONSENTED = 'GOOGLE_SIGNALS_CONSENT_CONSENTED';
    /**
     * Terms of service have not been accepted
     */
    public const CONSENT_GOOGLE_SIGNALS_CONSENT_NOT_CONSENTED = 'GOOGLE_SIGNALS_CONSENT_NOT_CONSENTED';
    /**
     * Google Signals status defaults to GOOGLE_SIGNALS_STATE_UNSPECIFIED to
     * represent that the user has not made an explicit choice.
     */
    public const STATE_GOOGLE_SIGNALS_STATE_UNSPECIFIED = 'GOOGLE_SIGNALS_STATE_UNSPECIFIED';
    /**
     * Google Signals is enabled.
     */
    public const STATE_GOOGLE_SIGNALS_ENABLED = 'GOOGLE_SIGNALS_ENABLED';
    /**
     * Google Signals is disabled.
     */
    public const STATE_GOOGLE_SIGNALS_DISABLED = 'GOOGLE_SIGNALS_DISABLED';
    /**
     * Output only. Terms of Service acceptance.
     *
     * @var string
     */
    public $consent;
    /**
     * Output only. Resource name of this setting. Format:
     * properties/{property_id}/googleSignalsSettings Example:
     * "properties/1000/googleSignalsSettings"
     *
     * @var string
     */
    public $name;
    /**
     * Status of this setting.
     *
     * @var string
     */
    public $state;
    /**
     * Output only. Terms of Service acceptance.
     *
     * Accepted values: GOOGLE_SIGNALS_CONSENT_UNSPECIFIED,
     * GOOGLE_SIGNALS_CONSENT_CONSENTED, GOOGLE_SIGNALS_CONSENT_NOT_CONSENTED
     *
     * @param self::CONSENT_* $consent
     */
    public function setConsent($consent)
    {
        $this->consent = $consent;
    }
    /**
     * @return self::CONSENT_*
     */
    public function getConsent()
    {
        return $this->consent;
    }
    /**
     * Output only. Resource name of this setting. Format:
     * properties/{property_id}/googleSignalsSettings Example:
     * "properties/1000/googleSignalsSettings"
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
     * Status of this setting.
     *
     * Accepted values: GOOGLE_SIGNALS_STATE_UNSPECIFIED, GOOGLE_SIGNALS_ENABLED,
     * GOOGLE_SIGNALS_DISABLED
     *
     * @param self::STATE_* $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
    /**
     * @return self::STATE_*
     */
    public function getState()
    {
        return $this->state;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaGoogleSignalsSettings::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaGoogleSignalsSettings');
