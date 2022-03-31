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

class PersonResponse extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * @var int
     */
    public $httpStatusCode;
    protected $personType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\Person::class;
    protected $personDataType = '';
    /**
     * @var string
     */
    public $requestedResourceName;
    protected $statusType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\Status::class;
    protected $statusDataType = '';
    /**
     * @param int
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
    }
    /**
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
    /**
     * @param Person
     */
    public function setPerson(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\Person $person)
    {
        $this->person = $person;
    }
    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }
    /**
     * @param string
     */
    public function setRequestedResourceName($requestedResourceName)
    {
        $this->requestedResourceName = $requestedResourceName;
    }
    /**
     * @return string
     */
    public function getRequestedResourceName()
    {
        return $this->requestedResourceName;
    }
    /**
     * @param Status
     */
    public function setStatus(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\Status $status)
    {
        $this->status = $status;
    }
    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\PersonResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_PeopleService_PersonResponse');
