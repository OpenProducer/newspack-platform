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

class GoogleAnalyticsAdminV1alphaAudienceSequenceFilterAudienceSequenceStep extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Scope is not specified.
     */
    public const SCOPE_AUDIENCE_FILTER_SCOPE_UNSPECIFIED = 'AUDIENCE_FILTER_SCOPE_UNSPECIFIED';
    /**
     * User joins the Audience if the filter condition is met within one event.
     */
    public const SCOPE_AUDIENCE_FILTER_SCOPE_WITHIN_SAME_EVENT = 'AUDIENCE_FILTER_SCOPE_WITHIN_SAME_EVENT';
    /**
     * User joins the Audience if the filter condition is met within one session.
     */
    public const SCOPE_AUDIENCE_FILTER_SCOPE_WITHIN_SAME_SESSION = 'AUDIENCE_FILTER_SCOPE_WITHIN_SAME_SESSION';
    /**
     * User joins the Audience if the filter condition is met by any event across
     * any session.
     */
    public const SCOPE_AUDIENCE_FILTER_SCOPE_ACROSS_ALL_SESSIONS = 'AUDIENCE_FILTER_SCOPE_ACROSS_ALL_SESSIONS';
    /**
     * Optional. When set, this step must be satisfied within the
     * constraint_duration of the previous step (For example, t[i] - t[i-1] <=
     * constraint_duration). If not set, there is no duration requirement (the
     * duration is effectively unlimited). It is ignored for the first step.
     *
     * @var string
     */
    public $constraintDuration;
    protected $filterExpressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpression::class;
    protected $filterExpressionDataType = '';
    /**
     * Optional. If true, the event satisfying this step must be the very next
     * event after the event satisfying the last step. If unset or false, this
     * step indirectly follows the prior step; for example, there may be events
     * between the prior step and this step. It is ignored for the first step.
     *
     * @var bool
     */
    public $immediatelyFollows;
    /**
     * Required. Immutable. Specifies the scope for this step.
     *
     * @var string
     */
    public $scope;
    /**
     * Optional. When set, this step must be satisfied within the
     * constraint_duration of the previous step (For example, t[i] - t[i-1] <=
     * constraint_duration). If not set, there is no duration requirement (the
     * duration is effectively unlimited). It is ignored for the first step.
     *
     * @param string $constraintDuration
     */
    public function setConstraintDuration($constraintDuration)
    {
        $this->constraintDuration = $constraintDuration;
    }
    /**
     * @return string
     */
    public function getConstraintDuration()
    {
        return $this->constraintDuration;
    }
    /**
     * Required. Immutable. A logical expression of Audience dimension, metric, or
     * event filters in each step.
     *
     * @param GoogleAnalyticsAdminV1alphaAudienceFilterExpression $filterExpression
     */
    public function setFilterExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceFilterExpression $filterExpression)
    {
        $this->filterExpression = $filterExpression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudienceFilterExpression
     */
    public function getFilterExpression()
    {
        return $this->filterExpression;
    }
    /**
     * Optional. If true, the event satisfying this step must be the very next
     * event after the event satisfying the last step. If unset or false, this
     * step indirectly follows the prior step; for example, there may be events
     * between the prior step and this step. It is ignored for the first step.
     *
     * @param bool $immediatelyFollows
     */
    public function setImmediatelyFollows($immediatelyFollows)
    {
        $this->immediatelyFollows = $immediatelyFollows;
    }
    /**
     * @return bool
     */
    public function getImmediatelyFollows()
    {
        return $this->immediatelyFollows;
    }
    /**
     * Required. Immutable. Specifies the scope for this step.
     *
     * Accepted values: AUDIENCE_FILTER_SCOPE_UNSPECIFIED,
     * AUDIENCE_FILTER_SCOPE_WITHIN_SAME_EVENT,
     * AUDIENCE_FILTER_SCOPE_WITHIN_SAME_SESSION,
     * AUDIENCE_FILTER_SCOPE_ACROSS_ALL_SESSIONS
     *
     * @param self::SCOPE_* $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
    /**
     * @return self::SCOPE_*
     */
    public function getScope()
    {
        return $this->scope;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudienceSequenceFilterAudienceSequenceStep::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAudienceSequenceFilterAudienceSequenceStep');
