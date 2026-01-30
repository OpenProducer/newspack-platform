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

class GoogleAnalyticsAdminV1alphaExpandedDataSetFilterStringFilter extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Unspecified
     */
    public const MATCH_TYPE_MATCH_TYPE_UNSPECIFIED = 'MATCH_TYPE_UNSPECIFIED';
    /**
     * Exact match of the string value.
     */
    public const MATCH_TYPE_EXACT = 'EXACT';
    /**
     * Contains the string value.
     */
    public const MATCH_TYPE_CONTAINS = 'CONTAINS';
    /**
     * Optional. If true, the match is case-sensitive. If false, the match is
     * case-insensitive. Must be true when match_type is EXACT. Must be false when
     * match_type is CONTAINS.
     *
     * @var bool
     */
    public $caseSensitive;
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
     * Optional. If true, the match is case-sensitive. If false, the match is
     * case-insensitive. Must be true when match_type is EXACT. Must be false when
     * match_type is CONTAINS.
     *
     * @param bool $caseSensitive
     */
    public function setCaseSensitive($caseSensitive)
    {
        $this->caseSensitive = $caseSensitive;
    }
    /**
     * @return bool
     */
    public function getCaseSensitive()
    {
        return $this->caseSensitive;
    }
    /**
     * Required. The match type for the string filter.
     *
     * Accepted values: MATCH_TYPE_UNSPECIFIED, EXACT, CONTAINS
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSetFilterStringFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaExpandedDataSetFilterStringFilter');
