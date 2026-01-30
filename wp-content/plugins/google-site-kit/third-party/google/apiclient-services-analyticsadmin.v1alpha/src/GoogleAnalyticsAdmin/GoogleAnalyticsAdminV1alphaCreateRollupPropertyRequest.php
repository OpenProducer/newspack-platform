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

class GoogleAnalyticsAdminV1alphaCreateRollupPropertyRequest extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'sourceProperties';
    protected $rollupPropertyType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty::class;
    protected $rollupPropertyDataType = '';
    /**
     * Optional. The resource names of properties that will be sources to the
     * created roll-up property.
     *
     * @var string[]
     */
    public $sourceProperties;
    /**
     * Required. The roll-up property to create.
     *
     * @param GoogleAnalyticsAdminV1alphaProperty $rollupProperty
     */
    public function setRollupProperty(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty $rollupProperty)
    {
        $this->rollupProperty = $rollupProperty;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaProperty
     */
    public function getRollupProperty()
    {
        return $this->rollupProperty;
    }
    /**
     * Optional. The resource names of properties that will be sources to the
     * created roll-up property.
     *
     * @param string[] $sourceProperties
     */
    public function setSourceProperties($sourceProperties)
    {
        $this->sourceProperties = $sourceProperties;
    }
    /**
     * @return string[]
     */
    public function getSourceProperties()
    {
        return $this->sourceProperties;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCreateRollupPropertyRequest::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaCreateRollupPropertyRequest');
