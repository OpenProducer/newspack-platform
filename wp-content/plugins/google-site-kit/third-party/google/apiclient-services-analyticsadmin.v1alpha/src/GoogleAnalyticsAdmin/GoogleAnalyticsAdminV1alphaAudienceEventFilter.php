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

class GoogleAnalyticsAdminV1alphaAudienceEventFilter extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Required. Immutable. The name of the event to match against.
     *
     * @var string
     */
    public $eventName;
    protected $eventParameterFilterExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpression::class;
    protected $eventParameterFilterExpressionDataType = '';
    /**
     * Required. Immutable. The name of the event to match against.
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
     * Optional. If specified, this filter matches events that match both the
     * single event name and the parameter filter expressions. AudienceEventFilter
     * inside the parameter filter expression cannot be set (For example, nested
     * event filters are not supported). This should be a single and_group of
     * dimension_or_metric_filter or not_expression; ANDs of ORs are not
     * supported. Also, if it includes a filter for "eventCount", only that one
     * will be considered; all the other filters will be ignored.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpression $eventParameterFilterExpression
     */
    public function setEventParameterFilterExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpression $eventParameterFilterExpression)
    {
        $this->eventParameterFilterExpression = $eventParameterFilterExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceFilterExpression
     */
    public function getEventParameterFilterExpression()
    {
        return $this->eventParameterFilterExpression;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceEventFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudienceEventFilter');
