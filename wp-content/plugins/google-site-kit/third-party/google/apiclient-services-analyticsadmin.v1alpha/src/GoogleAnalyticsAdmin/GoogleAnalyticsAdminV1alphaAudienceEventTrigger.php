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

class GoogleAnalyticsAdminV1alphaAudienceEventTrigger extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Log condition is not specified.
     */
    public const LOG_CONDITION_LOG_CONDITION_UNSPECIFIED = 'LOG_CONDITION_UNSPECIFIED';
    /**
     * The event should be logged only when a user is joined.
     */
    public const LOG_CONDITION_AUDIENCE_JOINED = 'AUDIENCE_JOINED';
    /**
     * The event should be logged whenever the Audience condition is met, even if
     * the user is already a member of the Audience.
     */
    public const LOG_CONDITION_AUDIENCE_MEMBERSHIP_RENEWED = 'AUDIENCE_MEMBERSHIP_RENEWED';
    /**
     * Required. The event name that will be logged.
     *
     * @var string
     */
    public $eventName;
    /**
     * Required. When to log the event.
     *
     * @var string
     */
    public $logCondition;
    /**
     * Required. The event name that will be logged.
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
     * Required. When to log the event.
     *
     * Accepted values: LOG_CONDITION_UNSPECIFIED, AUDIENCE_JOINED,
     * AUDIENCE_MEMBERSHIP_RENEWED
     *
     * @param self::LOG_CONDITION_* $logCondition
     */
    public function setLogCondition($logCondition)
    {
        $this->logCondition = $logCondition;
    }
    /**
     * @return self::LOG_CONDITION_*
     */
    public function getLogCondition()
    {
        return $this->logCondition;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceEventTrigger::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudienceEventTrigger');
