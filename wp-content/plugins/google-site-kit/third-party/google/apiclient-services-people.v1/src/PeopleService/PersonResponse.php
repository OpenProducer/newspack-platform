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
     * **DEPRECATED** (Please use status instead) [HTTP 1.1 status code]
     * (http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html).
     *
     * @deprecated
     * @var int
     */
    public $httpStatusCode;
    protected $personType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\Person::class;
    protected $personDataType = '';
    /**
     * The original requested resource name. May be different than the resource
     * name on the returned person. The resource name can change when adding or
     * removing fields that link a contact and profile such as a verified email,
     * verified phone number, or a profile URL.
     *
     * @var string
     */
    public $requestedResourceName;
    protected $statusType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\Status::class;
    protected $statusDataType = '';
    /**
     * **DEPRECATED** (Please use status instead) [HTTP 1.1 status code]
     * (http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html).
     *
     * @deprecated
     * @param int $httpStatusCode
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
    }
    /**
     * @deprecated
     * @return int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
    /**
     * The person.
     *
     * @param Person $person
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
     * The original requested resource name. May be different than the resource
     * name on the returned person. The resource name can change when adding or
     * removing fields that link a contact and profile such as a verified email,
     * verified phone number, or a profile URL.
     *
     * @param string $requestedResourceName
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
     * The status of the response.
     *
     * @param Status $status
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
