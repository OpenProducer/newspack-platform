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

class Trigger extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'parameter';
    /**
     * @var string
     */
    public $accountId;
    protected $autoEventFilterType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Condition::class;
    protected $autoEventFilterDataType = 'array';
    protected $checkValidationType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $checkValidationDataType = '';
    /**
     * @var string
     */
    public $containerId;
    protected $continuousTimeMinMillisecondsType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $continuousTimeMinMillisecondsDataType = '';
    protected $customEventFilterType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Condition::class;
    protected $customEventFilterDataType = 'array';
    protected $eventNameType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $eventNameDataType = '';
    protected $filterType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Condition::class;
    protected $filterDataType = 'array';
    /**
     * @var string
     */
    public $fingerprint;
    protected $horizontalScrollPercentageListType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $horizontalScrollPercentageListDataType = '';
    protected $intervalType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $intervalDataType = '';
    protected $intervalSecondsType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $intervalSecondsDataType = '';
    protected $limitType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $limitDataType = '';
    protected $maxTimerLengthSecondsType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $maxTimerLengthSecondsDataType = '';
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $notes;
    protected $parameterType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $parameterDataType = 'array';
    /**
     * @var string
     */
    public $parentFolderId;
    /**
     * @var string
     */
    public $path;
    protected $selectorType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $selectorDataType = '';
    /**
     * @var string
     */
    public $tagManagerUrl;
    protected $totalTimeMinMillisecondsType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $totalTimeMinMillisecondsDataType = '';
    /**
     * @var string
     */
    public $triggerId;
    /**
     * @var string
     */
    public $type;
    protected $uniqueTriggerIdType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $uniqueTriggerIdDataType = '';
    protected $verticalScrollPercentageListType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $verticalScrollPercentageListDataType = '';
    protected $visibilitySelectorType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $visibilitySelectorDataType = '';
    protected $visiblePercentageMaxType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $visiblePercentageMaxDataType = '';
    protected $visiblePercentageMinType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $visiblePercentageMinDataType = '';
    protected $waitForTagsType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $waitForTagsDataType = '';
    protected $waitForTagsTimeoutType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $waitForTagsTimeoutDataType = '';
    /**
     * @var string
     */
    public $workspaceId;
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
     * @param Condition[]
     */
    public function setAutoEventFilter($autoEventFilter)
    {
        $this->autoEventFilter = $autoEventFilter;
    }
    /**
     * @return Condition[]
     */
    public function getAutoEventFilter()
    {
        return $this->autoEventFilter;
    }
    /**
     * @param Parameter
     */
    public function setCheckValidation(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $checkValidation)
    {
        $this->checkValidation = $checkValidation;
    }
    /**
     * @return Parameter
     */
    public function getCheckValidation()
    {
        return $this->checkValidation;
    }
    /**
     * @param string
     */
    public function setContainerId($containerId)
    {
        $this->containerId = $containerId;
    }
    /**
     * @return string
     */
    public function getContainerId()
    {
        return $this->containerId;
    }
    /**
     * @param Parameter
     */
    public function setContinuousTimeMinMilliseconds(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $continuousTimeMinMilliseconds)
    {
        $this->continuousTimeMinMilliseconds = $continuousTimeMinMilliseconds;
    }
    /**
     * @return Parameter
     */
    public function getContinuousTimeMinMilliseconds()
    {
        return $this->continuousTimeMinMilliseconds;
    }
    /**
     * @param Condition[]
     */
    public function setCustomEventFilter($customEventFilter)
    {
        $this->customEventFilter = $customEventFilter;
    }
    /**
     * @return Condition[]
     */
    public function getCustomEventFilter()
    {
        return $this->customEventFilter;
    }
    /**
     * @param Parameter
     */
    public function setEventName(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $eventName)
    {
        $this->eventName = $eventName;
    }
    /**
     * @return Parameter
     */
    public function getEventName()
    {
        return $this->eventName;
    }
    /**
     * @param Condition[]
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }
    /**
     * @return Condition[]
     */
    public function getFilter()
    {
        return $this->filter;
    }
    /**
     * @param string
     */
    public function setFingerprint($fingerprint)
    {
        $this->fingerprint = $fingerprint;
    }
    /**
     * @return string
     */
    public function getFingerprint()
    {
        return $this->fingerprint;
    }
    /**
     * @param Parameter
     */
    public function setHorizontalScrollPercentageList(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $horizontalScrollPercentageList)
    {
        $this->horizontalScrollPercentageList = $horizontalScrollPercentageList;
    }
    /**
     * @return Parameter
     */
    public function getHorizontalScrollPercentageList()
    {
        return $this->horizontalScrollPercentageList;
    }
    /**
     * @param Parameter
     */
    public function setInterval(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $interval)
    {
        $this->interval = $interval;
    }
    /**
     * @return Parameter
     */
    public function getInterval()
    {
        return $this->interval;
    }
    /**
     * @param Parameter
     */
    public function setIntervalSeconds(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $intervalSeconds)
    {
        $this->intervalSeconds = $intervalSeconds;
    }
    /**
     * @return Parameter
     */
    public function getIntervalSeconds()
    {
        return $this->intervalSeconds;
    }
    /**
     * @param Parameter
     */
    public function setLimit(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $limit)
    {
        $this->limit = $limit;
    }
    /**
     * @return Parameter
     */
    public function getLimit()
    {
        return $this->limit;
    }
    /**
     * @param Parameter
     */
    public function setMaxTimerLengthSeconds(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $maxTimerLengthSeconds)
    {
        $this->maxTimerLengthSeconds = $maxTimerLengthSeconds;
    }
    /**
     * @return Parameter
     */
    public function getMaxTimerLengthSeconds()
    {
        return $this->maxTimerLengthSeconds;
    }
    /**
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }
    /**
     * @return string
     */
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
    /**
     * @param string
     */
    public function setParentFolderId($parentFolderId)
    {
        $this->parentFolderId = $parentFolderId;
    }
    /**
     * @return string
     */
    public function getParentFolderId()
    {
        return $this->parentFolderId;
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
    /**
     * @param Parameter
     */
    public function setSelector(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $selector)
    {
        $this->selector = $selector;
    }
    /**
     * @return Parameter
     */
    public function getSelector()
    {
        return $this->selector;
    }
    /**
     * @param string
     */
    public function setTagManagerUrl($tagManagerUrl)
    {
        $this->tagManagerUrl = $tagManagerUrl;
    }
    /**
     * @return string
     */
    public function getTagManagerUrl()
    {
        return $this->tagManagerUrl;
    }
    /**
     * @param Parameter
     */
    public function setTotalTimeMinMilliseconds(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $totalTimeMinMilliseconds)
    {
        $this->totalTimeMinMilliseconds = $totalTimeMinMilliseconds;
    }
    /**
     * @return Parameter
     */
    public function getTotalTimeMinMilliseconds()
    {
        return $this->totalTimeMinMilliseconds;
    }
    /**
     * @param string
     */
    public function setTriggerId($triggerId)
    {
        $this->triggerId = $triggerId;
    }
    /**
     * @return string
     */
    public function getTriggerId()
    {
        return $this->triggerId;
    }
    /**
     * @param string
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * @param Parameter
     */
    public function setUniqueTriggerId(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $uniqueTriggerId)
    {
        $this->uniqueTriggerId = $uniqueTriggerId;
    }
    /**
     * @return Parameter
     */
    public function getUniqueTriggerId()
    {
        return $this->uniqueTriggerId;
    }
    /**
     * @param Parameter
     */
    public function setVerticalScrollPercentageList(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $verticalScrollPercentageList)
    {
        $this->verticalScrollPercentageList = $verticalScrollPercentageList;
    }
    /**
     * @return Parameter
     */
    public function getVerticalScrollPercentageList()
    {
        return $this->verticalScrollPercentageList;
    }
    /**
     * @param Parameter
     */
    public function setVisibilitySelector(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $visibilitySelector)
    {
        $this->visibilitySelector = $visibilitySelector;
    }
    /**
     * @return Parameter
     */
    public function getVisibilitySelector()
    {
        return $this->visibilitySelector;
    }
    /**
     * @param Parameter
     */
    public function setVisiblePercentageMax(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $visiblePercentageMax)
    {
        $this->visiblePercentageMax = $visiblePercentageMax;
    }
    /**
     * @return Parameter
     */
    public function getVisiblePercentageMax()
    {
        return $this->visiblePercentageMax;
    }
    /**
     * @param Parameter
     */
    public function setVisiblePercentageMin(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $visiblePercentageMin)
    {
        $this->visiblePercentageMin = $visiblePercentageMin;
    }
    /**
     * @return Parameter
     */
    public function getVisiblePercentageMin()
    {
        return $this->visiblePercentageMin;
    }
    /**
     * @param Parameter
     */
    public function setWaitForTags(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $waitForTags)
    {
        $this->waitForTags = $waitForTags;
    }
    /**
     * @return Parameter
     */
    public function getWaitForTags()
    {
        return $this->waitForTags;
    }
    /**
     * @param Parameter
     */
    public function setWaitForTagsTimeout(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $waitForTagsTimeout)
    {
        $this->waitForTagsTimeout = $waitForTagsTimeout;
    }
    /**
     * @return Parameter
     */
    public function getWaitForTagsTimeout()
    {
        return $this->waitForTagsTimeout;
    }
    /**
     * @param string
     */
    public function setWorkspaceId($workspaceId)
    {
        $this->workspaceId = $workspaceId;
    }
    /**
     * @return string
     */
    public function getWorkspaceId()
    {
        return $this->workspaceId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Trigger::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_Trigger');
