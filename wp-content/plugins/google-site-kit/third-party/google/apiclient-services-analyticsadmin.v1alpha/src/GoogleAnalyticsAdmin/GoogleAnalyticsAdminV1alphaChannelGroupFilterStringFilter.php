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

class GoogleAnalyticsAdminV1alphaChannelGroupFilterStringFilter extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Default match type.
     */
    public const MATCH_TYPE_MATCH_TYPE_UNSPECIFIED = 'MATCH_TYPE_UNSPECIFIED';
    /**
     * Exact match of the string value.
     */
    public const MATCH_TYPE_EXACT = 'EXACT';
    /**
     * Begins with the string value.
     */
    public const MATCH_TYPE_BEGINS_WITH = 'BEGINS_WITH';
    /**
     * Ends with the string value.
     */
    public const MATCH_TYPE_ENDS_WITH = 'ENDS_WITH';
    /**
     * Contains the string value.
     */
    public const MATCH_TYPE_CONTAINS = 'CONTAINS';
    /**
     * Full regular expression match with the string value.
     */
    public const MATCH_TYPE_FULL_REGEXP = 'FULL_REGEXP';
    /**
     * Partial regular expression match with the string value.
     */
    public const MATCH_TYPE_PARTIAL_REGEXP = 'PARTIAL_REGEXP';
    /**
     * Required. The match type for the string filter.
     *
     * @var string
     */
    public $matchType;
    /**
     * Required. The string value to be matched against.
     *
     * @var string
     */
    public $value;
    /**
     * Required. The match type for the string filter.
     *
     * Accepted values: MATCH_TYPE_UNSPECIFIED, EXACT, BEGINS_WITH, ENDS_WITH,
     * CONTAINS, FULL_REGEXP, PARTIAL_REGEXP
     *
     * @param self::MATCH_TYPE_* $matchType
     */
    public function setMatchType($matchType)
    {
        $this->matchType = $matchType;
    }
    /**
     * @return self::MATCH_TYPE_*
     */
    public function getMatchType()
    {
        return $this->matchType;
    }
    /**
     * Required. The string value to be matched against.
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterStringFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaChannelGroupFilterStringFilter');
