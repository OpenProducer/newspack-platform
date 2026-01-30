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

class GoogleAnalyticsAdminV1alphaDataRedactionSettings extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'queryParameterKeys';
    /**
     * If enabled, any event parameter or user property values that look like an
     * email will be redacted.
     *
     * @var bool
     */
    public $emailRedactionEnabled;
    /**
     * Output only. Name of this Data Redaction Settings resource. Format:
     * properties/{property_id}/dataStreams/{data_stream}/dataRedactionSettings
     * Example: "properties/1000/dataStreams/2000/dataRedactionSettings"
     *
     * @var string
     */
    public $name;
    /**
     * The query parameter keys to apply redaction logic to if present in the URL.
     * Query parameter matching is case-insensitive. Must contain at least one
     * element if query_parameter_replacement_enabled is true. Keys cannot contain
     * commas.
     *
     * @var string[]
     */
    public $queryParameterKeys;
    /**
     * Query Parameter redaction removes the key and value portions of a query
     * parameter if it is in the configured set of query parameters. If enabled,
     * URL query replacement logic will be run for the Stream. Any query
     * parameters defined in query_parameter_keys will be redacted.
     *
     * @var bool
     */
    public $queryParameterRedactionEnabled;
    /**
     * If enabled, any event parameter or user property values that look like an
     * email will be redacted.
     *
     * @param bool $emailRedactionEnabled
     */
    public function setEmailRedactionEnabled($emailRedactionEnabled)
    {
        $this->emailRedactionEnabled = $emailRedactionEnabled;
    }
    /**
     * @return bool
     */
    public function getEmailRedactionEnabled()
    {
        return $this->emailRedactionEnabled;
    }
    /**
     * Output only. Name of this Data Redaction Settings resource. Format:
     * properties/{property_id}/dataStreams/{data_stream}/dataRedactionSettings
     * Example: "properties/1000/dataStreams/2000/dataRedactionSettings"
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
     * The query parameter keys to apply redaction logic to if present in the URL.
     * Query parameter matching is case-insensitive. Must contain at least one
     * element if query_parameter_replacement_enabled is true. Keys cannot contain
     * commas.
     *
     * @param string[] $queryParameterKeys
     */
    public function setQueryParameterKeys($queryParameterKeys)
    {
        $this->queryParameterKeys = $queryParameterKeys;
    }
    /**
     * @return string[]
     */
    public function getQueryParameterKeys()
    {
        return $this->queryParameterKeys;
    }
    /**
     * Query Parameter redaction removes the key and value portions of a query
     * parameter if it is in the configured set of query parameters. If enabled,
     * URL query replacement logic will be run for the Stream. Any query
     * parameters defined in query_parameter_keys will be redacted.
     *
     * @param bool $queryParameterRedactionEnabled
     */
    public function setQueryParameterRedactionEnabled($queryParameterRedactionEnabled)
    {
        $this->queryParameterRedactionEnabled = $queryParameterRedactionEnabled;
    }
    /**
     * @return bool
     */
    public function getQueryParameterRedactionEnabled()
    {
        return $this->queryParameterRedactionEnabled;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDataRedactionSettings::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaDataRedactionSettings');
