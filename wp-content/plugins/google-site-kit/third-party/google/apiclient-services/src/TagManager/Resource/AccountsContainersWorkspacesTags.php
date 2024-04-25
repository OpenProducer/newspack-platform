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
namespace Google\Site_Kit_Dependencies\Google\Service\TagManager\Resource;

use Google\Site_Kit_Dependencies\Google\Service\TagManager\ListTagsResponse;
use Google\Site_Kit_Dependencies\Google\Service\TagManager\RevertTagResponse;
use Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag;
/**
 * The "tags" collection of methods.
 * Typical usage is:
 *  <code>
 *   $tagmanagerService = new Google\Service\TagManager(...);
 *   $tags = $tagmanagerService->accounts_containers_workspaces_tags;
 *  </code>
 */
class AccountsContainersWorkspacesTags extends \Google\Site_Kit_Dependencies\Google\Service\Resource
{
    /**
     * Creates a GTM Tag. (tags.create)
     *
     * @param string $parent GTM Workspace's API relative path. Example:
     * accounts/{account_id}/containers/{container_id}/workspaces/{workspace_id}
     * @param Tag $postBody
     * @param array $optParams Optional parameters.
     * @return Tag
     * @throws \Google\Service\Exception
     */
    public function create($parent, \Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag $postBody, $optParams = [])
    {
        $params = ['parent' => $parent, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('create', [$params], \Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag::class);
    }
    /**
     * Deletes a GTM Tag. (tags.delete)
     *
     * @param string $path GTM Tag's API relative path. Example: accounts/{account_i
     * d}/containers/{container_id}/workspaces/{workspace_id}/tags/{tag_id}
     * @param array $optParams Optional parameters.
     * @throws \Google\Service\Exception
     */
    public function delete($path, $optParams = [])
    {
        $params = ['path' => $path];
        $params = \array_merge($params, $optParams);
        return $this->call('delete', [$params]);
    }
    /**
     * Gets a GTM Tag. (tags.get)
     *
     * @param string $path GTM Tag's API relative path. Example: accounts/{account_i
     * d}/containers/{container_id}/workspaces/{workspace_id}/tags/{tag_id}
     * @param array $optParams Optional parameters.
     * @return Tag
     * @throws \Google\Service\Exception
     */
    public function get($path, $optParams = [])
    {
        $params = ['path' => $path];
        $params = \array_merge($params, $optParams);
        return $this->call('get', [$params], \Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag::class);
    }
    /**
     * Lists all GTM Tags of a Container.
     * (tags.listAccountsContainersWorkspacesTags)
     *
     * @param string $parent GTM Workspace's API relative path. Example:
     * accounts/{account_id}/containers/{container_id}/workspaces/{workspace_id}
     * @param array $optParams Optional parameters.
     *
     * @opt_param string pageToken Continuation token for fetching the next page of
     * results.
     * @return ListTagsResponse
     * @throws \Google\Service\Exception
     */
    public function listAccountsContainersWorkspacesTags($parent, $optParams = [])
    {
        $params = ['parent' => $parent];
        $params = \array_merge($params, $optParams);
        return $this->call('list', [$params], \Google\Site_Kit_Dependencies\Google\Service\TagManager\ListTagsResponse::class);
    }
    /**
     * Reverts changes to a GTM Tag in a GTM Workspace. (tags.revert)
     *
     * @param string $path GTM Tag's API relative path. Example: accounts/{account_i
     * d}/containers/{container_id}/workspaces/{workspace_id}/tags/{tag_id}
     * @param array $optParams Optional parameters.
     *
     * @opt_param string fingerprint When provided, this fingerprint must match the
     * fingerprint of thetag in storage.
     * @return RevertTagResponse
     * @throws \Google\Service\Exception
     */
    public function revert($path, $optParams = [])
    {
        $params = ['path' => $path];
        $params = \array_merge($params, $optParams);
        return $this->call('revert', [$params], \Google\Site_Kit_Dependencies\Google\Service\TagManager\RevertTagResponse::class);
    }
    /**
     * Updates a GTM Tag. (tags.update)
     *
     * @param string $path GTM Tag's API relative path. Example: accounts/{account_i
     * d}/containers/{container_id}/workspaces/{workspace_id}/tags/{tag_id}
     * @param Tag $postBody
     * @param array $optParams Optional parameters.
     *
     * @opt_param string fingerprint When provided, this fingerprint must match the
     * fingerprint of the tag in storage.
     * @return Tag
     * @throws \Google\Service\Exception
     */
    public function update($path, \Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag $postBody, $optParams = [])
    {
        $params = ['path' => $path, 'postBody' => $postBody];
        $params = \array_merge($params, $optParams);
        return $this->call('update', [$params], \Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag::class);
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Resource\AccountsContainersWorkspacesTags::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_Resource_AccountsContainersWorkspacesTags');
