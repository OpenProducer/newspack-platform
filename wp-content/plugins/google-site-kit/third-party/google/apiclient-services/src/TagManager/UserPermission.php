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
namespace Google\Site_Kit_Dependencies\Google\Service\TagManager;

class UserPermission extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'containerAccess';
    protected $accountAccessType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\AccountAccess::class;
    protected $accountAccessDataType = '';
    /**
     * @var string
     */
    public $accountId;
    protected $containerAccessType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\ContainerAccess::class;
    protected $containerAccessDataType = 'array';
    /**
     * @var string
     */
    public $emailAddress;
    /**
     * @var string
     */
    public $path;
    /**
     * @param AccountAccess
     */
    public function setAccountAccess(\Google\Site_Kit_Dependencies\Google\Service\TagManager\AccountAccess $accountAccess)
    {
        $this->accountAccess = $accountAccess;
    }
    /**
     * @return AccountAccess
     */
    public function getAccountAccess()
    {
        return $this->accountAccess;
    }
    /**
     * @param string
     */
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    /**
     * @return string
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
    /**
     * @param ContainerAccess[]
     */
    public function setContainerAccess($containerAccess)
    {
        $this->containerAccess = $containerAccess;
    }
    /**
     * @return ContainerAccess[]
     */
    public function getContainerAccess()
    {
        return $this->containerAccess;
    }
    /**
     * @param string
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }
    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }
    /**
     * @param string
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\UserPermission::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_UserPermission');
