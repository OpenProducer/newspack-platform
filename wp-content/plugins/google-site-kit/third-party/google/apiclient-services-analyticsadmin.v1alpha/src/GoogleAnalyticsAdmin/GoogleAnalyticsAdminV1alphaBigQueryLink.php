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

class GoogleAnalyticsAdminV1alphaBigQueryLink extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'exportStreams';
    /**
     * Output only. Time when the link was created.
     *
     * @var string
     */
    public $createTime;
    /**
     * If set true, enables daily data export to the linked Google Cloud project.
     *
     * @var bool
     */
    public $dailyExportEnabled;
    /**
     * Required. Immutable. The geographic location where the created BigQuery
     * dataset should reside. See https://cloud.google.com/bigquery/docs/locations
     * for supported locations.
     *
     * @var string
     */
    public $datasetLocation;
    /**
     * The list of event names that will be excluded from exports.
     *
     * @var string[]
     */
    public $excludedEvents;
    /**
     * The list of streams under the parent property for which data will be
     * exported. Format: properties/{property_id}/dataStreams/{stream_id} Example:
     * ['properties/1000/dataStreams/2000']
     *
     * @var string[]
     */
    public $exportStreams;
    /**
     * If set true, enables fresh daily export to the linked Google Cloud project.
     *
     * @var bool
     */
    public $freshDailyExportEnabled;
    /**
     * If set true, exported data will include advertising identifiers for mobile
     * app streams.
     *
     * @var bool
     */
    public $includeAdvertisingId;
    /**
     * Output only. Resource name of this BigQuery link. Format:
     * 'properties/{property_id}/bigQueryLinks/{bigquery_link_id}' Format:
     * 'properties/1234/bigQueryLinks/abc567'
     *
     * @var string
     */
    public $name;
    /**
     * Immutable. The linked Google Cloud project. When creating a BigQueryLink,
     * you may provide this resource name using either a project number or project
     * ID. Once this resource has been created, the returned project will always
     * have a project that contains a project number. Format: 'projects/{project
     * number}' Example: 'projects/1234'
     *
     * @var string
     */
    public $project;
    /**
     * If set true, enables streaming export to the linked Google Cloud project.
     *
     * @var bool
     */
    public $streamingExportEnabled;
    /**
     * Output only. Time when the link was created.
     *
     * @param string $createTime
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }
    /**
     * @return string
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
    /**
     * If set true, enables daily data export to the linked Google Cloud project.
     *
     * @param bool $dailyExportEnabled
     */
    public function setDailyExportEnabled($dailyExportEnabled)
    {
        $this->dailyExportEnabled = $dailyExportEnabled;
    }
    /**
     * @return bool
     */
    public function getDailyExportEnabled()
    {
        return $this->dailyExportEnabled;
    }
    /**
     * Required. Immutable. The geographic location where the created BigQuery
     * dataset should reside. See https://cloud.google.com/bigquery/docs/locations
     * for supported locations.
     *
     * @param string $datasetLocation
     */
    public function setDatasetLocation($datasetLocation)
    {
        $this->datasetLocation = $datasetLocation;
    }
    /**
     * @return string
     */
    public function getDatasetLocation()
    {
        return $this->datasetLocation;
    }
    /**
     * The list of event names that will be excluded from exports.
     *
     * @param string[] $excludedEvents
     */
    public function setExcludedEvents($excludedEvents)
    {
        $this->excludedEvents = $excludedEvents;
    }
    /**
     * @return string[]
     */
    public function getExcludedEvents()
    {
        return $this->excludedEvents;
    }
    /**
     * The list of streams under the parent property for which data will be
     * exported. Format: properties/{property_id}/dataStreams/{stream_id} Example:
     * ['properties/1000/dataStreams/2000']
     *
     * @param string[] $exportStreams
     */
    public function setExportStreams($exportStreams)
    {
        $this->exportStreams = $exportStreams;
    }
    /**
     * @return string[]
     */
    public function getExportStreams()
    {
        return $this->exportStreams;
    }
    /**
     * If set true, enables fresh daily export to the linked Google Cloud project.
     *
     * @param bool $freshDailyExportEnabled
     */
    public function setFreshDailyExportEnabled($freshDailyExportEnabled)
    {
        $this->freshDailyExportEnabled = $freshDailyExportEnabled;
    }
    /**
     * @return bool
     */
    public function getFreshDailyExportEnabled()
    {
        return $this->freshDailyExportEnabled;
    }
    /**
     * If set true, exported data will include advertising identifiers for mobile
     * app streams.
     *
     * @param bool $includeAdvertisingId
     */
    public function setIncludeAdvertisingId($includeAdvertisingId)
    {
        $this->includeAdvertisingId = $includeAdvertisingId;
    }
    /**
     * @return bool
     */
    public function getIncludeAdvertisingId()
    {
        return $this->includeAdvertisingId;
    }
    /**
     * Output only. Resource name of this BigQuery link. Format:
     * 'properties/{property_id}/bigQueryLinks/{bigquery_link_id}' Format:
     * 'properties/1234/bigQueryLinks/abc567'
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
    /**
     * Immutable. The linked Google Cloud project. When creating a BigQueryLink,
     * you may provide this resource name using either a project number or project
     * ID. Once this resource has been created, the returned project will always
     * have a project that contains a project number. Format: 'projects/{project
     * number}' Example: 'projects/1234'
     *
     * @param string $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }
    /**
     * @return string
     */
    public function getProject()
    {
        return $this->project;
    }
    /**
     * If set true, enables streaming export to the linked Google Cloud project.
     *
     * @param bool $streamingExportEnabled
     */
    public function setStreamingExportEnabled($streamingExportEnabled)
    {
        $this->streamingExportEnabled = $streamingExportEnabled;
    }
    /**
     * @return bool
     */
    public function getStreamingExportEnabled()
    {
        return $this->streamingExportEnabled;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaBigQueryLink::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaBigQueryLink');
