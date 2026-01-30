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

class GoogleAnalyticsAdminV1alphaProvisionSubpropertyResponse extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $subpropertyType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty::class;
    protected $subpropertyDataType = '';
    protected $subpropertyEventFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilter::class;
    protected $subpropertyEventFilterDataType = '';
    /**
     * The created subproperty.
     *
     * @param GoogleAnalyticsAdminV1alphaProperty $subproperty
     */
    public function setSubproperty(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty $subproperty)
    {
        $this->subproperty = $subproperty;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaProperty
     */
    public function getSubproperty()
    {
        return $this->subproperty;
    }
    /**
     * The created subproperty event filter.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilter $subpropertyEventFilter
     */
    public function setSubpropertyEventFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilter $subpropertyEventFilter)
    {
        $this->subpropertyEventFilter = $subpropertyEventFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilter
     */
    public function getSubpropertyEventFilter()
    {
        return $this->subpropertyEventFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProvisionSubpropertyResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaProvisionSubpropertyResponse');
