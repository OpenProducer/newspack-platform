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

class GoogleAnalyticsAdminV1alphaListEventEditRulesResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'eventEditRules';
    protected $eventEditRulesType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule::class;
    protected $eventEditRulesDataType = 'array';
    /**
     * A token, which can be sent as `page_token` to retrieve the next page. If
     * this field is omitted, there are no subsequent pages.
     *
     * @var string
     */
    public $nextPageToken;
    /**
     * List of EventEditRules. These will be ordered stably, but in an arbitrary
     * order.
     *
     * @param GoogleAnalyticsAdminV1alphaEventEditRule[] $eventEditRules
     */
    public function setEventEditRules($eventEditRules)
    {
        $this->eventEditRules = $eventEditRules;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaEventEditRule[]
     */
    public function getEventEditRules()
    {
        return $this->eventEditRules;
    }
    /**
     * A token, which can be sent as `page_token` to retrieve the next page. If
     * this field is omitted, there are no subsequent pages.
     *
     * @param string $nextPageToken
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaListEventEditRulesResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaListEventEditRulesResponse');
