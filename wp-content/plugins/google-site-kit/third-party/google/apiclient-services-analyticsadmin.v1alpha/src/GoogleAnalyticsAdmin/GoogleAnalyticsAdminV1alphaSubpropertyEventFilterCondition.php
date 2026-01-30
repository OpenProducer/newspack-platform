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

class GoogleAnalyticsAdminV1alphaSubpropertyEventFilterCondition extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Required. The field that is being filtered.
     *
     * @var string
     */
    public $fieldName;
    /**
     * A filter for null values.
     *
     * @var bool
     */
    public $nullFilter;
    protected $stringFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterConditionStringFilter::class;
    protected $stringFilterDataType = '';
    /**
     * Required. The field that is being filtered.
     *
     * @param string $fieldName
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
     * A filter for null values.
     *
     * @param bool $nullFilter
     */
    public function setNullFilter($nullFilter)
    {
        $this->nullFilter = $nullFilter;
    }
    /**
     * @return bool
     */
    public function getNullFilter()
    {
        return $this->nullFilter;
    }
    /**
     * A filter for a string-type dimension that matches a particular pattern.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertyEventFilterConditionStringFilter $stringFilter
     */
    public function setStringFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterConditionStringFilter $stringFilter)
    {
        $this->stringFilter = $stringFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertyEventFilterConditionStringFilter
     */
    public function getStringFilter()
    {
        return $this->stringFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertyEventFilterCondition::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSubpropertyEventFilterCondition');
