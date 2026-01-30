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

class GoogleAnalyticsAdminV1alphaEventEditRule extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'parameterMutations';
    /**
     * Required. The display name of this event edit rule. Maximum of 255
     * characters.
     *
     * @var string
     */
    public $displayName;
    protected $eventConditionsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaMatchingCondition::class;
    protected $eventConditionsDataType = 'array';
    /**
     * Identifier. Resource name for this EventEditRule resource. Format: properti
     * es/{property}/dataStreams/{data_stream}/eventEditRules/{event_edit_rule}
     *
     * @var string
     */
    public $name;
    protected $parameterMutationsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaParameterMutation::class;
    protected $parameterMutationsDataType = 'array';
    /**
     * Output only. The order for which this rule will be processed. Rules with an
     * order value lower than this will be processed before this rule, rules with
     * an order value higher than this will be processed after this rule. New
     * event edit rules will be assigned an order value at the end of the order.
     * This value does not apply to event create rules.
     *
     * @var string
     */
    public $processingOrder;
    /**
     * Required. The display name of this event edit rule. Maximum of 255
     * characters.
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * Required. Conditions on the source event must match for this rule to be
     * applied. Must have at least one condition, and can have up to 10 max.
     *
     * @param GoogleAnalyticsAdminV1alphaMatchingCondition[] $eventConditions
     */
    public function setEventConditions($eventConditions)
    {
        $this->eventConditions = $eventConditions;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaMatchingCondition[]
     */
    public function getEventConditions()
    {
        return $this->eventConditions;
    }
    /**
     * Identifier. Resource name for this EventEditRule resource. Format: properti
     * es/{property}/dataStreams/{data_stream}/eventEditRules/{event_edit_rule}
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
     * Required. Parameter mutations define parameter behavior on the new event,
     * and are applied in order. A maximum of 20 mutations can be applied.
     *
     * @param GoogleAnalyticsAdminV1alphaParameterMutation[] $parameterMutations
     */
    public function setParameterMutations($parameterMutations)
    {
        $this->parameterMutations = $parameterMutations;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaParameterMutation[]
     */
    public function getParameterMutations()
    {
        return $this->parameterMutations;
    }
    /**
     * Output only. The order for which this rule will be processed. Rules with an
     * order value lower than this will be processed before this rule, rules with
     * an order value higher than this will be processed after this rule. New
     * event edit rules will be assigned an order value at the end of the order.
     * This value does not apply to event create rules.
     *
     * @param string $processingOrder
     */
    public function setProcessingOrder($processingOrder)
    {
        $this->processingOrder = $processingOrder;
    }
    /**
     * @return string
     */
    public function getProcessingOrder()
    {
        return $this->processingOrder;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaEventEditRule');
