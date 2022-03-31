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
namespace Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin;

class GoogleAnalyticsAdminV1alphaListFirebaseLinksResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'firebaseLinks';
    protected $firebaseLinksType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaFirebaseLink::class;
    protected $firebaseLinksDataType = 'array';
    /**
     * @var string
     */
    public $nextPageToken;
    /**
     * @param GoogleAnalyticsAdminV1alphaFirebaseLink[]
     */
    public function setFirebaseLinks($firebaseLinks)
    {
        $this->firebaseLinks = $firebaseLinks;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaFirebaseLink[]
     */
    public function getFirebaseLinks()
    {
        return $this->firebaseLinks;
    }
    /**
     * @param string
     */
    public function setNextPageToken($nextPageToken)
    {
        $this->nextPageToken = $nextPageToken;
    }
    /**
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->nextPageToken;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaListFirebaseLinksResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaListFirebaseLinksResponse');
