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

class GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Optional. Indicates whether this filter needs dynamic evaluation or not. If
     * set to true, users join the Audience if they ever met the condition (static
     * evaluation). If unset or set to false, user evaluation for an Audience is
     * dynamic; users are added to an Audience when they meet the conditions and
     * then removed when they no longer meet them. This can only be set when
     * Audience scope is ACROSS_ALL_SESSIONS.
     *
     * @var bool
     */
    public $atAnyPointInTime;
    protected $betweenFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterBetweenFilter::class;
    protected $betweenFilterDataType = '';
    /**
     * Required. Immutable. The dimension name or metric name to filter. If the
     * field name refers to a custom dimension or metric, a scope prefix will be
     * added to the front of the custom dimensions or metric name. For more on
     * scope prefixes or custom dimensions/metrics, reference the [Google
     * Analytics Data API documentation]
     * (https://developers.google.com/analytics/devguides/reporting/data/v1/api-
     * schema#custom_dimensions).
     *
     * @var string
     */
    public $fieldName;
    /**
     * Optional. If set, specifies the time window for which to evaluate data in
     * number of days. If not set, then audience data is evaluated against
     * lifetime data (For example, infinite time window). For example, if set to 1
     * day, only the current day's data is evaluated. The reference point is the
     * current day when at_any_point_in_time is unset or false. It can only be set
     * when Audience scope is ACROSS_ALL_SESSIONS and cannot be greater than 60
     * days.
     *
     * @var int
     */
    public $inAnyNDayPeriod;
    protected $inListFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterInListFilter::class;
    protected $inListFilterDataType = '';
    protected $numericFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericFilter::class;
    protected $numericFilterDataType = '';
    protected $stringFilterType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterStringFilter::class;
    protected $stringFilterDataType = '';
    /**
     * Optional. Indicates whether this filter needs dynamic evaluation or not. If
     * set to true, users join the Audience if they ever met the condition (static
     * evaluation). If unset or set to false, user evaluation for an Audience is
     * dynamic; users are added to an Audience when they meet the conditions and
     * then removed when they no longer meet them. This can only be set when
     * Audience scope is ACROSS_ALL_SESSIONS.
     *
     * @param bool $atAnyPointInTime
     */
    public function setAtAnyPointInTime($atAnyPointInTime)
    {
        $this->atAnyPointInTime = $atAnyPointInTime;
    }
    /**
     * @return bool
     */
    public function getAtAnyPointInTime()
    {
        return $this->atAnyPointInTime;
    }
    /**
     * A filter for numeric or date values between certain values on a dimension
     * or metric.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterBetweenFilter $betweenFilter
     */
    public function setBetweenFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterBetweenFilter $betweenFilter)
    {
        $this->betweenFilter = $betweenFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterBetweenFilter
     */
    public function getBetweenFilter()
    {
        return $this->betweenFilter;
    }
    /**
     * Required. Immutable. The dimension name or metric name to filter. If the
     * field name refers to a custom dimension or metric, a scope prefix will be
     * added to the front of the custom dimensions or metric name. For more on
     * scope prefixes or custom dimensions/metrics, reference the [Google
     * Analytics Data API documentation]
     * (https://developers.google.com/analytics/devguides/reporting/data/v1/api-
     * schema#custom_dimensions).
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
     * Optional. If set, specifies the time window for which to evaluate data in
     * number of days. If not set, then audience data is evaluated against
     * lifetime data (For example, infinite time window). For example, if set to 1
     * day, only the current day's data is evaluated. The reference point is the
     * current day when at_any_point_in_time is unset or false. It can only be set
     * when Audience scope is ACROSS_ALL_SESSIONS and cannot be greater than 60
     * days.
     *
     * @param int $inAnyNDayPeriod
     */
    public function setInAnyNDayPeriod($inAnyNDayPeriod)
    {
        $this->inAnyNDayPeriod = $inAnyNDayPeriod;
    }
    /**
     * @return int
     */
    public function getInAnyNDayPeriod()
    {
        return $this->inAnyNDayPeriod;
    }
    /**
     * A filter for a string dimension that matches a particular list of options.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterInListFilter $inListFilter
     */
    public function setInListFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterInListFilter $inListFilter)
    {
        $this->inListFilter = $inListFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterInListFilter
     */
    public function getInListFilter()
    {
        return $this->inListFilter;
    }
    /**
     * A filter for numeric or date values on a dimension or metric.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericFilter $numericFilter
     */
    public function setNumericFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericFilter $numericFilter)
    {
        $this->numericFilter = $numericFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterNumericFilter
     */
    public function getNumericFilter()
    {
        return $this->numericFilter;
    }
    /**
     * A filter for a string-type dimension that matches a particular pattern.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterStringFilter $stringFilter
     */
    public function setStringFilter(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterStringFilter $stringFilter)
    {
        $this->stringFilter = $stringFilter;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilterStringFilter
     */
    public function getStringFilter()
    {
        return $this->stringFilter;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudienceDimensionOrMetricFilter');
