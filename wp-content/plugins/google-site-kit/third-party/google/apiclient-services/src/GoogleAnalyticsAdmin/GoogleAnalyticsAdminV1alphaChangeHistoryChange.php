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

class GoogleAnalyticsAdminV1alphaChangeHistoryChange extends \Google\Site_Kit_Dependencies\Google\Model
{
    public $action;
    public $resource;
    protected $resourceAfterChangeType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource::class;
    protected $resourceAfterChangeDataType = '';
    protected $resourceBeforeChangeType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource::class;
    protected $resourceBeforeChangeDataType = '';
    public function setAction($action)
    {
        $this->action = $action;
    }
    public function getAction()
    {
        return $this->action;
    }
    public function setResource($resource)
    {
        $this->resource = $resource;
    }
    public function getResource()
    {
        return $this->resource;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource
     */
    public function setResourceAfterChange(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource $resourceAfterChange)
    {
        $this->resourceAfterChange = $resourceAfterChange;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource
     */
    public function getResourceAfterChange()
    {
        return $this->resourceAfterChange;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource
     */
    public function setResourceBeforeChange(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource $resourceBeforeChange)
    {
        $this->resourceBeforeChange = $resourceBeforeChange;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource
     */
    public function getResourceBeforeChange()
    {
        return $this->resourceBeforeChange;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaChangeHistoryChange::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaChangeHistoryChange');
