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
namespace Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights;

class Categories extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $internal_gapi_mappings = ["bestPractices" => "best-practices"];
    protected $accessibilityType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5::class;
    protected $accessibilityDataType = '';
    protected $bestPracticesType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5::class;
    protected $bestPracticesDataType = '';
    protected $performanceType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5::class;
    protected $performanceDataType = '';
    protected $pwaType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5::class;
    protected $pwaDataType = '';
    protected $seoType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5::class;
    protected $seoDataType = '';
    /**
     * @param LighthouseCategoryV5
     */
    public function setAccessibility(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5 $accessibility)
    {
        $this->accessibility = $accessibility;
    }
    /**
     * @return LighthouseCategoryV5
     */
    public function getAccessibility()
    {
        return $this->accessibility;
    }
    /**
     * @param LighthouseCategoryV5
     */
    public function setBestPractices(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5 $bestPractices)
    {
        $this->bestPractices = $bestPractices;
    }
    /**
     * @return LighthouseCategoryV5
     */
    public function getBestPractices()
    {
        return $this->bestPractices;
    }
    /**
     * @param LighthouseCategoryV5
     */
    public function setPerformance(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5 $performance)
    {
        $this->performance = $performance;
    }
    /**
     * @return LighthouseCategoryV5
     */
    public function getPerformance()
    {
        return $this->performance;
    }
    /**
     * @param LighthouseCategoryV5
     */
    public function setPwa(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5 $pwa)
    {
        $this->pwa = $pwa;
    }
    /**
     * @return LighthouseCategoryV5
     */
    public function getPwa()
    {
        return $this->pwa;
    }
    /**
     * @param LighthouseCategoryV5
     */
    public function setSeo(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseCategoryV5 $seo)
    {
        $this->seo = $seo;
    }
    /**
     * @return LighthouseCategoryV5
     */
    public function getSeo()
    {
        return $this->seo;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\Categories::class, 'Google\\Site_Kit_Dependencies\\Google_Service_PagespeedInsights_Categories');
