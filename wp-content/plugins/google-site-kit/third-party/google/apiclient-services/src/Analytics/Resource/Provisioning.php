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
namespace Google\Site_Kit_Dependencies\Google\Service\Analytics\Resource;

use Google\Site_Kit_Dependencies\Google\Service\Analytics\AccountTicket;
use Google\Site_Kit_Dependencies\Google\Service\Analytics\AccountTreeRequest;
use Google\Site_Kit_Dependencies\Google\Service\Analytics\AccountTreeResponse;
/**
 * The "provisioning" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsService = new Google\Service\Analytics(...);
 *   $provisioning = $analyticsService->provisioning;
 *  </code>
 */
class Provisioning extends \Google\Site_Kit_Dependencies\Google\Service\Resource
{
    /**
     * Creates an account ticket. (provisioning.createAccountTicket)
     *
     * @param AccountTicket $postBody
     * @param array $optParams Optional parameters.
     * @return AccountTicket
     */
    public function createAccountTicket(\Google\Site_Kit_Dependencies\Google\Service\Analytics\AccountTicket $postBody, $optParams = [])
    {
        $params = ['postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('createAccountTicket', [$params], \Google\Site_Kit_Dependencies\Google\Service\Analytics\AccountTicket::class);
    }
    /**
     * Provision account. (provisioning.createAccountTree)
     *
     * @param AccountTreeRequest $postBody
     * @param array $optParams Optional parameters.
     * @return AccountTreeResponse
     */
    public function createAccountTree(\Google\Site_Kit_Dependencies\Google\Service\Analytics\AccountTreeRequest $postBody, $optParams = [])
    {
        $params = ['postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('createAccountTree', [$params], \Google\Site_Kit_Dependencies\Google\Service\Analytics\AccountTreeResponse::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\Analytics\Resource\Provisioning::class, 'Google\\Site_Kit_Dependencies\\Google_Service_Analytics_Resource_Provisioning');
