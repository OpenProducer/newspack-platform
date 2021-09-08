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
namespace Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin;

class GoogleAnalyticsAdminV1alphaChangeHistoryEvent extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'changes';
    public $actorType;
    public $changeTime;
    protected $changesType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaChangeHistoryChange::class;
    protected $changesDataType = 'array';
    public $changesFiltered;
    public $id;
    public $userActorEmail;
    public function setActorType($actorType)
    {
        $this->actorType = $actorType;
    }
    public function getActorType()
    {
        return $this->actorType;
    }
    public function setChangeTime($changeTime)
    {
        $this->changeTime = $changeTime;
    }
    public function getChangeTime()
    {
        return $this->changeTime;
    }
    /**
     * @param GoogleAnalyticsAdminV1alphaChangeHistoryChange[]
     */
    public function setChanges($changes)
    {
        $this->changes = $changes;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChangeHistoryChange[]
     */
    public function getChanges()
    {
        return $this->changes;
    }
    public function setChangesFiltered($changesFiltered)
    {
        $this->changesFiltered = $changesFiltered;
    }
    public function getChangesFiltered()
    {
        return $this->changesFiltered;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setUserActorEmail($userActorEmail)
    {
        $this->userActorEmail = $userActorEmail;
    }
    public function getUserActorEmail()
    {
        return $this->userActorEmail;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdmin\GoogleAnalyticsAdminV1alphaChangeHistoryEvent::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdmin_GoogleAnalyticsAdminV1alphaChangeHistoryEvent');
