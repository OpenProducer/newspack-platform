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

class Photo extends \Google\Site_Kit_Dependencies\Google\Model
{
    public $default;
    protected $metadataType = \Google\Site_Kit_Dependencies\Google\Service\PeopleService\FieldMetadata::class;
    protected $metadataDataType = '';
    public $url;
    public function setDefault($default)
    {
        $this->default = $default;
    }
    public function getDefault()
    {
        return $this->default;
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
    public function setUrl($url)
    {
        $this->url = $url;
    }
    public function getUrl()
    {
        return $this->url;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\PeopleService\Photo::class, 'Google\\Site_Kit_Dependencies\\Google_Service_PeopleService_Photo');
