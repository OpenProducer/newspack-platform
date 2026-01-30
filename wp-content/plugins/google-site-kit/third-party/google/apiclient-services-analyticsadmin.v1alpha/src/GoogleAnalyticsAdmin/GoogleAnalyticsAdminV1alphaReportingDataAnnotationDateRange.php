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

class GoogleAnalyticsAdminV1alphaReportingDataAnnotationDateRange extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $endDateType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleTypeDate::class;
    protected $endDateDataType = '';
    protected $startDateType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleTypeDate::class;
    protected $startDateDataType = '';
    /**
     * Required. The end date for this range. Must be a valid date with year,
     * month, and day set. This date must be greater than or equal to the start
     * date.
     *
     * @param GoogleTypeDate $endDate
     */
    public function setEndDate(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleTypeDate $endDate)
    {
        $this->endDate = $endDate;
    }
    /**
     * @return GoogleTypeDate
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
    /**
     * Required. The start date for this range. Must be a valid date with year,
     * month, and day set. The date may be in the past, present, or future.
     *
     * @param GoogleTypeDate $startDate
     */
    public function setStartDate(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleTypeDate $startDate)
    {
        $this->startDate = $startDate;
    }
    /**
     * @return GoogleTypeDate
     */
    public function getStartDate()
    {
        return $this->startDate;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReportingDataAnnotationDateRange::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaReportingDataAnnotationDateRange');
