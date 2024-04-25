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

class Entity extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $builtInVariableType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\BuiltInVariable::class;
    protected $builtInVariableDataType = '';
    /**
     * @var string
     */
    public $changeStatus;
    protected $clientType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Client::class;
    protected $clientDataType = '';
    protected $customTemplateType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\CustomTemplate::class;
    protected $customTemplateDataType = '';
    protected $folderType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Folder::class;
    protected $folderDataType = '';
    protected $gtagConfigType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\GtagConfig::class;
    protected $gtagConfigDataType = '';
    protected $tagType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag::class;
    protected $tagDataType = '';
    protected $transformationType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Transformation::class;
    protected $transformationDataType = '';
    protected $triggerType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Trigger::class;
    protected $triggerDataType = '';
    protected $variableType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Variable::class;
    protected $variableDataType = '';
    protected $zoneType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Zone::class;
    protected $zoneDataType = '';
    /**
     * @param BuiltInVariable
     */
    public function setBuiltInVariable(\Google\Site_Kit_Dependencies\Google\Service\TagManager\BuiltInVariable $builtInVariable)
    {
        $this->builtInVariable = $builtInVariable;
    }
    /**
     * @return BuiltInVariable
     */
    public function getBuiltInVariable()
    {
        return $this->builtInVariable;
    }
    /**
     * @param string
     */
    public function setChangeStatus($changeStatus)
    {
        $this->changeStatus = $changeStatus;
    }
    /**
     * @return string
     */
    public function getChangeStatus()
    {
        return $this->changeStatus;
    }
    /**
     * @param Client
     */
    public function setClient(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Client $client)
    {
        $this->client = $client;
    }
    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
    /**
     * @param CustomTemplate
     */
    public function setCustomTemplate(\Google\Site_Kit_Dependencies\Google\Service\TagManager\CustomTemplate $customTemplate)
    {
        $this->customTemplate = $customTemplate;
    }
    /**
     * @return CustomTemplate
     */
    public function getCustomTemplate()
    {
        return $this->customTemplate;
    }
    /**
     * @param Folder
     */
    public function setFolder(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Folder $folder)
    {
        $this->folder = $folder;
    }
    /**
     * @return Folder
     */
    public function getFolder()
    {
        return $this->folder;
    }
    /**
     * @param GtagConfig
     */
    public function setGtagConfig(\Google\Site_Kit_Dependencies\Google\Service\TagManager\GtagConfig $gtagConfig)
    {
        $this->gtagConfig = $gtagConfig;
    }
    /**
     * @return GtagConfig
     */
    public function getGtagConfig()
    {
        return $this->gtagConfig;
    }
    /**
     * @param Tag
     */
    public function setTag(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Tag $tag)
    {
        $this->tag = $tag;
    }
    /**
     * @return Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
    /**
     * @param Transformation
     */
    public function setTransformation(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Transformation $transformation)
    {
        $this->transformation = $transformation;
    }
    /**
     * @return Transformation
     */
    public function getTransformation()
    {
        return $this->transformation;
    }
    /**
     * @param Trigger
     */
    public function setTrigger(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Trigger $trigger)
    {
        $this->trigger = $trigger;
    }
    /**
     * @return Trigger
     */
    public function getTrigger()
    {
        return $this->trigger;
    }
    /**
     * @param Variable
     */
    public function setVariable(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Variable $variable)
    {
        $this->variable = $variable;
    }
    /**
     * @return Variable
     */
    public function getVariable()
    {
        return $this->variable;
    }
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
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Entity::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_Entity');
