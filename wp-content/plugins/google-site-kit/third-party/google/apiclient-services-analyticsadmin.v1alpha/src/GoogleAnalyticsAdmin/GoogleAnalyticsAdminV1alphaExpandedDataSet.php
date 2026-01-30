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

class GoogleAnalyticsAdminV1alphaExpandedDataSet extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'metricNames';
    /**
     * Output only. Time when expanded data set began (or will begin) collecing
     * data.
     *
     * @var string
     */
    public $dataCollectionStartTime;
    /**
     * Optional. The description of the ExpandedDataSet. Max 50 chars.
     *
     * @var string
     */
    public $description;
    protected $dimensionFilterExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression::class;
    protected $dimensionFilterExpressionDataType = '';
    /**
     * Immutable. The list of dimensions included in the ExpandedDataSet. See the
     * [API Dimensions](https://developers.google.com/analytics/devguides/reportin
     * g/data/v1/api-schema#dimensions) for the list of dimension names.
     *
     * @var string[]
     */
    public $dimensionNames;
    /**
     * Required. The display name of the ExpandedDataSet. Max 200 chars.
     *
     * @var string
     */
    public $displayName;
    /**
     * Immutable. The list of metrics included in the ExpandedDataSet. See the
     * [API Metrics](https://developers.google.com/analytics/devguides/reporting/d
     * ata/v1/api-schema#metrics) for the list of dimension names.
     *
     * @var string[]
     */
    public $metricNames;
    /**
     * Output only. The resource name for this ExpandedDataSet resource. Format:
     * properties/{property_id}/expandedDataSets/{expanded_data_set}
     *
     * @var string
     */
    public $name;
    /**
     * Output only. Time when expanded data set began (or will begin) collecing
     * data.
     *
     * @param string $dataCollectionStartTime
     */
    public function setDataCollectionStartTime($dataCollectionStartTime)
    {
        $this->dataCollectionStartTime = $dataCollectionStartTime;
    }
    /**
     * @return string
     */
    public function getDataCollectionStartTime()
    {
        return $this->dataCollectionStartTime;
    }
    /**
     * Optional. The description of the ExpandedDataSet. Max 50 chars.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Immutable. A logical expression of ExpandedDataSet filters applied to
     * dimension included in the ExpandedDataSet. This filter is used to reduce
     * the number of rows and thus the chance of encountering `other` row.
     *
     * @param GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression $dimensionFilterExpression
     */
    public function setDimensionFilterExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression $dimensionFilterExpression)
    {
        $this->dimensionFilterExpression = $dimensionFilterExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaExpandedDataSetFilterExpression
     */
    public function getDimensionFilterExpression()
    {
        return $this->dimensionFilterExpression;
    }
    /**
     * Immutable. The list of dimensions included in the ExpandedDataSet. See the
     * [API Dimensions](https://developers.google.com/analytics/devguides/reportin
     * g/data/v1/api-schema#dimensions) for the list of dimension names.
     *
     * @param string[] $dimensionNames
     */
    public function setDimensionNames($dimensionNames)
    {
        $this->dimensionNames = $dimensionNames;
    }
    /**
     * @return string[]
     */
    public function getDimensionNames()
    {
        return $this->dimensionNames;
    }
    /**
     * Required. The display name of the ExpandedDataSet. Max 200 chars.
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * Immutable. The list of metrics included in the ExpandedDataSet. See the
     * [API Metrics](https://developers.google.com/analytics/devguides/reporting/d
     * ata/v1/api-schema#metrics) for the list of dimension names.
     *
     * @param string[] $metricNames
     */
    public function setMetricNames($metricNames)
    {
        $this->metricNames = $metricNames;
    }
    /**
     * @return string[]
     */
    public function getMetricNames()
    {
        return $this->metricNames;
    }
    /**
     * Output only. The resource name for this ExpandedDataSet resource. Format:
     * properties/{property_id}/expandedDataSets/{expanded_data_set}
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSet::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaExpandedDataSet');
