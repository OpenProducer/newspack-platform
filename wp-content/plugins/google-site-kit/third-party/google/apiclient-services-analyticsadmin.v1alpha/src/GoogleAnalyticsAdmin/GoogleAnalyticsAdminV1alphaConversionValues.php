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
namespace Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha;

class GoogleAnalyticsAdminV1alphaConversionValues extends \Google\Site_Kit_Dependencies\Google\Collection
{
    /**
     * Coarse value not specified.
     */
    public const COARSE_VALUE_COARSE_VALUE_UNSPECIFIED = 'COARSE_VALUE_UNSPECIFIED';
    /**
     * Coarse value of low.
     */
    public const COARSE_VALUE_COARSE_VALUE_LOW = 'COARSE_VALUE_LOW';
    /**
     * Coarse value of medium.
     */
    public const COARSE_VALUE_COARSE_VALUE_MEDIUM = 'COARSE_VALUE_MEDIUM';
    /**
     * Coarse value of high.
     */
    public const COARSE_VALUE_COARSE_VALUE_HIGH = 'COARSE_VALUE_HIGH';
    protected $collection_key = 'eventMappings';
    /**
     * Required. A coarse grained conversion value. This value is not guaranteed
     * to be unique.
     *
     * @var string
     */
    public $coarseValue;
    /**
     * Display name of the SKAdNetwork conversion value. The max allowed display
     * name length is 50 UTF-16 code units.
     *
     * @var string
     */
    public $displayName;
    protected $eventMappingsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventMapping::class;
    protected $eventMappingsDataType = 'array';
    /**
     * The fine-grained conversion value. This is applicable only to the first
     * postback window. Its valid values are [0,63], both inclusive. It must be
     * set for postback window 1, and must not be set for postback window 2 & 3.
     * This value is not guaranteed to be unique. If the configuration for the
     * first postback window is re-used for second or third postback windows this
     * field has no effect.
     *
     * @var int
     */
    public $fineValue;
    /**
     * If true, the SDK should lock to this conversion value for the current
     * postback window.
     *
     * @var bool
     */
    public $lockEnabled;
    /**
     * Required. A coarse grained conversion value. This value is not guaranteed
     * to be unique.
     *
     * Accepted values: COARSE_VALUE_UNSPECIFIED, COARSE_VALUE_LOW,
     * COARSE_VALUE_MEDIUM, COARSE_VALUE_HIGH
     *
     * @param self::COARSE_VALUE_* $coarseValue
     */
    public function setCoarseValue($coarseValue)
    {
        $this->coarseValue = $coarseValue;
    }
    /**
     * @return self::COARSE_VALUE_*
     */
    public function getCoarseValue()
    {
        return $this->coarseValue;
    }
    /**
     * Display name of the SKAdNetwork conversion value. The max allowed display
     * name length is 50 UTF-16 code units.
     *
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }
    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * Event conditions that must be met for this Conversion Value to be achieved.
     * The conditions in this list are ANDed together. It must have minimum of 1
     * entry and maximum of 3 entries, if the postback window is enabled.
     *
     * @param GoogleAnalyticsAdminV1alphaEventMapping[] $eventMappings
     */
    public function setEventMappings($eventMappings)
    {
        $this->eventMappings = $eventMappings;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaEventMapping[]
     */
    public function getEventMappings()
    {
        return $this->eventMappings;
    }
    /**
     * The fine-grained conversion value. This is applicable only to the first
     * postback window. Its valid values are [0,63], both inclusive. It must be
     * set for postback window 1, and must not be set for postback window 2 & 3.
     * This value is not guaranteed to be unique. If the configuration for the
     * first postback window is re-used for second or third postback windows this
     * field has no effect.
     *
     * @param int $fineValue
     */
    public function setFineValue($fineValue)
    {
        $this->fineValue = $fineValue;
    }
    /**
     * @return int
     */
    public function getFineValue()
    {
        return $this->fineValue;
    }
    /**
     * If true, the SDK should lock to this conversion value for the current
     * postback window.
     *
     * @param bool $lockEnabled
     */
    public function setLockEnabled($lockEnabled)
    {
        $this->lockEnabled = $lockEnabled;
    }
    /**
     * @return bool
     */
    public function getLockEnabled()
    {
        return $this->lockEnabled;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaConversionValues::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaConversionValues');
