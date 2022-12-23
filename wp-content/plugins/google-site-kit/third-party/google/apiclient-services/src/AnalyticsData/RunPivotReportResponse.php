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
namespace Google\Site_Kit_Dependencies\Google\Service\AnalyticsData;

class RunPivotReportResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'rows';
    protected $aggregatesType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\Row::class;
    protected $aggregatesDataType = 'array';
    protected $dimensionHeadersType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\DimensionHeader::class;
    protected $dimensionHeadersDataType = 'array';
    /**
     * @var string
     */
    public $kind;
    protected $metadataType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\ResponseMetaData::class;
    protected $metadataDataType = '';
    protected $metricHeadersType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\MetricHeader::class;
    protected $metricHeadersDataType = 'array';
    protected $pivotHeadersType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\PivotHeader::class;
    protected $pivotHeadersDataType = 'array';
    protected $propertyQuotaType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\PropertyQuota::class;
    protected $propertyQuotaDataType = '';
    protected $rowsType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\Row::class;
    protected $rowsDataType = 'array';
    /**
     * @param Row[]
     */
    public function setAggregates($aggregates)
    {
        $this->aggregates = $aggregates;
    }
    /**
     * @return Row[]
     */
    public function getAggregates()
    {
        return $this->aggregates;
    }
    /**
     * @param DimensionHeader[]
     */
    public function setDimensionHeaders($dimensionHeaders)
    {
        $this->dimensionHeaders = $dimensionHeaders;
    }
    /**
     * @return DimensionHeader[]
     */
    public function getDimensionHeaders()
    {
        return $this->dimensionHeaders;
    }
    /**
     * @param string
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }
    /**
     * @param ResponseMetaData
     */
    public function setMetadata(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\ResponseMetaData $metadata)
    {
        $this->metadata = $metadata;
    }
    /**
     * @return ResponseMetaData
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
    /**
     * @param MetricHeader[]
     */
    public function setMetricHeaders($metricHeaders)
    {
        $this->metricHeaders = $metricHeaders;
    }
    /**
     * @return MetricHeader[]
     */
    public function getMetricHeaders()
    {
        return $this->metricHeaders;
    }
    /**
     * @param PivotHeader[]
     */
    public function setPivotHeaders($pivotHeaders)
    {
        $this->pivotHeaders = $pivotHeaders;
    }
    /**
     * @return PivotHeader[]
     */
    public function getPivotHeaders()
    {
        return $this->pivotHeaders;
    }
    /**
     * @param PropertyQuota
     */
    public function setPropertyQuota(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\PropertyQuota $propertyQuota)
    {
        $this->propertyQuota = $propertyQuota;
    }
    /**
     * @return PropertyQuota
     */
    public function getPropertyQuota()
    {
        return $this->propertyQuota;
    }
    /**
     * @param Row[]
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }
    /**
     * @return Row[]
     */
    public function getRows()
    {
        return $this->rows;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\RunPivotReportResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_AnalyticsData_RunPivotReportResponse');
