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
namespace Google\Site_Kit_Dependencies\Google\Service\SiteVerification\Resource;

use Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceGettokenRequest;
use Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceGettokenResponse;
use Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceListResponse;
use Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource;
/**
 * The "webResource" collection of methods.
 * Typical usage is:
 *  <code>
 *   $siteVerificationService = new Google\Service\SiteVerification(...);
 *   $webResource = $siteVerificationService->webResource;
 *  </code>
 */
class WebResource extends \Google\Site_Kit_Dependencies\Google\Service\Resource
{
    /**
     * Relinquish ownership of a website or domain. (webResource.delete)
     *
     * @param string $id The id of a verified site or domain.
     * @param array $optParams Optional parameters.
     * @throws \Google\Service\Exception
     */
    public function delete($id, $optParams = [])
    {
        $params = ['id' => $id];
        $params = \array_merge($params, $optParams);
        return $this->call('delete', [$params]);
    }
    /**
     * Get the most current data for a website or domain. (webResource.get)
     *
     * @param string $id The id of a verified site or domain.
     * @param array $optParams Optional parameters.
     * @return SiteVerificationWebResourceResource
     * @throws \Google\Service\Exception
     */
    public function get($id, $optParams = [])
    {
        $params = ['id' => $id];
        $params = \array_merge($params, $optParams);
        return $this->call('get', [$params], \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource::class);
    }
    /**
     * Get a verification token for placing on a website or domain.
     * (webResource.getToken)
     *
     * @param SiteVerificationWebResourceGettokenRequest $postBody
     * @param array $optParams Optional parameters.
     * @return SiteVerificationWebResourceGettokenResponse
     * @throws \Google\Service\Exception
     */
    public function getToken(\Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceGettokenRequest $postBody, $optParams = [])
    {
        $params = ['postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('getToken', [$params], \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceGettokenResponse::class);
    }
    /**
     * Attempt verification of a website or domain. (webResource.insert)
     *
     * @param string $verificationMethod The method to use for verifying a site or
     * domain.
     * @param SiteVerificationWebResourceResource $postBody
     * @param array $optParams Optional parameters.
     * @return SiteVerificationWebResourceResource
     * @throws \Google\Service\Exception
     */
    public function insert($verificationMethod, \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource $postBody, $optParams = [])
    {
        $params = ['verificationMethod' => $verificationMethod, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('insert', [$params], \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource::class);
    }
    /**
     * Get the list of your verified websites and domains.
     * (webResource.listWebResource)
     *
     * @param array $optParams Optional parameters.
     * @return SiteVerificationWebResourceListResponse
     * @throws \Google\Service\Exception
     */
    public function listWebResource($optParams = [])
    {
        $params = [];
        $params = \array_merge($params, $optParams);
        return $this->call('list', [$params], \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceListResponse::class);
    }
    /**
     * Modify the list of owners for your website or domain. This method supports
     * patch semantics. (webResource.patch)
     *
     * @param string $id The id of a verified site or domain.
     * @param SiteVerificationWebResourceResource $postBody
     * @param array $optParams Optional parameters.
     * @return SiteVerificationWebResourceResource
     * @throws \Google\Service\Exception
     */
    public function patch($id, \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource $postBody, $optParams = [])
    {
        $params = ['id' => $id, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('patch', [$params], \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource::class);
    }
    /**
     * Modify the list of owners for your website or domain. (webResource.update)
     *
     * @param string $id The id of a verified site or domain.
     * @param SiteVerificationWebResourceResource $postBody
     * @param array $optParams Optional parameters.
     * @return SiteVerificationWebResourceResource
     * @throws \Google\Service\Exception
     */
    public function update($id, \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource $postBody, $optParams = [])
    {
        $params = ['id' => $id, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('update', [$params], \Google\Site_Kit_Dependencies\Google\Service\SiteVerification\SiteVerificationWebResourceResource::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\SiteVerification\Resource\WebResource::class, 'Google\\Site_Kit_Dependencies\\Google_Service_SiteVerification_Resource_WebResource');
