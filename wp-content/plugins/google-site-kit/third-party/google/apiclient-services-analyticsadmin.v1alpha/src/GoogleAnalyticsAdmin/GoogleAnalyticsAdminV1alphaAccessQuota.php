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

class GoogleAnalyticsAdminV1alphaAccessQuota extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $concurrentRequestsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $concurrentRequestsDataType = '';
    protected $serverErrorsPerProjectPerHourType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $serverErrorsPerProjectPerHourDataType = '';
    protected $tokensPerDayType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $tokensPerDayDataType = '';
    protected $tokensPerHourType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $tokensPerHourDataType = '';
    protected $tokensPerProjectPerHourType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $tokensPerProjectPerHourDataType = '';
    /**
     * Properties can use up to 50 concurrent requests.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus $concurrentRequests
     */
    public function setConcurrentRequests(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $concurrentRequests)
    {
        $this->concurrentRequests = $concurrentRequests;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function getConcurrentRequests()
    {
        return $this->concurrentRequests;
    }
    /**
     * Properties and cloud project pairs can have up to 50 server errors per
     * hour.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus $serverErrorsPerProjectPerHour
     */
    public function setServerErrorsPerProjectPerHour(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $serverErrorsPerProjectPerHour)
    {
        $this->serverErrorsPerProjectPerHour = $serverErrorsPerProjectPerHour;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function getServerErrorsPerProjectPerHour()
    {
        return $this->serverErrorsPerProjectPerHour;
    }
    /**
     * Properties can use 250,000 tokens per day. Most requests consume fewer than
     * 10 tokens.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerDay
     */
    public function setTokensPerDay(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerDay)
    {
        $this->tokensPerDay = $tokensPerDay;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function getTokensPerDay()
    {
        return $this->tokensPerDay;
    }
    /**
     * Properties can use 50,000 tokens per hour. An API request consumes a single
     * number of tokens, and that number is deducted from all of the hourly,
     * daily, and per project hourly quotas.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerHour
     */
    public function setTokensPerHour(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerHour)
    {
        $this->tokensPerHour = $tokensPerHour;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function getTokensPerHour()
    {
        return $this->tokensPerHour;
    }
    /**
     * Properties can use up to 25% of their tokens per project per hour. This
     * amounts to Analytics 360 Properties can use 12,500 tokens per project per
     * hour. An API request consumes a single number of tokens, and that number is
     * deducted from all of the hourly, daily, and per project hourly quotas.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerProjectPerHour
     */
    public function setTokensPerProjectPerHour(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerProjectPerHour)
    {
        $this->tokensPerProjectPerHour = $tokensPerProjectPerHour;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function getTokensPerProjectPerHour()
    {
        return $this->tokensPerProjectPerHour;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuota::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAccessQuota');
