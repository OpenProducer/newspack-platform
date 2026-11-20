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

class SurveyQuestionCatalog extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'questions';
    /**
     * Identifier. The resource name of the survey question catalog. Format: `orga
     * nizations/{organization}/publications/{publication}/surveyQuestionCatalog`.
     *
     * @var string
     */
    public $name;
    protected $questionsType = QuestionTemplate::class;
    protected $questionsDataType = 'array';
    /**
     * Identifier. The resource name of the survey question catalog. Format: `orga
     * nizations/{organization}/publications/{publication}/surveyQuestionCatalog`.
     *
     * @param string $name
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
     * Output only. The list of available predefined question templates.
     *
     * @param QuestionTemplate[] $questions
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
    }
    /**
     * @return QuestionTemplate[]
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SurveyQuestionCatalog::class, 'Google\Site_Kit_Dependencies\Google_Service_WebContentPublisher_SurveyQuestionCatalog');
