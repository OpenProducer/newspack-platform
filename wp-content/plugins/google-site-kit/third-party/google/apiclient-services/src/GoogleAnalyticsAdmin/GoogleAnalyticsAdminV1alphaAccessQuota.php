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

class GoogleAnalyticsAdminV1alphaAccessQuota extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $concurrentRequestsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $concurrentRequestsDataType = '';
    protected $serverErrorsPerProjectPerHourType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $serverErrorsPerProjectPerHourDataType = '';
    protected $tokensPerDayType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $tokensPerDayDataType = '';
    protected $tokensPerHourType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $tokensPerHourDataType = '';
    protected $tokensPerProjectPerHourType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus::class;
    protected $tokensPerProjectPerHourDataType = '';
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function setConcurrentRequests(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $concurrentRequests)
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
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function setServerErrorsPerProjectPerHour(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $serverErrorsPerProjectPerHour)
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
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function setTokensPerDay(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerDay)
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
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function setTokensPerHour(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerHour)
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
     * @param GoogleAnalyticsAdminV1alphaAccessQuotaStatus
     */
    public function setTokensPerProjectPerHour(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuotaStatus $tokensPerProjectPerHour)
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuota::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaAccessQuota');
