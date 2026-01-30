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

class GoogleAnalyticsAdminV1alphaEventMapping extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Required. Name of the Google Analytics event. It must always be set. The
     * max allowed display name length is 40 UTF-16 code units.
     *
     * @var string
     */
    public $eventName;
    /**
     * The maximum number of times the event occurred. If not set, maximum event
     * count won't be checked.
     *
     * @var string
     */
    public $maxEventCount;
    /**
     * The maximum revenue generated due to the event. Revenue currency will be
     * defined at the property level. If not set, maximum event value won't be
     * checked.
     *
     * @var 
     */
    public $maxEventValue;
    /**
     * At least one of the following four min/max values must be set. The values
     * set will be ANDed together to qualify an event. The minimum number of times
     * the event occurred. If not set, minimum event count won't be checked.
     *
     * @var string
     */
    public $minEventCount;
    /**
     * The minimum revenue generated due to the event. Revenue currency will be
     * defined at the property level. If not set, minimum event value won't be
     * checked.
     *
     * @var 
     */
    public $minEventValue;
    /**
     * Required. Name of the Google Analytics event. It must always be set. The
     * max allowed display name length is 40 UTF-16 code units.
     *
     * @param string $eventName
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
    }
    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }
    /**
     * The maximum number of times the event occurred. If not set, maximum event
     * count won't be checked.
     *
     * @param string $maxEventCount
     */
    public function setMaxEventCount($maxEventCount)
    {
        $this->maxEventCount = $maxEventCount;
    }
    /**
     * @return string
     */
    public function getMaxEventCount()
    {
        return $this->maxEventCount;
    }
    public function setMaxEventValue($maxEventValue)
    {
        $this->maxEventValue = $maxEventValue;
    }
    public function getMaxEventValue()
    {
        return $this->maxEventValue;
    }
    /**
     * At least one of the following four min/max values must be set. The values
     * set will be ANDed together to qualify an event. The minimum number of times
     * the event occurred. If not set, minimum event count won't be checked.
     *
     * @param string $minEventCount
     */
    public function setMinEventCount($minEventCount)
    {
        $this->minEventCount = $minEventCount;
    }
    /**
     * @return string
     */
    public function getMinEventCount()
    {
        return $this->minEventCount;
    }
    public function setMinEventValue($minEventValue)
    {
        $this->minEventValue = $minEventValue;
    }
    public function getMinEventValue()
    {
        return $this->minEventValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventMapping::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaEventMapping');
