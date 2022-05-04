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
namespace Google\Site_Kit_Dependencies\Google\Service\SearchConsole;

class UrlInspectionResult extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $ampResultType = \Google\Site_Kit_Dependencies\Google\Service\SearchConsole\AmpInspectionResult::class;
    protected $ampResultDataType = '';
    protected $indexStatusResultType = \Google\Site_Kit_Dependencies\Google\Service\SearchConsole\IndexStatusInspectionResult::class;
    protected $indexStatusResultDataType = '';
    /**
     * @var string
     */
    public $inspectionResultLink;
    protected $mobileUsabilityResultType = \Google\Site_Kit_Dependencies\Google\Service\SearchConsole\MobileUsabilityInspectionResult::class;
    protected $mobileUsabilityResultDataType = '';
    protected $richResultsResultType = \Google\Site_Kit_Dependencies\Google\Service\SearchConsole\RichResultsInspectionResult::class;
    protected $richResultsResultDataType = '';
    /**
     * @param AmpInspectionResult
     */
    public function setAmpResult(\Google\Site_Kit_Dependencies\Google\Service\SearchConsole\AmpInspectionResult $ampResult)
    {
        $this->ampResult = $ampResult;
    }
    /**
     * @return AmpInspectionResult
     */
    public function getAmpResult()
    {
        return $this->ampResult;
    }
    /**
     * @param IndexStatusInspectionResult
     */
    public function setIndexStatusResult(\Google\Site_Kit_Dependencies\Google\Service\SearchConsole\IndexStatusInspectionResult $indexStatusResult)
    {
        $this->indexStatusResult = $indexStatusResult;
    }
    /**
     * @return IndexStatusInspectionResult
     */
    public function getIndexStatusResult()
    {
        return $this->indexStatusResult;
    }
    /**
     * @param string
     */
    public function setInspectionResultLink($inspectionResultLink)
    {
        $this->inspectionResultLink = $inspectionResultLink;
    }
    /**
     * @return string
     */
    public function getInspectionResultLink()
    {
        return $this->inspectionResultLink;
    }
    /**
     * @param MobileUsabilityInspectionResult
     */
    public function setMobileUsabilityResult(\Google\Site_Kit_Dependencies\Google\Service\SearchConsole\MobileUsabilityInspectionResult $mobileUsabilityResult)
    {
        $this->mobileUsabilityResult = $mobileUsabilityResult;
    }
    /**
     * @return MobileUsabilityInspectionResult
     */
    public function getMobileUsabilityResult()
    {
        return $this->mobileUsabilityResult;
    }
    /**
     * @param RichResultsInspectionResult
     */
    public function setRichResultsResult(\Google\Site_Kit_Dependencies\Google\Service\SearchConsole\RichResultsInspectionResult $richResultsResult)
    {
        $this->richResultsResult = $richResultsResult;
    }
    /**
     * @return RichResultsInspectionResult
     */
    public function getRichResultsResult()
    {
        return $this->richResultsResult;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\SearchConsole\UrlInspectionResult::class, 'Google\\Site_Kit_Dependencies\\Google_Service_SearchConsole_UrlInspectionResult');
