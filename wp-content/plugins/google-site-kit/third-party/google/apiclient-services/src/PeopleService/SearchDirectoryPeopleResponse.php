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
namespace Google\Site_Kit_Dependencies\Google\Service\PeopleService;

class SearchDirectoryPeopleResponse extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'people';
    /**
     * @var string
     */
    public $nextPageToken;
    protected $peopleType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\Person::class;
    protected $peopleDataType = 'array';
    /**
     * @var int
     */
    public $totalSize;
    /**
     * @param string
     */
    public function setNextPageToken($nextPageToken)
    {
        $this->nextPageToken = $nextPageToken;
    }
    /**
     * @return string
     */
    public function getNextPageToken()
    {
        return $this->nextPageToken;
    }
    /**
     * @param Person[]
     */
    public function setPeople($people)
    {
        $this->people = $people;
    }
    /**
     * @return Person[]
     */
    public function getPeople()
    {
        return $this->people;
    }
    /**
     * @param int
     */
    public function setTotalSize($totalSize)
    {
        $this->totalSize = $totalSize;
    }
    /**
     * @return int
     */
    public function getTotalSize()
    {
        return $this->totalSize;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\SearchDirectoryPeopleResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_PeopleService_SearchDirectoryPeopleResponse');
