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

class GoogleAnalyticsAdminV1alphaRunAccessReportResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'rows';
    protected $dimensionHeadersType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessDimensionHeader::class;
    protected $dimensionHeadersDataType = 'array';
    protected $metricHeadersType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessMetricHeader::class;
    protected $metricHeadersDataType = 'array';
    protected $quotaType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuota::class;
    protected $quotaDataType = '';
    /**
     * @var int
     */
    public $rowCount;
    protected $rowsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessRow::class;
    protected $rowsDataType = 'array';
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessDimensionHeader[]
     */
    public function setDimensionHeaders($dimensionHeaders)
    {
        $this->dimensionHeaders = $dimensionHeaders;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessDimensionHeader[]
     */
    public function getDimensionHeaders()
    {
        return $this->dimensionHeaders;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessMetricHeader[]
     */
    public function setMetricHeaders($metricHeaders)
    {
        $this->metricHeaders = $metricHeaders;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessMetricHeader[]
     */
    public function getMetricHeaders()
    {
        return $this->metricHeaders;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessQuota
     */
    public function setQuota(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessQuota $quota)
    {
        $this->quota = $quota;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessQuota
     */
    public function getQuota()
    {
        return $this->quota;
    }
    /**
     * @param int
     */
    public function setRowCount($rowCount)
    {
        $this->rowCount = $rowCount;
    }
    /**
     * @return int
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessRow[]
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessRow[]
     */
    public function getRows()
    {
        return $this->rows;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaRunAccessReportResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaRunAccessReportResponse');
