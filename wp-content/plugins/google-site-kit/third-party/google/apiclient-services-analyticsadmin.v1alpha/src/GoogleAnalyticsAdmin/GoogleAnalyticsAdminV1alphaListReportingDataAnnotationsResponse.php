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

class GoogleAnalyticsAdminV1alphaListReportingDataAnnotationsResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'reportingDataAnnotations';
    /**
     * A token, which can be sent as `page_token` to retrieve the next page. If
     * this field is omitted, there are no subsequent pages.
     *
     * @var string
     */
    public $nextPageToken;
    protected $reportingDataAnnotationsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReportingDataAnnotation::class;
    protected $reportingDataAnnotationsDataType = 'array';
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
    /**
     * List of Reporting Data Annotations.
     *
     * @param GoogleAnalyticsAdminV1alphaReportingDataAnnotation[] $reportingDataAnnotations
     */
    public function setReportingDataAnnotations($reportingDataAnnotations)
    {
        $this->reportingDataAnnotations = $reportingDataAnnotations;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaReportingDataAnnotation[]
     */
    public function getReportingDataAnnotations()
    {
        return $this->reportingDataAnnotations;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaListReportingDataAnnotationsResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaListReportingDataAnnotationsResponse');
