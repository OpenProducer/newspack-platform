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

class GoogleAnalyticsAdminV1alphaRunAccessReportResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'rows';
    protected $dimensionHeadersType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessDimensionHeader::class;
    protected $dimensionHeadersDataType = 'array';
    protected $metricHeadersType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessMetricHeader::class;
    protected $metricHeadersDataType = 'array';
    protected $quotaType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuota::class;
    protected $quotaDataType = '';
    /**
     * The total number of rows in the query result. `rowCount` is independent of
     * the number of rows returned in the response, the `limit` request parameter,
     * and the `offset` request parameter. For example if a query returns 175 rows
     * and includes `limit` of 50 in the API request, the response will contain
     * `rowCount` of 175 but only 50 rows. To learn more about this pagination
     * parameter, see [Pagination](https://developers.google.com/analytics/devguid
     * es/reporting/data/v1/basics#pagination).
     *
     * @var int
     */
    public $rowCount;
    protected $rowsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessRow::class;
    protected $rowsDataType = 'array';
    /**
     * The header for a column in the report that corresponds to a specific
     * dimension. The number of DimensionHeaders and ordering of DimensionHeaders
     * matches the dimensions present in rows.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessDimensionHeader[] $dimensionHeaders
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
     * The header for a column in the report that corresponds to a specific
     * metric. The number of MetricHeaders and ordering of MetricHeaders matches
     * the metrics present in rows.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessMetricHeader[] $metricHeaders
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
     * The quota state for this Analytics property including this request. This
     * field doesn't work with account-level requests.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessQuota $quota
     */
    public function setQuota(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccessQuota $quota)
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
     * The total number of rows in the query result. `rowCount` is independent of
     * the number of rows returned in the response, the `limit` request parameter,
     * and the `offset` request parameter. For example if a query returns 175 rows
     * and includes `limit` of 50 in the API request, the response will contain
     * `rowCount` of 175 but only 50 rows. To learn more about this pagination
     * parameter, see [Pagination](https://developers.google.com/analytics/devguid
     * es/reporting/data/v1/basics#pagination).
     *
     * @param int $rowCount
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
     * Rows of dimension value combinations and metric values in the report.
     *
     * @param GoogleAnalyticsAdminV1alphaAccessRow[] $rows
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaRunAccessReportResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaRunAccessReportResponse');
