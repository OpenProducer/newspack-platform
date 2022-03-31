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
namespace Google\Site_Kit_Dependencies\Google\Service\TagManager;

class TagConsentSetting extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * @var string
     */
    public $consentStatus;
    protected $consentTypeType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $consentTypeDataType = '';
    /**
     * @param string
     */
    public function setConsentStatus($consentStatus)
    {
        $this->consentStatus = $consentStatus;
    }
    /**
     * @return string
     */
    public function getConsentStatus()
    {
        return $this->consentStatus;
    }
    /**
     * @param Parameter
     */
    public function setConsentType(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $consentType)
    {
        $this->consentType = $consentType;
    }
    /**
     * @return Parameter
     */
    public function getConsentType()
    {
        return $this->consentType;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\TagConsentSetting::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_TagConsentSetting');
