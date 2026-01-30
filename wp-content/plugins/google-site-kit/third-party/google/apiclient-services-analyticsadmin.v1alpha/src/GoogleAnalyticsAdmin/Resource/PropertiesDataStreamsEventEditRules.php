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
namespace Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\Resource;

use Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule;
use Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaListEventEditRulesResponse;
use Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReorderEventEditRulesRequest;
use Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleProtobufEmpty;
/**
 * The "eventEditRules" collection of methods.
 * Typical usage is:
 *  <code>
 *   $analyticsadminService = new Google\Service\GoogleAnalyticsAdminV1alpha(...);
 *   $eventEditRules = $analyticsadminService->properties_dataStreams_eventEditRules;
 *  </code>
 */
class PropertiesDataStreamsEventEditRules extends \Google\Site_Kit_Dependencies\Google\Service\Resource
{
    /**
     * Creates an EventEditRule. (eventEditRules.create)
     *
     * @param string $parent Required. Example format:
     * properties/123/dataStreams/456
     * @param GoogleAnalyticsAdminV1alphaEventEditRule $postBody
     * @param array $optParams Optional parameters.
     * @return GoogleAnalyticsAdminV1alphaEventEditRule
     * @throws \Google\Service\Exception
     */
    public function create($parent, \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule $postBody, $optParams = [])
    {
        $params = ['parent' => $parent, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('create', [$params], \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule::class);
    }
    /**
     * Deletes an EventEditRule. (eventEditRules.delete)
     *
     * @param string $name Required. Example format:
     * properties/123/dataStreams/456/eventEditRules/789
     * @param array $optParams Optional parameters.
     * @return GoogleProtobufEmpty
     * @throws \Google\Service\Exception
     */
    public function delete($name, $optParams = [])
    {
        $params = ['name' => $name];
        $params = \array_merge($params, $optParams);
        return $this->call('delete', [$params], \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleProtobufEmpty::class);
    }
    /**
     * Lookup for a single EventEditRule. (eventEditRules.get)
     *
     * @param string $name Required. The name of the EventEditRule to get. Example
     * format: properties/123/dataStreams/456/eventEditRules/789
     * @param array $optParams Optional parameters.
     * @return GoogleAnalyticsAdminV1alphaEventEditRule
     * @throws \Google\Service\Exception
     */
    public function get($name, $optParams = [])
    {
        $params = ['name' => $name];
        $params = \array_merge($params, $optParams);
        return $this->call('get', [$params], \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule::class);
    }
    /**
     * Lists EventEditRules on a web data stream.
     * (eventEditRules.listPropertiesDataStreamsEventEditRules)
     *
     * @param string $parent Required. Example format:
     * properties/123/dataStreams/456
     * @param array $optParams Optional parameters.
     *
     * @opt_param int pageSize Optional. The maximum number of resources to return.
     * If unspecified, at most 50 resources will be returned. The maximum value is
     * 200 (higher values will be coerced to the maximum).
     * @opt_param string pageToken Optional. A page token, received from a previous
     * `ListEventEditRules` call. Provide this to retrieve the subsequent page. When
     * paginating, all other parameters provided to `ListEventEditRules` must match
     * the call that provided the page token.
     * @return GoogleAnalyticsAdminV1alphaListEventEditRulesResponse
     * @throws \Google\Service\Exception
     */
    public function listPropertiesDataStreamsEventEditRules($parent, $optParams = [])
    {
        $params = ['parent' => $parent];
        $params = \array_merge($params, $optParams);
        return $this->call('list', [$params], \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaListEventEditRulesResponse::class);
    }
    /**
     * Updates an EventEditRule. (eventEditRules.patch)
     *
     * @param string $name Identifier. Resource name for this EventEditRule
     * resource. Format: properties/{property}/dataStreams/{data_stream}/eventEditRu
     * les/{event_edit_rule}
     * @param GoogleAnalyticsAdminV1alphaEventEditRule $postBody
     * @param array $optParams Optional parameters.
     *
     * @opt_param string updateMask Required. The list of fields to be updated.
     * Field names must be in snake case (e.g., "field_to_update"). Omitted fields
     * will not be updated. To replace the entire entity, use one path with the
     * string "*" to match all fields.
     * @return GoogleAnalyticsAdminV1alphaEventEditRule
     * @throws \Google\Service\Exception
     */
    public function patch($name, \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule $postBody, $optParams = [])
    {
        $params = ['name' => $name, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('patch', [$params], \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventEditRule::class);
    }
    /**
     * Changes the processing order of event edit rules on the specified stream.
     * (eventEditRules.reorder)
     *
     * @param string $parent Required. Example format:
     * properties/123/dataStreams/456
     * @param GoogleAnalyticsAdminV1alphaReorderEventEditRulesRequest $postBody
     * @param array $optParams Optional parameters.
     * @return GoogleProtobufEmpty
     * @throws \Google\Service\Exception
     */
    public function reorder($parent, \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReorderEventEditRulesRequest $postBody, $optParams = [])
    {
        $params = ['parent' => $parent, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('reorder', [$params], \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleProtobufEmpty::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\Resource\PropertiesDataStreamsEventEditRules::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_Resource_PropertiesDataStreamsEventEditRules');
