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

class GoogleAnalyticsAdminV1alphaCalculatedMetric extends \Google\Site_Kit_Dependencies\Google\Collection
{
    /**
     * MetricUnit unspecified or missing.
     */
    public const METRIC_UNIT_METRIC_UNIT_UNSPECIFIED = 'METRIC_UNIT_UNSPECIFIED';
    /**
     * This metric uses default units.
     */
    public const METRIC_UNIT_STANDARD = 'STANDARD';
    /**
     * This metric measures a currency.
     */
    public const METRIC_UNIT_CURRENCY = 'CURRENCY';
    /**
     * This metric measures feet.
     */
    public const METRIC_UNIT_FEET = 'FEET';
    /**
     * This metric measures miles.
     */
    public const METRIC_UNIT_MILES = 'MILES';
    /**
     * This metric measures meters.
     */
    public const METRIC_UNIT_METERS = 'METERS';
    /**
     * This metric measures kilometers.
     */
    public const METRIC_UNIT_KILOMETERS = 'KILOMETERS';
    /**
     * This metric measures milliseconds.
     */
    public const METRIC_UNIT_MILLISECONDS = 'MILLISECONDS';
    /**
     * This metric measures seconds.
     */
    public const METRIC_UNIT_SECONDS = 'SECONDS';
    /**
     * This metric measures minutes.
     */
    public const METRIC_UNIT_MINUTES = 'MINUTES';
    /**
     * This metric measures hours.
     */
    public const METRIC_UNIT_HOURS = 'HOURS';
    protected $collection_key = 'restrictedMetricType';
    /**
     * Output only. The ID to use for the calculated metric. In the UI, this is
     * referred to as the "API name." The calculated_metric_id is used when
     * referencing this calculated metric from external APIs. For example,
     * "calcMetric:{calculated_metric_id}".
     *
     * @var string
     */
    public $calculatedMetricId;
    /**
     * Optional. Description for this calculated metric. Max length of 4096
     * characters.
     *
     * @var string
     */
    public $description;
    /**
     * Required. Display name for this calculated metric as shown in the Google
     * Analytics UI. Max length 82 characters.
     *
     * @var string
     */
    public $displayName;
    /**
     * Required. The calculated metric's definition. Maximum number of unique
     * referenced custom metrics is 5. Formulas supports the following operations:
     * + (addition), - (subtraction), - (negative), * (multiplication), /
     * (division), () (parenthesis). Any valid real numbers are acceptable that
     * fit in a Long (64bit integer) or a Double (64 bit floating point number).
     * Example formula: "( customEvent:parameter_name + cartPurchaseQuantity ) /
     * 2.0"
     *
     * @var string
     */
    public $formula;
    /**
     * Output only. If true, this calculated metric has a invalid metric
     * reference. Anything using a calculated metric with invalid_metric_reference
     * set to true may fail, produce warnings, or produce unexpected results.
     *
     * @var bool
     */
    public $invalidMetricReference;
    /**
     * Required. The type for the calculated metric's value.
     *
     * @var string
     */
    public $metricUnit;
    /**
     * Output only. Resource name for this CalculatedMetric. Format:
     * 'properties/{property_id}/calculatedMetrics/{calculated_metric_id}'
     *
     * @var string
     */
    public $name;
    /**
     * Output only. Types of restricted data that this metric contains.
     *
     * @var string[]
     */
    public $restrictedMetricType;
    /**
     * Output only. The ID to use for the calculated metric. In the UI, this is
     * referred to as the "API name." The calculated_metric_id is used when
     * referencing this calculated metric from external APIs. For example,
     * "calcMetric:{calculated_metric_id}".
     *
     * @param string $calculatedMetricId
     */
    public function setCalculatedMetricId($calculatedMetricId)
    {
        $this->calculatedMetricId = $calculatedMetricId;
    }
    /**
     * @return string
     */
    public function getCalculatedMetricId()
    {
        return $this->calculatedMetricId;
    }
    /**
     * Optional. Description for this calculated metric. Max length of 4096
     * characters.
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Required. Display name for this calculated metric as shown in the Google
     * Analytics UI. Max length 82 characters.
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
     * Required. The calculated metric's definition. Maximum number of unique
     * referenced custom metrics is 5. Formulas supports the following operations:
     * + (addition), - (subtraction), - (negative), * (multiplication), /
     * (division), () (parenthesis). Any valid real numbers are acceptable that
     * fit in a Long (64bit integer) or a Double (64 bit floating point number).
     * Example formula: "( customEvent:parameter_name + cartPurchaseQuantity ) /
     * 2.0"
     *
     * @param string $formula
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;
    }
    /**
     * @return string
     */
    public function getFormula()
    {
        return $this->formula;
    }
    /**
     * Output only. If true, this calculated metric has a invalid metric
     * reference. Anything using a calculated metric with invalid_metric_reference
     * set to true may fail, produce warnings, or produce unexpected results.
     *
     * @param bool $invalidMetricReference
     */
    public function setInvalidMetricReference($invalidMetricReference)
    {
        $this->invalidMetricReference = $invalidMetricReference;
    }
    /**
     * @return bool
     */
    public function getInvalidMetricReference()
    {
        return $this->invalidMetricReference;
    }
    /**
     * Required. The type for the calculated metric's value.
     *
     * Accepted values: METRIC_UNIT_UNSPECIFIED, STANDARD, CURRENCY, FEET, MILES,
     * METERS, KILOMETERS, MILLISECONDS, SECONDS, MINUTES, HOURS
     *
     * @param self::METRIC_UNIT_* $metricUnit
     */
    public function setMetricUnit($metricUnit)
    {
        $this->metricUnit = $metricUnit;
    }
    /**
     * @return self::METRIC_UNIT_*
     */
    public function getMetricUnit()
    {
        return $this->metricUnit;
    }
    /**
     * Output only. Resource name for this CalculatedMetric. Format:
     * 'properties/{property_id}/calculatedMetrics/{calculated_metric_id}'
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
     * Output only. Types of restricted data that this metric contains.
     *
     * @param string[] $restrictedMetricType
     */
    public function setRestrictedMetricType($restrictedMetricType)
    {
        $this->restrictedMetricType = $restrictedMetricType;
    }
    /**
     * @return string[]
     */
    public function getRestrictedMetricType()
    {
        return $this->restrictedMetricType;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCalculatedMetric::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaCalculatedMetric');
