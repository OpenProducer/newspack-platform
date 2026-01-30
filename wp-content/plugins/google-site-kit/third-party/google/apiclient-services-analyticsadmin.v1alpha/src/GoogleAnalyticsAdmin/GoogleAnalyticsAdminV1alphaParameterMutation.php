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

class GoogleAnalyticsAdminV1alphaParameterMutation extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Required. The name of the parameter to mutate. This value must: * be less
     * than 40 characters. * be unique across across all mutations within the rule
     * * consist only of letters, digits or _ (underscores) For event edit rules,
     * the name may also be set to 'event_name' to modify the event_name in place.
     *
     * @var string
     */
    public $parameter;
    /**
     * Required. The value mutation to perform. * Must be less than 100
     * characters. * To specify a constant value for the param, use the value's
     * string. * To copy value from another parameter, use syntax like
     * "[[other_parameter]]" For more details, see this [help center
     * article](https://support.google.com/analytics/answer/10085872#modify-an-
     * event&zippy=%2Cin-this-article%2Cmodify-parameters).
     *
     * @var string
     */
    public $parameterValue;
    /**
     * Required. The name of the parameter to mutate. This value must: * be less
     * than 40 characters. * be unique across across all mutations within the rule
     * * consist only of letters, digits or _ (underscores) For event edit rules,
     * the name may also be set to 'event_name' to modify the event_name in place.
     *
     * @param string $parameter
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }
    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }
    /**
     * Required. The value mutation to perform. * Must be less than 100
     * characters. * To specify a constant value for the param, use the value's
     * string. * To copy value from another parameter, use syntax like
     * "[[other_parameter]]" For more details, see this [help center
     * article](https://support.google.com/analytics/answer/10085872#modify-an-
     * event&zippy=%2Cin-this-article%2Cmodify-parameters).
     *
     * @param string $parameterValue
     */
    public function setParameterValue($parameterValue)
    {
        $this->parameterValue = $parameterValue;
    }
    /**
     * @return string
     */
    public function getParameterValue()
    {
        return $this->parameterValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaParameterMutation::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaParameterMutation');
