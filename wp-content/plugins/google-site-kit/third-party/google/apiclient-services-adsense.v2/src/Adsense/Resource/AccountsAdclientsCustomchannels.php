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
namespace Google\Site_Kit_Dependencies\Google\Service\Adsense\Resource;

use Google\Site_Kit_Dependencies\Google\Service\Adsense\AdsenseEmpty;
use Google\Site_Kit_Dependencies\Google\Service\Adsense\CustomChannel;
use Google\Site_Kit_Dependencies\Google\Service\Adsense\ListCustomChannelsResponse;
use Google\Site_Kit_Dependencies\Google\Service\Adsense\ListLinkedAdUnitsResponse;
/**
 * The "customchannels" collection of methods.
 * Typical usage is:
 *  <code>
 *   $adsenseService = new Google\Service\Adsense(...);
 *   $customchannels = $adsenseService->accounts_adclients_customchannels;
 *  </code>
 */
class AccountsAdclientsCustomchannels extends \Google\Site_Kit_Dependencies\Google\Service\Resource
{
    /**
     * Creates a custom channel. This method can be called only by a restricted set
     * of projects, which are usually owned by [AdSense for
     * Platforms](https://developers.google.com/adsense/platforms/) publishers.
     * Contact your account manager if you need to use this method.
     * (customchannels.create)
     *
     * @param string $parent Required. The ad client to create a custom channel
     * under. Format: accounts/{account}/adclients/{adclient}
     * @param CustomChannel $postBody
     * @param array $optParams Optional parameters.
     * @return CustomChannel
     * @throws \Google\Service\Exception
     */
    public function create($parent, \Google\Site_Kit_Dependencies\Google\Service\Adsense\CustomChannel $postBody, $optParams = [])
    {
        $params = ['parent' => $parent, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('create', [$params], \Google\Site_Kit_Dependencies\Google\Service\Adsense\CustomChannel::class);
    }
    /**
     * Deletes a custom channel. This method can be called only by a restricted set
     * of projects, which are usually owned by [AdSense for
     * Platforms](https://developers.google.com/adsense/platforms/) publishers.
     * Contact your account manager if you need to use this method.
     * (customchannels.delete)
     *
     * @param string $name Required. Name of the custom channel to delete. Format:
     * accounts/{account}/adclients/{adclient}/customchannels/{customchannel}
     * @param array $optParams Optional parameters.
     * @return AdsenseEmpty
     * @throws \Google\Service\Exception
     */
    public function delete($name, $optParams = [])
    {
        $params = ['name' => $name];
        $params = \array_merge($params, $optParams);
        return $this->call('delete', [$params], \Google\Site_Kit_Dependencies\Google\Service\Adsense\AdsenseEmpty::class);
    }
    /**
     * Gets information about the selected custom channel. (customchannels.get)
     *
     * @param string $name Required. Name of the custom channel. Format:
     * accounts/{account}/adclients/{adclient}/customchannels/{customchannel}
     * @param array $optParams Optional parameters.
     * @return CustomChannel
     * @throws \Google\Service\Exception
     */
    public function get($name, $optParams = [])
    {
        $params = ['name' => $name];
        $params = \array_merge($params, $optParams);
        return $this->call('get', [$params], \Google\Site_Kit_Dependencies\Google\Service\Adsense\CustomChannel::class);
    }
    /**
     * Lists all the custom channels available in an ad client.
     * (customchannels.listAccountsAdclientsCustomchannels)
     *
     * @param string $parent Required. The ad client which owns the collection of
     * custom channels. Format: accounts/{account}/adclients/{adclient}
     * @param array $optParams Optional parameters.
     *
     * @opt_param int pageSize The maximum number of custom channels to include in
     * the response, used for paging. If unspecified, at most 10000 custom channels
     * will be returned. The maximum value is 10000; values above 10000 will be
     * coerced to 10000.
     * @opt_param string pageToken A page token, received from a previous
     * `ListCustomChannels` call. Provide this to retrieve the subsequent page. When
     * paginating, all other parameters provided to `ListCustomChannels` must match
     * the call that provided the page token.
     * @return ListCustomChannelsResponse
     * @throws \Google\Service\Exception
     */
    public function listAccountsAdclientsCustomchannels($parent, $optParams = [])
    {
        $params = ['parent' => $parent];
        $params = \array_merge($params, $optParams);
        return $this->call('list', [$params], \Google\Site_Kit_Dependencies\Google\Service\Adsense\ListCustomChannelsResponse::class);
    }
    /**
     * Lists all the ad units available for a custom channel.
     * (customchannels.listLinkedAdUnits)
     *
     * @param string $parent Required. The custom channel which owns the collection
     * of ad units. Format:
     * accounts/{account}/adclients/{adclient}/customchannels/{customchannel}
     * @param array $optParams Optional parameters.
     *
     * @opt_param int pageSize The maximum number of ad units to include in the
     * response, used for paging. If unspecified, at most 10000 ad units will be
     * returned. The maximum value is 10000; values above 10000 will be coerced to
     * 10000.
     * @opt_param string pageToken A page token, received from a previous
     * `ListLinkedAdUnits` call. Provide this to retrieve the subsequent page. When
     * paginating, all other parameters provided to `ListLinkedAdUnits` must match
     * the call that provided the page token.
     * @return ListLinkedAdUnitsResponse
     * @throws \Google\Service\Exception
     */
    public function listLinkedAdUnits($parent, $optParams = [])
    {
        $params = ['parent' => $parent];
        $params = \array_merge($params, $optParams);
        return $this->call('listLinkedAdUnits', [$params], \Google\Site_Kit_Dependencies\Google\Service\Adsense\ListLinkedAdUnitsResponse::class);
    }
    /**
     * Updates a custom channel. This method can be called only by a restricted set
     * of projects, which are usually owned by [AdSense for
     * Platforms](https://developers.google.com/adsense/platforms/) publishers.
     * Contact your account manager if you need to use this method.
     * (customchannels.patch)
     *
     * @param string $name Output only. Resource name of the custom channel. Format:
     * accounts/{account}/adclients/{adclient}/customchannels/{customchannel}
     * @param CustomChannel $postBody
     * @param array $optParams Optional parameters.
     *
     * @opt_param string updateMask The list of fields to update. If empty, a full
     * update is performed.
     * @return CustomChannel
     * @throws \Google\Service\Exception
     */
    public function patch($name, \Google\Site_Kit_Dependencies\Google\Service\Adsense\CustomChannel $postBody, $optParams = [])
    {
        $params = ['name' => $name, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('patch', [$params], \Google\Site_Kit_Dependencies\Google\Service\Adsense\CustomChannel::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\Adsense\Resource\AccountsAdclientsCustomchannels::class, 'Google\\Site_Kit_Dependencies\\Google_Service_Adsense_Resource_AccountsAdclientsCustomchannels');
