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

class GoogleAnalyticsAdminV1alphaRollupPropertySourceLink extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Output only. Resource name of this RollupPropertySourceLink. Format: 'prope
     * rties/{property_id}/rollupPropertySourceLinks/{rollup_property_source_link}
     * ' Format: 'properties/123/rollupPropertySourceLinks/456'
     *
     * @var string
     */
    public $name;
    /**
     * Immutable. Resource name of the source property. Format:
     * properties/{property_id} Example: "properties/789"
     *
     * @var string
     */
    public $sourceProperty;
    /**
     * Output only. Resource name of this RollupPropertySourceLink. Format: 'prope
     * rties/{property_id}/rollupPropertySourceLinks/{rollup_property_source_link}
     * ' Format: 'properties/123/rollupPropertySourceLinks/456'
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
     * Immutable. Resource name of the source property. Format:
     * properties/{property_id} Example: "properties/789"
     *
     * @param string $sourceProperty
     */
    public function setSourceProperty($sourceProperty)
    {
        $this->sourceProperty = $sourceProperty;
    }
    /**
     * @return string
     */
    public function getSourceProperty()
    {
        return $this->sourceProperty;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaRollupPropertySourceLink::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaRollupPropertySourceLink');
