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

class MergeConflict extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $entityInBaseVersionType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Entity::class;
    protected $entityInBaseVersionDataType = '';
    protected $entityInWorkspaceType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Entity::class;
    protected $entityInWorkspaceDataType = '';
    /**
     * The base version entity (since the latest sync operation) that has
     * conflicting changes compared to the workspace. If this field is missing, it
     * means the workspace entity is deleted from the base version.
     *
     * @param Entity $entityInBaseVersion
     */
    public function setEntityInBaseVersion(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Entity $entityInBaseVersion)
    {
        $this->entityInBaseVersion = $entityInBaseVersion;
    }
    /**
     * @return Entity
     */
    public function getEntityInBaseVersion()
    {
        return $this->entityInBaseVersion;
    }
    /**
     * The workspace entity that has conflicting changes compared to the base
     * version. If an entity is deleted in a workspace, it will still appear with
     * a deleted change status.
     *
     * @param Entity $entityInWorkspace
     */
    public function setEntityInWorkspace(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Entity $entityInWorkspace)
    {
        $this->entityInWorkspace = $entityInWorkspace;
    }
    /**
     * @return Entity
     */
    public function getEntityInWorkspace()
    {
        return $this->entityInWorkspace;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\MergeConflict::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_MergeConflict');
