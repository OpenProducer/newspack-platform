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
namespace Google\Site_Kit_Dependencies\Google\Service\AnalyticsData;

class Filter extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $betweenFilterType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\BetweenFilter::class;
    protected $betweenFilterDataType = '';
    protected $emptyFilterType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\EmptyFilter::class;
    protected $emptyFilterDataType = '';
    /**
     * @var string
     */
    public $fieldName;
    protected $inListFilterType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\InListFilter::class;
    protected $inListFilterDataType = '';
    protected $numericFilterType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\NumericFilter::class;
    protected $numericFilterDataType = '';
    protected $stringFilterType = \Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\StringFilter::class;
    protected $stringFilterDataType = '';
    /**
     * @param BetweenFilter
     */
    public function setBetweenFilter(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\BetweenFilter $betweenFilter)
    {
        $this->betweenFilter = $betweenFilter;
    }
    /**
     * @return BetweenFilter
     */
    public function getBetweenFilter()
    {
        return $this->betweenFilter;
    }
    /**
     * @param EmptyFilter
     */
    public function setEmptyFilter(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\EmptyFilter $emptyFilter)
    {
        $this->emptyFilter = $emptyFilter;
    }
    /**
     * @return EmptyFilter
     */
    public function getEmptyFilter()
    {
        return $this->emptyFilter;
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
     * @param InListFilter
     */
    public function setInListFilter(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\InListFilter $inListFilter)
    {
        $this->inListFilter = $inListFilter;
    }
    /**
     * @return InListFilter
     */
    public function getInListFilter()
    {
        return $this->inListFilter;
    }
    /**
     * @param NumericFilter
     */
    public function setNumericFilter(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\NumericFilter $numericFilter)
    {
        $this->numericFilter = $numericFilter;
    }
    /**
     * @return NumericFilter
     */
    public function getNumericFilter()
    {
        return $this->numericFilter;
    }
    /**
     * @param StringFilter
     */
    public function setStringFilter(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\StringFilter $stringFilter)
    {
        $this->stringFilter = $stringFilter;
    }
    /**
     * @return StringFilter
     */
    public function getStringFilter()
    {
        return $this->stringFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\AnalyticsData\Filter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_AnalyticsData_Filter');
