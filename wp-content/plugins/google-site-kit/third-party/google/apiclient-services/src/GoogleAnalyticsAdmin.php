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
namespace Google\Site_Kit_Dependencies\Google\Service;

use Google\Site_Kit_Dependencies\Google\Client;
/**
 * Service definition for GoogleAnalyticsAdmin (v1beta).
 *
 * <p>
 * Manage properties in Google Analytics. Warning: Creating multiple Customer
 * Applications, Accounts, or Projects to simulate or act as a single Customer
 * Application, Account, or Project (respectively) or to circumvent Service-
 * specific usage limits or quotas is a direct violation of Google Cloud
 * Platform Terms of Service as well as Google APIs Terms of Service. These
 * actions can result in immediate termination of your GCP project(s) without
 * any warning.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="http://code.google.com/apis/analytics/docs/mgmt/home.html" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class GoogleAnalyticsAdmin extends \Google\Site_Kit_Dependencies\Google\Service
{
    /** Edit Google Analytics management entities. */
    const ANALYTICS_EDIT = "https://www.googleapis.com/auth/analytics.edit";
    /** See and download your Google Analytics data. */
    const ANALYTICS_READONLY = "https://www.googleapis.com/auth/analytics.readonly";
    public $accountSummaries;
    public $accounts;
    public $properties;
    public $properties_conversionEvents;
    public $properties_customDimensions;
    public $properties_customMetrics;
    public $properties_dataStreams;
    public $properties_dataStreams_measurementProtocolSecrets;
    public $properties_firebaseLinks;
    public $properties_googleAdsLinks;
    public $properties_keyEvents;
    public $rootUrlTemplate;
    /**
     * Constructs the internal representation of the GoogleAnalyticsAdmin service.
     *
     * @param Client|array $clientOrConfig The client used to deliver requests, or a
     *                                     config array to pass to a new Client instance.
     * @param string $rootUrl The root URL used for requests to the service.
     */
    public function __construct($clientOrConfig = [], $rootUrl = null)
    {
        parent::__construct($clientOrConfig);
        $this->rootUrl = $rootUrl ?: 'https://analyticsadmin.googleapis.com/';
        $this->rootUrlTemplate = $rootUrl ?: 'https://analyticsadmin.UNIVERSE_DOMAIN/';
        $this->servicePath = '';
        $this->batchPath = 'batch';
        $this->version = 'v1beta';
        $this->serviceName = 'analyticsadmin';
        $this->accountSummaries = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\AccountSummaries($this, $this->serviceName, 'accountSummaries', ['methods' => ['list' => ['path' => 'v1beta/accountSummaries', 'httpMethod' => 'GET', 'parameters' => ['pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->accounts = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\Accounts($this, $this->serviceName, 'accounts', ['methods' => ['delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'getDataSharingSettings' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/accounts', 'httpMethod' => 'GET', 'parameters' => ['pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string'], 'showDeleted' => ['location' => 'query', 'type' => 'boolean']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]], 'provisionAccountTicket' => ['path' => 'v1beta/accounts:provisionAccountTicket', 'httpMethod' => 'POST', 'parameters' => []], 'runAccessReport' => ['path' => 'v1beta/{+entity}:runAccessReport', 'httpMethod' => 'POST', 'parameters' => ['entity' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'searchChangeHistoryEvents' => ['path' => 'v1beta/{+account}:searchChangeHistoryEvents', 'httpMethod' => 'POST', 'parameters' => ['account' => ['location' => 'path', 'type' => 'string', 'required' => \true]]]]]);
        $this->properties = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\Properties($this, $this->serviceName, 'properties', ['methods' => ['acknowledgeUserDataCollection' => ['path' => 'v1beta/{+property}:acknowledgeUserDataCollection', 'httpMethod' => 'POST', 'parameters' => ['property' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'create' => ['path' => 'v1beta/properties', 'httpMethod' => 'POST', 'parameters' => []], 'delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'getDataRetentionSettings' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/properties', 'httpMethod' => 'GET', 'parameters' => ['filter' => ['location' => 'query', 'type' => 'string'], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string'], 'showDeleted' => ['location' => 'query', 'type' => 'boolean']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]], 'runAccessReport' => ['path' => 'v1beta/{+entity}:runAccessReport', 'httpMethod' => 'POST', 'parameters' => ['entity' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'updateDataRetentionSettings' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_conversionEvents = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesConversionEvents($this, $this->serviceName, 'conversionEvents', ['methods' => ['create' => ['path' => 'v1beta/{+parent}/conversionEvents', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/conversionEvents', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_customDimensions = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesCustomDimensions($this, $this->serviceName, 'customDimensions', ['methods' => ['archive' => ['path' => 'v1beta/{+name}:archive', 'httpMethod' => 'POST', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'create' => ['path' => 'v1beta/{+parent}/customDimensions', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/customDimensions', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_customMetrics = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesCustomMetrics($this, $this->serviceName, 'customMetrics', ['methods' => ['archive' => ['path' => 'v1beta/{+name}:archive', 'httpMethod' => 'POST', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'create' => ['path' => 'v1beta/{+parent}/customMetrics', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/customMetrics', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_dataStreams = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesDataStreams($this, $this->serviceName, 'dataStreams', ['methods' => ['create' => ['path' => 'v1beta/{+parent}/dataStreams', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/dataStreams', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_dataStreams_measurementProtocolSecrets = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesDataStreamsMeasurementProtocolSecrets($this, $this->serviceName, 'measurementProtocolSecrets', ['methods' => ['create' => ['path' => 'v1beta/{+parent}/measurementProtocolSecrets', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/measurementProtocolSecrets', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_firebaseLinks = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesFirebaseLinks($this, $this->serviceName, 'firebaseLinks', ['methods' => ['create' => ['path' => 'v1beta/{+parent}/firebaseLinks', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/firebaseLinks', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_googleAdsLinks = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesGoogleAdsLinks($this, $this->serviceName, 'googleAdsLinks', ['methods' => ['create' => ['path' => 'v1beta/{+parent}/googleAdsLinks', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/googleAdsLinks', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
        $this->properties_keyEvents = new \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\Resource\PropertiesKeyEvents($this, $this->serviceName, 'keyEvents', ['methods' => ['create' => ['path' => 'v1beta/{+parent}/keyEvents', 'httpMethod' => 'POST', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'delete' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'DELETE', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'get' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'GET', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true]]], 'list' => ['path' => 'v1beta/{+parent}/keyEvents', 'httpMethod' => 'GET', 'parameters' => ['parent' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'pageSize' => ['location' => 'query', 'type' => 'integer'], 'pageToken' => ['location' => 'query', 'type' => 'string']]], 'patch' => ['path' => 'v1beta/{+name}', 'httpMethod' => 'PATCH', 'parameters' => ['name' => ['location' => 'path', 'type' => 'string', 'required' => \true], 'updateMask' => ['location' => 'query', 'type' => 'string']]]]]);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin');
