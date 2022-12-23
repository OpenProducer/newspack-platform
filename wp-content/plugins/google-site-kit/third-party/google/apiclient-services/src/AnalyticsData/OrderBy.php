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

class OrderBy extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * @var bool
     */
    public $desc;
    protected $dimensionType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\DimensionOrderBy::class;
    protected $dimensionDataType = '';
    protected $metricType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\MetricOrderBy::class;
    protected $metricDataType = '';
    protected $pivotType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\PivotOrderBy::class;
    protected $pivotDataType = '';
    /**
     * @param bool
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }
    /**
     * @return bool
     */
    public function getDesc()
    {
        return $this->desc;
    }
    /**
     * @param DimensionOrderBy
     */
    public function setDimension(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\DimensionOrderBy $dimension)
    {
        $this->dimension = $dimension;
    }
    /**
     * @return DimensionOrderBy
     */
    public function getDimension()
    {
        return $this->dimension;
    }
    /**
     * @param MetricOrderBy
     */
    public function setMetric(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\MetricOrderBy $metric)
    {
        $this->metric = $metric;
    }
    /**
     * @return MetricOrderBy
     */
    public function getMetric()
    {
        return $this->metric;
    }
    /**
     * @param PivotOrderBy
     */
    public function setPivot(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\PivotOrderBy $pivot)
    {
        $this->pivot = $pivot;
    }
    /**
     * @return PivotOrderBy
     */
    public function getPivot()
    {
        return $this->pivot;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\OrderBy::class, 'Google\\Site_Kit_Dependencies\\Google_Service_AnalyticsData_OrderBy');
