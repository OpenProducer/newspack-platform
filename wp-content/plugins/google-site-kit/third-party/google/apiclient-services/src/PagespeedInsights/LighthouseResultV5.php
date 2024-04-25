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

class LighthouseResultV5 extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'stackPacks';
    protected $auditsType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseAuditResultV5::class;
    protected $auditsDataType = 'map';
    protected $categoriesType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\Categories::class;
    protected $categoriesDataType = '';
    protected $categoryGroupsType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\CategoryGroupV5::class;
    protected $categoryGroupsDataType = 'map';
    protected $configSettingsType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\ConfigSettings::class;
    protected $configSettingsDataType = '';
    protected $entitiesType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LhrEntity::class;
    protected $entitiesDataType = 'array';
    protected $environmentType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\Environment::class;
    protected $environmentDataType = '';
    /**
     * @var string
     */
    public $fetchTime;
    /**
     * @var string
     */
    public $finalDisplayedUrl;
    /**
     * @var string
     */
    public $finalUrl;
    /**
     * @var array
     */
    public $fullPageScreenshot;
    protected $i18nType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\I18n::class;
    protected $i18nDataType = '';
    /**
     * @var string
     */
    public $lighthouseVersion;
    /**
     * @var string
     */
    public $mainDocumentUrl;
    /**
     * @var string
     */
    public $requestedUrl;
    /**
     * @var array[]
     */
    public $runWarnings;
    protected $runtimeErrorType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\RuntimeError::class;
    protected $runtimeErrorDataType = '';
    protected $stackPacksType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\StackPack::class;
    protected $stackPacksDataType = 'array';
    protected $timingType = \Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\Timing::class;
    protected $timingDataType = '';
    /**
     * @var string
     */
    public $userAgent;
    /**
     * @param LighthouseAuditResultV5[]
     */
    public function setAudits($audits)
    {
        $this->audits = $audits;
    }
    /**
     * @return LighthouseAuditResultV5[]
     */
    public function getAudits()
    {
        return $this->audits;
    }
    /**
     * @param Categories
     */
    public function setCategories(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\Categories $categories)
    {
        $this->categories = $categories;
    }
    /**
     * @return Categories
     */
    public function getCategories()
    {
        return $this->categories;
    }
    /**
     * @param CategoryGroupV5[]
     */
    public function setCategoryGroups($categoryGroups)
    {
        $this->categoryGroups = $categoryGroups;
    }
    /**
     * @return CategoryGroupV5[]
     */
    public function getCategoryGroups()
    {
        return $this->categoryGroups;
    }
    /**
     * @param ConfigSettings
     */
    public function setConfigSettings(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\ConfigSettings $configSettings)
    {
        $this->configSettings = $configSettings;
    }
    /**
     * @return ConfigSettings
     */
    public function getConfigSettings()
    {
        return $this->configSettings;
    }
    /**
     * @param LhrEntity[]
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;
    }
    /**
     * @return LhrEntity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }
    /**
     * @param Environment
     */
    public function setEnvironment(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\Environment $environment)
    {
        $this->environment = $environment;
    }
    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
    /**
     * @param string
     */
    public function setFetchTime($fetchTime)
    {
        $this->fetchTime = $fetchTime;
    }
    /**
     * @return string
     */
    public function getFetchTime()
    {
        return $this->fetchTime;
    }
    /**
     * @param string
     */
    public function setFinalDisplayedUrl($finalDisplayedUrl)
    {
        $this->finalDisplayedUrl = $finalDisplayedUrl;
    }
    /**
     * @return string
     */
    public function getFinalDisplayedUrl()
    {
        return $this->finalDisplayedUrl;
    }
    /**
     * @param string
     */
    public function setFinalUrl($finalUrl)
    {
        $this->finalUrl = $finalUrl;
    }
    /**
     * @return string
     */
    public function getFinalUrl()
    {
        return $this->finalUrl;
    }
    /**
     * @param array
     */
    public function setFullPageScreenshot($fullPageScreenshot)
    {
        $this->fullPageScreenshot = $fullPageScreenshot;
    }
    /**
     * @return array
     */
    public function getFullPageScreenshot()
    {
        return $this->fullPageScreenshot;
    }
    /**
     * @param I18n
     */
    public function setI18n(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\I18n $i18n)
    {
        $this->i18n = $i18n;
    }
    /**
     * @return I18n
     */
    public function getI18n()
    {
        return $this->i18n;
    }
    /**
     * @param string
     */
    public function setLighthouseVersion($lighthouseVersion)
    {
        $this->lighthouseVersion = $lighthouseVersion;
    }
    /**
     * @return string
     */
    public function getLighthouseVersion()
    {
        return $this->lighthouseVersion;
    }
    /**
     * @param string
     */
    public function setMainDocumentUrl($mainDocumentUrl)
    {
        $this->mainDocumentUrl = $mainDocumentUrl;
    }
    /**
     * @return string
     */
    public function getMainDocumentUrl()
    {
        return $this->mainDocumentUrl;
    }
    /**
     * @param string
     */
    public function setRequestedUrl($requestedUrl)
    {
        $this->requestedUrl = $requestedUrl;
    }
    /**
     * @return string
     */
    public function getRequestedUrl()
    {
        return $this->requestedUrl;
    }
    /**
     * @param array[]
     */
    public function setRunWarnings($runWarnings)
    {
        $this->runWarnings = $runWarnings;
    }
    /**
     * @return array[]
     */
    public function getRunWarnings()
    {
        return $this->runWarnings;
    }
    /**
     * @param RuntimeError
     */
    public function setRuntimeError(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\RuntimeError $runtimeError)
    {
        $this->runtimeError = $runtimeError;
    }
    /**
     * @return RuntimeError
     */
    public function getRuntimeError()
    {
        return $this->runtimeError;
    }
    /**
     * @param StackPack[]
     */
    public function setStackPacks($stackPacks)
    {
        $this->stackPacks = $stackPacks;
    }
    /**
     * @return StackPack[]
     */
    public function getStackPacks()
    {
        return $this->stackPacks;
    }
    /**
     * @param Timing
     */
    public function setTiming(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\Timing $timing)
    {
        $this->timing = $timing;
    }
    /**
     * @return Timing
     */
    public function getTiming()
    {
        return $this->timing;
    }
    /**
     * @param string
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }
    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\PagespeedInsights\LighthouseResultV5::class, 'Google\\Site_Kit_Dependencies\\Google_Service_PagespeedInsights_LighthouseResultV5');
