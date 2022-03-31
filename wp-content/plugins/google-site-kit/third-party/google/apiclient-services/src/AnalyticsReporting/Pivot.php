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
namespace Google\Site_Kit_Dependencies\Google\Service\AnalyticsReporting;

class Pivot extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'metrics';
    protected $dimensionFilterClausesType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsReporting\DimensionFilterClause::class;
    protected $dimensionFilterClausesDataType = 'array';
    protected $dimensionsType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsReporting\Dimension::class;
    protected $dimensionsDataType = 'array';
    /**
     * @var int
     */
    public $maxGroupCount;
    protected $metricsType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsReporting\Metric::class;
    protected $metricsDataType = 'array';
    /**
     * @var int
     */
    public $startGroup;
    /**
     * @param DimensionFilterClause[]
     */
    public function setDimensionFilterClauses($dimensionFilterClauses)
    {
        $this->dimensionFilterClauses = $dimensionFilterClauses;
    }
    /**
     * @return DimensionFilterClause[]
     */
    public function getDimensionFilterClauses()
    {
        return $this->dimensionFilterClauses;
    }
    /**
     * @param Dimension[]
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
     * @param int
     */
    public function setMaxGroupCount($maxGroupCount)
    {
        $this->maxGroupCount = $maxGroupCount;
    }
    /**
     * @return int
     */
    public function getMaxGroupCount()
    {
        return $this->maxGroupCount;
    }
    /**
     * @param Metric[]
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
    /**
     * @param int
     */
    public function setStartGroup($startGroup)
    {
        $this->startGroup = $startGroup;
    }
    /**
     * @return int
     */
    public function getStartGroup()
    {
        return $this->startGroup;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsReporting\Pivot::class, 'Google\\Site_Kit_Dependencies\\Google_Service_AnalyticsReporting_Pivot');
