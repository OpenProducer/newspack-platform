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

class GoogleAnalyticsAdminV1alphaAudienceFilterClause extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Unspecified clause type.
     */
    public const CLAUSE_TYPE_AUDIENCE_CLAUSE_TYPE_UNSPECIFIED = 'AUDIENCE_CLAUSE_TYPE_UNSPECIFIED';
    /**
     * Users will be included in the Audience if the filter clause is met.
     */
    public const CLAUSE_TYPE_INCLUDE = 'INCLUDE';
    /**
     * Users will be excluded from the Audience if the filter clause is met.
     */
    public const CLAUSE_TYPE_EXCLUDE = 'EXCLUDE';
    /**
     * Required. Specifies whether this is an include or exclude filter clause.
     *
     * @var string
     */
    public $clauseType;
    protected $sequenceFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceSequenceFilter::class;
    protected $sequenceFilterDataType = '';
    protected $simpleFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceSimpleFilter::class;
    protected $simpleFilterDataType = '';
    /**
     * Required. Specifies whether this is an include or exclude filter clause.
     *
     * Accepted values: AUDIENCE_CLAUSE_TYPE_UNSPECIFIED, INCLUDE, EXCLUDE
     *
     * @param self::CLAUSE_TYPE_* $clauseType
     */
    public function setClauseType($clauseType)
    {
        $this->clauseType = $clauseType;
    }
    /**
     * @return self::CLAUSE_TYPE_*
     */
    public function getClauseType()
    {
        return $this->clauseType;
    }
    /**
     * Filters that must occur in a specific order for the user to be a member of
     * the Audience.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceSequenceFilter $sequenceFilter
     */
    public function setSequenceFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceSequenceFilter $sequenceFilter)
    {
        $this->sequenceFilter = $sequenceFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceSequenceFilter
     */
    public function getSequenceFilter()
    {
        return $this->sequenceFilter;
    }
    /**
     * A simple filter that a user must satisfy to be a member of the Audience.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceSimpleFilter $simpleFilter
     */
    public function setSimpleFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceSimpleFilter $simpleFilter)
    {
        $this->simpleFilter = $simpleFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceSimpleFilter
     */
    public function getSimpleFilter()
    {
        return $this->simpleFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterClause::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudienceFilterClause');
