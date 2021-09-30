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

class Tag extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'teardownTag';
    public $accountId;
    public $blockingRuleId;
    public $blockingTriggerId;
    protected $consentSettingsType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\TagConsentSetting::class;
    protected $consentSettingsDataType = '';
    public $containerId;
    public $fingerprint;
    public $firingRuleId;
    public $firingTriggerId;
    public $liveOnly;
    protected $monitoringMetadataType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $monitoringMetadataDataType = '';
    public $monitoringMetadataTagNameKey;
    public $name;
    public $notes;
    protected $parameterType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $parameterDataType = 'array';
    public $parentFolderId;
    public $path;
    public $paused;
    protected $priorityType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $priorityDataType = '';
    public $scheduleEndMs;
    public $scheduleStartMs;
    protected $setupTagType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\SetupTag::class;
    protected $setupTagDataType = 'array';
    public $tagFiringOption;
    public $tagId;
    public $tagManagerUrl;
    protected $teardownTagType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\TeardownTag::class;
    protected $teardownTagDataType = 'array';
    public $type;
    public $workspaceId;
    public function setAccountId($accountId)
    {
        $this->accountId = $accountId;
    }
    public function getAccountId()
    {
        return $this->accountId;
    }
    public function setBlockingRuleId($blockingRuleId)
    {
        $this->blockingRuleId = $blockingRuleId;
    }
    public function getBlockingRuleId()
    {
        return $this->blockingRuleId;
    }
    public function setBlockingTriggerId($blockingTriggerId)
    {
        $this->blockingTriggerId = $blockingTriggerId;
    }
    public function getBlockingTriggerId()
    {
        return $this->blockingTriggerId;
    }
    /**
     * @param TagConsentSetting
     */
    public function setConsentSettings(\Google\Site_Kit_Dependencies\Google\Service\TagManager\TagConsentSetting $consentSettings)
    {
        $this->consentSettings = $consentSettings;
    }
    /**
     * @return TagConsentSetting
     */
    public function getConsentSettings()
    {
        return $this->consentSettings;
    }
    public function setContainerId($containerId)
    {
        $this->containerId = $containerId;
    }
    public function getContainerId()
    {
        return $this->containerId;
    }
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;
    }
    public function getFingerprint()
    {
        return $this->fingerprint;
    }
    public function setFiringRuleId($firingRuleId)
    {
        $this->firingRuleId = $firingRuleId;
    }
    public function getFiringRuleId()
    {
        return $this->firingRuleId;
    }
    public function setFiringTriggerId($firingTriggerId)
    {
        $this->firingTriggerId = $firingTriggerId;
    }
    public function getFiringTriggerId()
    {
        return $this->firingTriggerId;
    }
    public function setLiveOnly($liveOnly)
    {
        $this->liveOnly = $liveOnly;
    }
    public function getLiveOnly()
    {
        return $this->liveOnly;
    }
    /**
     * @param Parameter
     */
    public function setMonitoringMetadata(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $monitoringMetadata)
    {
        $this->monitoringMetadata = $monitoringMetadata;
    }
    /**
     * @return Parameter
     */
    public function getMonitoringMetadata()
    {
        return $this->monitoringMetadata;
    }
    public function setMonitoringMetadataTagNameKey($monitoringMetadataTagNameKey)
    {
        $this->monitoringMetadataTagNameKey = $monitoringMetadataTagNameKey;
    }
    public function getMonitoringMetadataTagNameKey()
    {
        return $this->monitoringMetadataTagNameKey;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }
    public function getNotes()
    {
        return $this->notes;
    }
    /**
     * @param Parameter[]
     */
    public function setParameter($parameter)
    {
        $this->parameter = $parameter;
    }
    /**
     * @return Parameter[]
     */
    public function getParameter()
    {
        return $this->parameter;
    }
    public function setParentFolderId($parentFolderId)
    {
        $this->parentFolderId = $parentFolderId;
    }
    public function getParentFolderId()
    {
        return $this->parentFolderId;
    }
    public function setPath($path)
    {
        $this->path = $path;
    }
    public function getPath()
    {
        return $this->path;
    }
    public function setPaused($paused)
    {
        $this->paused = $paused;
    }
    public function getPaused()
    {
        return $this->paused;
    }
    /**
     * @param Parameter
     */
    public function setPriority(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $priority)
    {
        $this->priority = $priority;
    }
    /**
     * @return Parameter
     */
    public function getPriority()
    {
        return $this->priority;
    }
    public function setScheduleEndMs($scheduleEndMs)
    {
        $this->scheduleEndMs = $scheduleEndMs;
    }
    public function getScheduleEndMs()
    {
        return $this->scheduleEndMs;
    }
    public function setScheduleStartMs($scheduleStartMs)
    {
        $this->scheduleStartMs = $scheduleStartMs;
    }
    public function getScheduleStartMs()
    {
        return $this->scheduleStartMs;
    }
    /**
     * @param SetupTag[]
     */
    public function setSetupTag($setupTag)
    {
        $this->setupTag = $setupTag;
    }
    /**
     * @return SetupTag[]
     */
    public function getSetupTag()
    {
        return $this->setupTag;
    }
    public function setTagFiringOption($tagFiringOption)
    {
        $this->tagFiringOption = $tagFiringOption;
    }
    public function getTagFiringOption()
    {
        return $this->tagFiringOption;
    }
    public function setTagId($tagId)
    {
        $this->tagId = $tagId;
    }
    public function getTagId()
    {
        return $this->tagId;
    }
    public function setTagManagerUrl($tagManagerUrl)
    {
        $this->tagManagerUrl = $tagManagerUrl;
    }
    public function getTagManagerUrl()
    {
        return $this->tagManagerUrl;
    }
    /**
     * @param TeardownTag[]
     */
    public function setTeardownTag($teardownTag)
    {
        $this->teardownTag = $teardownTag;
    }
    /**
     * @return TeardownTag[]
     */
    public function getTeardownTag()
    {
        return $this->teardownTag;
    }
    public function setType($type)
    {
        $this->type = $type;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setWorkspaceId($workspaceId)
    {
        $this->workspaceId = $workspaceId;
    }
    public function getWorkspaceId()
    {
        return $this->workspaceId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_Tag');
