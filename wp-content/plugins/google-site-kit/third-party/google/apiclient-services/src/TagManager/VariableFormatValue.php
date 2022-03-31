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

class VariableFormatValue extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * @var string
     */
    public $caseConversionType;
    protected $convertFalseToValueType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $convertFalseToValueDataType = '';
    protected $convertNullToValueType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $convertNullToValueDataType = '';
    protected $convertTrueToValueType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $convertTrueToValueDataType = '';
    protected $convertUndefinedToValueType = \Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter::class;
    protected $convertUndefinedToValueDataType = '';
    /**
     * @param string
     */
    public function setCaseConversionType($caseConversionType)
    {
        $this->caseConversionType = $caseConversionType;
    }
    /**
     * @return string
     */
    public function getCaseConversionType()
    {
        return $this->caseConversionType;
    }
    /**
     * @param Parameter
     */
    public function setConvertFalseToValue(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $convertFalseToValue)
    {
        $this->convertFalseToValue = $convertFalseToValue;
    }
    /**
     * @return Parameter
     */
    public function getConvertFalseToValue()
    {
        return $this->convertFalseToValue;
    }
    /**
     * @param Parameter
     */
    public function setConvertNullToValue(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $convertNullToValue)
    {
        $this->convertNullToValue = $convertNullToValue;
    }
    /**
     * @return Parameter
     */
    public function getConvertNullToValue()
    {
        return $this->convertNullToValue;
    }
    /**
     * @param Parameter
     */
    public function setConvertTrueToValue(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $convertTrueToValue)
    {
        $this->convertTrueToValue = $convertTrueToValue;
    }
    /**
     * @return Parameter
     */
    public function getConvertTrueToValue()
    {
        return $this->convertTrueToValue;
    }
    /**
     * @param Parameter
     */
    public function setConvertUndefinedToValue(\Google\Site_Kit_Dependencies\Google\Service\TagManager\Parameter $convertUndefinedToValue)
    {
        $this->convertUndefinedToValue = $convertUndefinedToValue;
    }
    /**
     * @return Parameter
     */
    public function getConvertUndefinedToValue()
    {
        return $this->convertUndefinedToValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\TagManager\VariableFormatValue::class, 'Google\\Site_Kit_Dependencies\\Google_Service_TagManager_VariableFormatValue');
