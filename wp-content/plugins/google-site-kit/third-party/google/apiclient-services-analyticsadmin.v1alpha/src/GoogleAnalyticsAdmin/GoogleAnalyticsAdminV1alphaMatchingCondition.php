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

class GoogleAnalyticsAdminV1alphaMatchingCondition extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Unknown
     */
    public const COMPARISON_TYPE_COMPARISON_TYPE_UNSPECIFIED = 'COMPARISON_TYPE_UNSPECIFIED';
    /**
     * Equals, case sensitive
     */
    public const COMPARISON_TYPE_EQUALS = 'EQUALS';
    /**
     * Equals, case insensitive
     */
    public const COMPARISON_TYPE_EQUALS_CASE_INSENSITIVE = 'EQUALS_CASE_INSENSITIVE';
    /**
     * Contains, case sensitive
     */
    public const COMPARISON_TYPE_CONTAINS = 'CONTAINS';
    /**
     * Contains, case insensitive
     */
    public const COMPARISON_TYPE_CONTAINS_CASE_INSENSITIVE = 'CONTAINS_CASE_INSENSITIVE';
    /**
     * Starts with, case sensitive
     */
    public const COMPARISON_TYPE_STARTS_WITH = 'STARTS_WITH';
    /**
     * Starts with, case insensitive
     */
    public const COMPARISON_TYPE_STARTS_WITH_CASE_INSENSITIVE = 'STARTS_WITH_CASE_INSENSITIVE';
    /**
     * Ends with, case sensitive
     */
    public const COMPARISON_TYPE_ENDS_WITH = 'ENDS_WITH';
    /**
     * Ends with, case insensitive
     */
    public const COMPARISON_TYPE_ENDS_WITH_CASE_INSENSITIVE = 'ENDS_WITH_CASE_INSENSITIVE';
    /**
     * Greater than
     */
    public const COMPARISON_TYPE_GREATER_THAN = 'GREATER_THAN';
    /**
     * Greater than or equal
     */
    public const COMPARISON_TYPE_GREATER_THAN_OR_EQUAL = 'GREATER_THAN_OR_EQUAL';
    /**
     * Less than
     */
    public const COMPARISON_TYPE_LESS_THAN = 'LESS_THAN';
    /**
     * Less than or equal
     */
    public const COMPARISON_TYPE_LESS_THAN_OR_EQUAL = 'LESS_THAN_OR_EQUAL';
    /**
     * regular expression. Only supported for web streams.
     */
    public const COMPARISON_TYPE_REGULAR_EXPRESSION = 'REGULAR_EXPRESSION';
    /**
     * regular expression, case insensitive. Only supported for web streams.
     */
    public const COMPARISON_TYPE_REGULAR_EXPRESSION_CASE_INSENSITIVE = 'REGULAR_EXPRESSION_CASE_INSENSITIVE';
    /**
     * Required. The type of comparison to be applied to the value.
     *
     * @var string
     */
    public $comparisonType;
    /**
     * Required. The name of the field that is compared against for the condition.
     * If 'event_name' is specified this condition will apply to the name of the
     * event. Otherwise the condition will apply to a parameter with the specified
     * name. This value cannot contain spaces.
     *
     * @var string
     */
    public $field;
    /**
     * Whether or not the result of the comparison should be negated. For example,
     * if `negated` is true, then 'equals' comparisons would function as 'not
     * equals'.
     *
     * @var bool
     */
    public $negated;
    /**
     * Required. The value being compared against for this condition. The runtime
     * implementation may perform type coercion of this value to evaluate this
     * condition based on the type of the parameter value.
     *
     * @var string
     */
    public $value;
    /**
     * Required. The type of comparison to be applied to the value.
     *
     * Accepted values: COMPARISON_TYPE_UNSPECIFIED, EQUALS,
     * EQUALS_CASE_INSENSITIVE, CONTAINS, CONTAINS_CASE_INSENSITIVE, STARTS_WITH,
     * STARTS_WITH_CASE_INSENSITIVE, ENDS_WITH, ENDS_WITH_CASE_INSENSITIVE,
     * GREATER_THAN, GREATER_THAN_OR_EQUAL, LESS_THAN, LESS_THAN_OR_EQUAL,
     * REGULAR_EXPRESSION, REGULAR_EXPRESSION_CASE_INSENSITIVE
     *
     * @param self::COMPARISON_TYPE_* $comparisonType
     */
    public function setComparisonType($comparisonType)
    {
        $this->comparisonType = $comparisonType;
    }
    /**
     * @return self::COMPARISON_TYPE_*
     */
    public function getComparisonType()
    {
        return $this->comparisonType;
    }
    /**
     * Required. The name of the field that is compared against for the condition.
     * If 'event_name' is specified this condition will apply to the name of the
     * event. Otherwise the condition will apply to a parameter with the specified
     * name. This value cannot contain spaces.
     *
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }
    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
    /**
     * Whether or not the result of the comparison should be negated. For example,
     * if `negated` is true, then 'equals' comparisons would function as 'not
     * equals'.
     *
     * @param bool $negated
     */
    public function setNegated($negated)
    {
        $this->negated = $negated;
    }
    /**
     * @return bool
     */
    public function getNegated()
    {
        return $this->negated;
    }
    /**
     * Required. The value being compared against for this condition. The runtime
     * implementation may perform type coercion of this value to evaluate this
     * condition based on the type of the parameter value.
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaMatchingCondition::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaMatchingCondition');
