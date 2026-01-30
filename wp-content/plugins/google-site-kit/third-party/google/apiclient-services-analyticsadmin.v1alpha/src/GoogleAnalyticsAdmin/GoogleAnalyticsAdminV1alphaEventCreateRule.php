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

class GoogleAnalyticsAdminV1alphaEventCreateRule extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'parameterMutations';
    /**
     * Required. The name of the new event to be created. This value must: * be
     * less than 40 characters * consist only of letters, digits or _
     * (underscores) * start with a letter
     *
     * @var string
     */
    public $destinationEvent;
    protected $eventConditionsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaMatchingCondition::class;
    protected $eventConditionsDataType = 'array';
    /**
     * Output only. Resource name for this EventCreateRule resource. Format: prope
     * rties/{property}/dataStreams/{data_stream}/eventCreateRules/{event_create_r
     * ule}
     *
     * @var string
     */
    public $name;
    protected $parameterMutationsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaParameterMutation::class;
    protected $parameterMutationsDataType = 'array';
    /**
     * If true, the source parameters are copied to the new event. If false, or
     * unset, all non-internal parameters are not copied from the source event.
     * Parameter mutations are applied after the parameters have been copied.
     *
     * @var bool
     */
    public $sourceCopyParameters;
    /**
     * Required. The name of the new event to be created. This value must: * be
     * less than 40 characters * consist only of letters, digits or _
     * (underscores) * start with a letter
     *
     * @param string $destinationEvent
     */
    public function setDestinationEvent($destinationEvent)
    {
        $this->destinationEvent = $destinationEvent;
    }
    /**
     * @return string
     */
    public function getDestinationEvent()
    {
        return $this->destinationEvent;
    }
    /**
     * Required. Must have at least one condition, and can have up to 10 max.
     * Conditions on the source event must match for this rule to be applied.
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
     * Output only. Resource name for this EventCreateRule resource. Format: prope
     * rties/{property}/dataStreams/{data_stream}/eventCreateRules/{event_create_r
     * ule}
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
     * Parameter mutations define parameter behavior on the new event, and are
     * applied in order. A maximum of 20 mutations can be applied.
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
     * If true, the source parameters are copied to the new event. If false, or
     * unset, all non-internal parameters are not copied from the source event.
     * Parameter mutations are applied after the parameters have been copied.
     *
     * @param bool $sourceCopyParameters
     */
    public function setSourceCopyParameters($sourceCopyParameters)
    {
        $this->sourceCopyParameters = $sourceCopyParameters;
    }
    /**
     * @return bool
     */
    public function getSourceCopyParameters()
    {
        return $this->sourceCopyParameters;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventCreateRule::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaEventCreateRule');
