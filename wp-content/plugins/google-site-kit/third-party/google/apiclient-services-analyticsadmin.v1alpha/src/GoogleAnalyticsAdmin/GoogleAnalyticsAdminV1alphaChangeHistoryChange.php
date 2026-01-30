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

class GoogleAnalyticsAdminV1alphaChangeHistoryChange extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Action type unknown or not specified.
     */
    public const ACTION_ACTION_TYPE_UNSPECIFIED = 'ACTION_TYPE_UNSPECIFIED';
    /**
     * Resource was created in this change.
     */
    public const ACTION_CREATED = 'CREATED';
    /**
     * Resource was updated in this change.
     */
    public const ACTION_UPDATED = 'UPDATED';
    /**
     * Resource was deleted in this change.
     */
    public const ACTION_DELETED = 'DELETED';
    /**
     * The type of action that changed this resource.
     *
     * @var string
     */
    public $action;
    /**
     * Resource name of the resource whose changes are described by this entry.
     *
     * @var string
     */
    public $resource;
    protected $resourceAfterChangeType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource::class;
    protected $resourceAfterChangeDataType = '';
    protected $resourceBeforeChangeType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource::class;
    protected $resourceBeforeChangeDataType = '';
    /**
     * The type of action that changed this resource.
     *
     * Accepted values: ACTION_TYPE_UNSPECIFIED, CREATED, UPDATED, DELETED
     *
     * @param self::ACTION_* $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }
    /**
     * @return self::ACTION_*
     */
    public function getAction()
    {
        return $this->action;
    }
    /**
     * Resource name of the resource whose changes are described by this entry.
     *
     * @param string $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }
    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }
    /**
     * Resource contents from after the change was made. If this resource was
     * deleted in this change, this field will be missing.
     *
     * @param GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource $resourceAfterChange
     */
    public function setResourceAfterChange(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource $resourceAfterChange)
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
     * Resource contents from before the change was made. If this resource was
     * created in this change, this field will be missing.
     *
     * @param GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource $resourceBeforeChange
     */
    public function setResourceBeforeChange(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource $resourceBeforeChange)
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChangeHistoryChange::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaChangeHistoryChange');
