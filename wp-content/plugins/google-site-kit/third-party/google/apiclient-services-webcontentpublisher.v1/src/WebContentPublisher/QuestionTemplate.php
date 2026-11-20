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

class QuestionTemplate extends \Google\Site_Kit_Dependencies\Google\Collection
{
    /**
     * Unspecified question category.
     */
    public const CATEGORY_QUESTION_CATEGORY_UNSPECIFIED = 'QUESTION_CATEGORY_UNSPECIFIED';
    /**
     * Interest.
     */
    public const CATEGORY_INTEREST = 'INTEREST';
    /**
     * Demographic | Education & Occupation | Education.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EDUCATION = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EDUCATION';
    /**
     * Demographic | Age Range.
     */
    public const CATEGORY_DEMOGRAPHIC__AGE_RANGE = 'DEMOGRAPHIC__AGE_RANGE';
    /**
     * Purchase Intent.
     */
    public const CATEGORY_PURCHASE_INTENT = 'PURCHASE_INTENT';
    /**
     * Demographic | Household Data | Household Income (USD).
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__HOUSEHOLD_INCOME_USD = 'DEMOGRAPHIC__HOUSEHOLD_DATA__HOUSEHOLD_INCOME_USD';
    /**
     * Demographic | Household Data | Number of Adults.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__NUMBER_OF_ADULTS = 'DEMOGRAPHIC__HOUSEHOLD_DATA__NUMBER_OF_ADULTS';
    /**
     * Demographic | Household Data | Number of Children.
     */
    public const CATEGORY_DEMOGRAPHIC__HOUSEHOLD_DATA__NUMBER_OF_CHILDREN = 'DEMOGRAPHIC__HOUSEHOLD_DATA__NUMBER_OF_CHILDREN';
    /**
     * Demographic | Education & Occupation | Employment Status.
     */
    public const CATEGORY_DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EMPLOYMENT_STATUS = 'DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EMPLOYMENT_STATUS';
    /**
     * Digital Subscription Benefits.
     */
    public const CATEGORY_DIGITAL_SUBSCRIPTION_BENEFITS = 'DIGITAL_SUBSCRIPTION_BENEFITS';
    /**
     * Unspecified question configuration type.
     */
    public const CONFIGURATION_TYPE_QUESTION_CONFIGURATION_TYPE_UNSPECIFIED = 'QUESTION_CONFIGURATION_TYPE_UNSPECIFIED';
    /**
     * Fixed set of answer choices for this question. Publishers are not able to
     * select their own.
     */
    public const CONFIGURATION_TYPE_FIXED_CHOICES = 'FIXED_CHOICES';
    /**
     * Publishers are able to select answer choices from `AnswerChoiceTemplate`
     * for the question.
     */
    public const CONFIGURATION_TYPE_CONFIGURABLE_CHOICES = 'CONFIGURABLE_CHOICES';
    protected $collection_key = 'answerChoices';
    protected $answerChoicesType = AnswerChoiceTemplate::class;
    protected $answerChoicesDataType = 'array';
    /**
     * Output only. Category of the question based on the IAB Audience Taxonomy.
     *
     * @var string
     */
    public $category;
    /**
     * Output only. Text label used for aggregating responses by category in
     * analytics.
     *
     * @var string
     */
    public $categoryLabel;
    /**
     * Output only. The type of answer choice configuration for this question.
     *
     * @var string
     */
    public $configurationType;
    /**
     * Output only. Localized question text displayed to readers.
     *
     * @var string
     */
    public $displayText;
    /**
     * Output only. Unique identifier of the question template.
     *
     * @var int
     */
    public $id;
    /**
     * Output only. Possible answer choices for this question.
     *
     * @param AnswerChoiceTemplate[] $answerChoices
     */
    public function setAnswerChoices($answerChoices)
    {
        $this->answerChoices = $answerChoices;
    }
    /**
     * @return AnswerChoiceTemplate[]
     */
    public function getAnswerChoices()
    {
        return $this->answerChoices;
    }
    /**
     * Output only. Category of the question based on the IAB Audience Taxonomy.
     *
     * Accepted values: QUESTION_CATEGORY_UNSPECIFIED, INTEREST,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EDUCATION, DEMOGRAPHIC__AGE_RANGE,
     * PURCHASE_INTENT, DEMOGRAPHIC__HOUSEHOLD_DATA__HOUSEHOLD_INCOME_USD,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__NUMBER_OF_ADULTS,
     * DEMOGRAPHIC__HOUSEHOLD_DATA__NUMBER_OF_CHILDREN,
     * DEMOGRAPHIC__EDUCATION_AND_OCCUPATION__EMPLOYMENT_STATUS,
     * DIGITAL_SUBSCRIPTION_BENEFITS
     *
     * @param self::CATEGORY_* $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }
    /**
     * @return self::CATEGORY_*
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * Output only. Text label used for aggregating responses by category in
     * analytics.
     *
     * @param string $categoryLabel
     */
    public function setCategoryLabel($categoryLabel)
    {
        $this->categoryLabel = $categoryLabel;
    }
    /**
     * @return string
     */
    public function getCategoryLabel()
    {
        return $this->categoryLabel;
    }
    /**
     * Output only. The type of answer choice configuration for this question.
     *
     * Accepted values: QUESTION_CONFIGURATION_TYPE_UNSPECIFIED, FIXED_CHOICES,
     * CONFIGURABLE_CHOICES
     *
     * @param self::CONFIGURATION_TYPE_* $configurationType
     */
    public function setConfigurationType($configurationType)
    {
        $this->configurationType = $configurationType;
    }
    /**
     * @return self::CONFIGURATION_TYPE_*
     */
    public function getConfigurationType()
    {
        return $this->configurationType;
    }
    /**
     * Output only. Localized question text displayed to readers.
     *
     * @param string $displayText
     */
    public function setDisplayText($displayText)
    {
        $this->displayText = $displayText;
    }
    /**
     * @return string
     */
    public function getDisplayText()
    {
        return $this->displayText;
    }
    /**
     * Output only. Unique identifier of the question template.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QuestionTemplate::class, 'Google\Site_Kit_Dependencies\Google_Service_WebContentPublisher_QuestionTemplate');
