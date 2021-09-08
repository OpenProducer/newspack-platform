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

class RevertZoneResponse extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $zoneType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Zone::class;
    protected $zoneDataType = '';
    /**
     * @param Zone
     */
    public function setZone(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Zone $zone)
    {
        $this->zone = $zone;
    }
    /**
     * @return Zone
     */
    public function getZone()
    {
        return $this->zone;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\RevertZoneResponse::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_RevertZoneResponse');
