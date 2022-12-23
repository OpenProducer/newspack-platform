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

class GoogleAnalyticsAdminV1alphaExpandedDataSetFilter extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * @var string
     */
    public $fieldName;
    protected $inListFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterInListFilter::class;
    protected $inListFilterDataType = '';
    protected $stringFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterStringFilter::class;
    protected $stringFilterDataType = '';
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
     * @param GoogleAnalyticsAdminV1alphaExpandedDataSetFilterInListFilter
     */
    public function setInListFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterInListFilter $inListFilter)
    {
        $this->inListFilter = $inListFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaExpandedDataSetFilterInListFilter
     */
    public function getInListFilter()
    {
        return $this->inListFilter;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaExpandedDataSetFilterStringFilter
     */
    public function setStringFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterStringFilter $stringFilter)
    {
        $this->stringFilter = $stringFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaExpandedDataSetFilterStringFilter
     */
    public function getStringFilter()
    {
        return $this->stringFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaExpandedDataSetFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaExpandedDataSetFilter');
