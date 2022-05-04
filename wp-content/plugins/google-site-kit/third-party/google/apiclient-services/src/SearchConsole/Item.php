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
namespace Google\Site_Kit_Dependencies\Google\Service\SearchConsole;

class Item extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'issues';
    protected $issuesType = \Google\Site_Kit_Dependencies\Google\Service\SearchConsole\RichResultsIssue::class;
    protected $issuesDataType = 'array';
    /**
     * @var string
     */
    public $name;
    /**
     * @param RichResultsIssue[]
     */
    public function setIssues($issues)
    {
        $this->issues = $issues;
    }
    /**
     * @return RichResultsIssue[]
     */
    public function getIssues()
    {
        return $this->issues;
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
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\SearchConsole\Item::class, 'Google\\Site_Kit_Dependencies\\Google_Service_SearchConsole_Item');
