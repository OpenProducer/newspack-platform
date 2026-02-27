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
namespace Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle;

class RecurrenceDetails extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * The plan id associated with the order.
     *
     * @var string
     */
    public $planId;
    protected $recurrencePeriodType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\RecurrencePeriod::class;
    protected $recurrencePeriodDataType = '';
    /**
     * The plan id associated with the order.
     *
     * @param string $planId
     */
    public function setPlanId($planId)
    {
        $this->planId = $planId;
    }
    /**
     * @return string
     */
    public function getPlanId()
    {
        return $this->planId;
    }
    /**
     * The billing frequency of the recurrence.
     *
     * @param RecurrencePeriod $recurrencePeriod
     */
    public function setRecurrencePeriod(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\RecurrencePeriod $recurrencePeriod)
    {
        $this->recurrencePeriod = $recurrencePeriod;
    }
    /**
     * @return RecurrencePeriod
     */
    public function getRecurrencePeriod()
    {
        return $this->recurrencePeriod;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\RecurrenceDetails::class, 'Google\\Site_Kit_Dependencies\\Google_Service_SubscribewithGoogle_RecurrenceDetails');
