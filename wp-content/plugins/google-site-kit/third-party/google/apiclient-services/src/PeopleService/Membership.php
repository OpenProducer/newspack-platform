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

class Membership extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $contactGroupMembershipType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\ContactGroupMembership::class;
    protected $contactGroupMembershipDataType = '';
    protected $domainMembershipType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\DomainMembership::class;
    protected $domainMembershipDataType = '';
    protected $metadataType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\FieldMetadata::class;
    protected $metadataDataType = '';
    /**
     * @param ContactGroupMembership
     */
    public function setContactGroupMembership(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\ContactGroupMembership $contactGroupMembership)
    {
        $this->contactGroupMembership = $contactGroupMembership;
    }
    /**
     * @return ContactGroupMembership
     */
    public function getContactGroupMembership()
    {
        return $this->contactGroupMembership;
    }
    /**
     * @param DomainMembership
     */
    public function setDomainMembership(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\DomainMembership $domainMembership)
    {
        $this->domainMembership = $domainMembership;
    }
    /**
     * @return DomainMembership
     */
    public function getDomainMembership()
    {
        return $this->domainMembership;
    }
    /**
     * @param FieldMetadata
     */
    public function setMetadata(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\FieldMetadata $metadata)
    {
        $this->metadata = $metadata;
    }
    /**
     * @return FieldMetadata
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\Membership::class, 'Google\\Site_Kit_Dependencies\\Google_Service_PeopleService_Membership');
