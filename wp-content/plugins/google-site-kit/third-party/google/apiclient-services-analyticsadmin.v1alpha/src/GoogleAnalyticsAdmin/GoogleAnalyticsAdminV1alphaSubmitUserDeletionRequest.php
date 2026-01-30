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

class GoogleAnalyticsAdminV1alphaSubmitUserDeletionRequest extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Firebase [application instance ID](https://firebase.google.com/docs/referen
     * ce/android/com/google/firebase/analytics/FirebaseAnalytics.html#getAppInsta
     * nceId).
     *
     * @var string
     */
    public $appInstanceId;
    /**
     * Google Analytics [client
     * ID](https://support.google.com/analytics/answer/11593727).
     *
     * @var string
     */
    public $clientId;
    /**
     * Google Analytics [user
     * ID](https://firebase.google.com/docs/analytics/userid).
     *
     * @var string
     */
    public $userId;
    /**
     * [User-provided data](https://support.google.com/analytics/answer/14077171).
     * May contain either one email address or one phone number. Email addresses
     * should be normalized as such: * lowercase * remove periods before @ for
     * gmail.com/googlemail.com addresses * remove all spaces Phone numbers should
     * be normalized as such: * remove all non digit characters * add + prefix
     *
     * @var string
     */
    public $userProvidedData;
    /**
     * Firebase [application instance ID](https://firebase.google.com/docs/referen
     * ce/android/com/google/firebase/analytics/FirebaseAnalytics.html#getAppInsta
     * nceId).
     *
     * @param string $appInstanceId
     */
    public function setAppInstanceId($appInstanceId)
    {
        $this->appInstanceId = $appInstanceId;
    }
    /**
     * @return string
     */
    public function getAppInstanceId()
    {
        return $this->appInstanceId;
    }
    /**
     * Google Analytics [client
     * ID](https://support.google.com/analytics/answer/11593727).
     *
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }
    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }
    /**
     * Google Analytics [user
     * ID](https://firebase.google.com/docs/analytics/userid).
     *
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }
    /**
     * [User-provided data](https://support.google.com/analytics/answer/14077171).
     * May contain either one email address or one phone number. Email addresses
     * should be normalized as such: * lowercase * remove periods before @ for
     * gmail.com/googlemail.com addresses * remove all spaces Phone numbers should
     * be normalized as such: * remove all non digit characters * add + prefix
     *
     * @param string $userProvidedData
     */
    public function setUserProvidedData($userProvidedData)
    {
        $this->userProvidedData = $userProvidedData;
    }
    /**
     * @return string
     */
    public function getUserProvidedData()
    {
        return $this->userProvidedData;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubmitUserDeletionRequest::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSubmitUserDeletionRequest');
