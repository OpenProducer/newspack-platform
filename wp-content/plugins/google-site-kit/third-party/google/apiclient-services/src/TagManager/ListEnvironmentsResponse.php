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

class ListEnvironmentsResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'environment';
    protected $environmentType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Environment::class;
    protected $environmentDataType = 'array';
    public $nextPageToken;
    /**
     * @param Environment[]
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
    /**
     * @return Environment[]
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
    public function setNextPageToken($nextPageToken)
    {
        $this->nextPageToken = $nextPageToken;
    }
    public function getNextPageToken()
    {
        return $this->nextPageToken;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\ListEnvironmentsResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_ListEnvironmentsResponse');
