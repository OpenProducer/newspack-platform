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

class GoogleAnalyticsAdminV1alphaAccessFilter extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $betweenFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessBetweenFilter::class;
    protected $betweenFilterDataType = '';
    /**
     * @var string
     */
    public $fieldName;
    protected $inListFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessInListFilter::class;
    protected $inListFilterDataType = '';
    protected $numericFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessNumericFilter::class;
    protected $numericFilterDataType = '';
    protected $stringFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessStringFilter::class;
    protected $stringFilterDataType = '';
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessBetweenFilter
     */
    public function setBetweenFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessBetweenFilter $betweenFilter)
    {
        $this->betweenFilter = $betweenFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessBetweenFilter
     */
    public function getBetweenFilter()
    {
        return $this->betweenFilter;
    }
    /**
     * @param string
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }
    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessInListFilter
     */
    public function setInListFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessInListFilter $inListFilter)
    {
        $this->inListFilter = $inListFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessInListFilter
     */
    public function getInListFilter()
    {
        return $this->inListFilter;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessNumericFilter
     */
    public function setNumericFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessNumericFilter $numericFilter)
    {
        $this->numericFilter = $numericFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessNumericFilter
     */
    public function getNumericFilter()
    {
        return $this->numericFilter;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaAccessStringFilter
     */
    public function setStringFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessStringFilter $stringFilter)
    {
        $this->stringFilter = $stringFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccessStringFilter
     */
    public function getStringFilter()
    {
        return $this->stringFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaAccessFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaAccessFilter');
