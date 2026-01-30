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

class CheckCompatibilityRequest extends \Google\Site_Kit_Dependencies\Google\Collection
{
    /**
     * Unspecified compatibility.
     */
    public const COMPATIBILITY_FILTER_COMPATIBILITY_UNSPECIFIED = 'COMPATIBILITY_UNSPECIFIED';
    /**
     * The dimension or metric is compatible. This dimension or metric can be
     * successfully added to a report.
     */
    public const COMPATIBILITY_FILTER_COMPATIBLE = 'COMPATIBLE';
    /**
     * The dimension or metric is incompatible. This dimension or metric cannot be
     * successfully added to a report.
     */
    public const COMPATIBILITY_FILTER_INCOMPATIBLE = 'INCOMPATIBLE';
    protected $collection_key = 'metrics';
    /**
     * Filters the dimensions and metrics in the response to just this
     * compatibility. Commonly used as `”compatibilityFilter”: “COMPATIBLE”` to
     * only return compatible dimensions & metrics.
     *
     * @var string
     */
    public $compatibilityFilter;
    protected $dimensionFilterType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\FilterExpression::class;
    protected $dimensionFilterDataType = '';
    protected $dimensionsType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\Dimension::class;
    protected $dimensionsDataType = 'array';
    protected $metricFilterType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\FilterExpression::class;
    protected $metricFilterDataType = '';
    protected $metricsType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\Metric::class;
    protected $metricsDataType = 'array';
    /**
     * Filters the dimensions and metrics in the response to just this
     * compatibility. Commonly used as `”compatibilityFilter”: “COMPATIBLE”` to
     * only return compatible dimensions & metrics.
     *
     * Accepted values: COMPATIBILITY_UNSPECIFIED, COMPATIBLE, INCOMPATIBLE
     *
     * @param self::COMPATIBILITY_FILTER_* $compatibilityFilter
     */
    public function setCompatibilityFilter($compatibilityFilter)
    {
        $this->compatibilityFilter = $compatibilityFilter;
    }
    /**
     * @return self::COMPATIBILITY_FILTER_*
     */
    public function getCompatibilityFilter()
    {
        return $this->compatibilityFilter;
    }
    /**
     * The filter clause of dimensions. `dimensionFilter` should be the same value
     * as in your `runReport` request.
     *
     * @param FilterExpression $dimensionFilter
     */
    public function setDimensionFilter(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\FilterExpression $dimensionFilter)
    {
        $this->dimensionFilter = $dimensionFilter;
    }
    /**
     * @return FilterExpression
     */
    public function getDimensionFilter()
    {
        return $this->dimensionFilter;
    }
    /**
     * The dimensions in this report. `dimensions` should be the same value as in
     * your `runReport` request.
     *
     * @param Dimension[] $dimensions
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;
    }
    /**
     * @return Dimension[]
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }
    /**
     * The filter clause of metrics. `metricFilter` should be the same value as in
     * your `runReport` request
     *
     * @param FilterExpression $metricFilter
     */
    public function setMetricFilter(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\FilterExpression $metricFilter)
    {
        $this->metricFilter = $metricFilter;
    }
    /**
     * @return FilterExpression
     */
    public function getMetricFilter()
    {
        return $this->metricFilter;
    }
    /**
     * The metrics in this report. `metrics` should be the same value as in your
     * `runReport` request.
     *
     * @param Metric[] $metrics
     */
    public function setMetrics($metrics)
    {
        $this->metrics = $metrics;
    }
    /**
     * @return Metric[]
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\CheckCompatibilityRequest::class, 'Google\\Site_Kit_Dependencies\\Google_Service_AnalyticsData_CheckCompatibilityRequest');
