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
namespace Google\Site_Kit_Dependencies\Google\Service\WebContentPublisher;

class PresetQuestion extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'answerIds';
    /**
     * Optional. Selected answer choice IDs from the question template. Required
     * (2-5 unique IDs) when the catalog question is `CONFIGURABLE_CHOICES`, and
     * must be omitted for `FIXED_CHOICES`.
     *
     * @var int[]
     */
    public $answerIds;
    /**
     * Required. ID of the question template from `SurveyQuestionCatalog`.
     *
     * @var int
     */
    public $questionId;
    /**
     * Optional. Selected answer choice IDs from the question template. Required
     * (2-5 unique IDs) when the catalog question is `CONFIGURABLE_CHOICES`, and
     * must be omitted for `FIXED_CHOICES`.
     *
     * @param int[] $answerIds
     */
    public function setAnswerIds($answerIds)
    {
        $this->answerIds = $answerIds;
    }
    /**
     * @return int[]
     */
    public function getAnswerIds()
    {
        return $this->answerIds;
    }
    /**
     * Required. ID of the question template from `SurveyQuestionCatalog`.
     *
     * @param int $questionId
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;
    }
    /**
     * @return int
     */
    public function getQuestionId()
    {
        return $this->questionId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PresetQuestion::class, 'Google\Site_Kit_Dependencies\Google_Service_WebContentPublisher_PresetQuestion');
