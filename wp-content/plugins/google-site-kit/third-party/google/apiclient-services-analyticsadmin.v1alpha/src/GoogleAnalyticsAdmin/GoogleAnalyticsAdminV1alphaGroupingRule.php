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

class GoogleAnalyticsAdminV1alphaGroupingRule extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Required. Customer defined display name for the channel.
     *
     * @var string
     */
    public $displayName;
    protected $expressionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression::class;
    protected $expressionDataType = '';
    /**
     * Required. Customer defined display name for the channel.
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * Required. The Filter Expression that defines the Grouping Rule.
     *
     * @param GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression $expression
     */
    public function setExpression(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression $expression)
    {
        $this->expression = $expression;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChannelGroupFilterExpression
     */
    public function getExpression()
    {
        return $this->expression;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaGroupingRule::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaGroupingRule');
