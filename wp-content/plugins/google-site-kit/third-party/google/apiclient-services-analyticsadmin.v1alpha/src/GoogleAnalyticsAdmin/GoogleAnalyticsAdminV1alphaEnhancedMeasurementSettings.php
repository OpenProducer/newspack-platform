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

class GoogleAnalyticsAdminV1alphaEnhancedMeasurementSettings extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * If enabled, capture a file download event each time a link is clicked with
     * a common document, compressed file, application, video, or audio extension.
     *
     * @var bool
     */
    public $fileDownloadsEnabled;
    /**
     * If enabled, capture a form interaction event each time a visitor interacts
     * with a form on your website. False by default.
     *
     * @var bool
     */
    public $formInteractionsEnabled;
    /**
     * Output only. Resource name of the Enhanced Measurement Settings. Format: pr
     * operties/{property_id}/dataStreams/{data_stream}/enhancedMeasurementSetting
     * s Example: "properties/1000/dataStreams/2000/enhancedMeasurementSettings"
     *
     * @var string
     */
    public $name;
    /**
     * If enabled, capture an outbound click event each time a visitor clicks a
     * link that leads them away from your domain.
     *
     * @var bool
     */
    public $outboundClicksEnabled;
    /**
     * If enabled, capture a page view event each time the website changes the
     * browser history state.
     *
     * @var bool
     */
    public $pageChangesEnabled;
    /**
     * If enabled, capture scroll events each time a visitor gets to the bottom of
     * a page.
     *
     * @var bool
     */
    public $scrollsEnabled;
    /**
     * Required. URL query parameters to interpret as site search parameters. Max
     * length is 1024 characters. Must not be empty.
     *
     * @var string
     */
    public $searchQueryParameter;
    /**
     * If enabled, capture a view search results event each time a visitor
     * performs a search on your site (based on a query parameter).
     *
     * @var bool
     */
    public $siteSearchEnabled;
    /**
     * Indicates whether Enhanced Measurement Settings will be used to
     * automatically measure interactions and content on this web stream. Changing
     * this value does not affect the settings themselves, but determines whether
     * they are respected.
     *
     * @var bool
     */
    public $streamEnabled;
    /**
     * Additional URL query parameters. Max length is 1024 characters.
     *
     * @var string
     */
    public $uriQueryParameter;
    /**
     * If enabled, capture video play, progress, and complete events as visitors
     * view embedded videos on your site.
     *
     * @var bool
     */
    public $videoEngagementEnabled;
    /**
     * If enabled, capture a file download event each time a link is clicked with
     * a common document, compressed file, application, video, or audio extension.
     *
     * @param bool $fileDownloadsEnabled
     */
    public function setFileDownloadsEnabled($fileDownloadsEnabled)
    {
        $this->fileDownloadsEnabled = $fileDownloadsEnabled;
    }
    /**
     * @return bool
     */
    public function getFileDownloadsEnabled()
    {
        return $this->fileDownloadsEnabled;
    }
    /**
     * If enabled, capture a form interaction event each time a visitor interacts
     * with a form on your website. False by default.
     *
     * @param bool $formInteractionsEnabled
     */
    public function setFormInteractionsEnabled($formInteractionsEnabled)
    {
        $this->formInteractionsEnabled = $formInteractionsEnabled;
    }
    /**
     * @return bool
     */
    public function getFormInteractionsEnabled()
    {
        return $this->formInteractionsEnabled;
    }
    /**
     * Output only. Resource name of the Enhanced Measurement Settings. Format: pr
     * operties/{property_id}/dataStreams/{data_stream}/enhancedMeasurementSetting
     * s Example: "properties/1000/dataStreams/2000/enhancedMeasurementSettings"
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
     * If enabled, capture an outbound click event each time a visitor clicks a
     * link that leads them away from your domain.
     *
     * @param bool $outboundClicksEnabled
     */
    public function setOutboundClicksEnabled($outboundClicksEnabled)
    {
        $this->outboundClicksEnabled = $outboundClicksEnabled;
    }
    /**
     * @return bool
     */
    public function getOutboundClicksEnabled()
    {
        return $this->outboundClicksEnabled;
    }
    /**
     * If enabled, capture a page view event each time the website changes the
     * browser history state.
     *
     * @param bool $pageChangesEnabled
     */
    public function setPageChangesEnabled($pageChangesEnabled)
    {
        $this->pageChangesEnabled = $pageChangesEnabled;
    }
    /**
     * @return bool
     */
    public function getPageChangesEnabled()
    {
        return $this->pageChangesEnabled;
    }
    /**
     * If enabled, capture scroll events each time a visitor gets to the bottom of
     * a page.
     *
     * @param bool $scrollsEnabled
     */
    public function setScrollsEnabled($scrollsEnabled)
    {
        $this->scrollsEnabled = $scrollsEnabled;
    }
    /**
     * @return bool
     */
    public function getScrollsEnabled()
    {
        return $this->scrollsEnabled;
    }
    /**
     * Required. URL query parameters to interpret as site search parameters. Max
     * length is 1024 characters. Must not be empty.
     *
     * @param string $searchQueryParameter
     */
    public function setSearchQueryParameter($searchQueryParameter)
    {
        $this->searchQueryParameter = $searchQueryParameter;
    }
    /**
     * @return string
     */
    public function getSearchQueryParameter()
    {
        return $this->searchQueryParameter;
    }
    /**
     * If enabled, capture a view search results event each time a visitor
     * performs a search on your site (based on a query parameter).
     *
     * @param bool $siteSearchEnabled
     */
    public function setSiteSearchEnabled($siteSearchEnabled)
    {
        $this->siteSearchEnabled = $siteSearchEnabled;
    }
    /**
     * @return bool
     */
    public function getSiteSearchEnabled()
    {
        return $this->siteSearchEnabled;
    }
    /**
     * Indicates whether Enhanced Measurement Settings will be used to
     * automatically measure interactions and content on this web stream. Changing
     * this value does not affect the settings themselves, but determines whether
     * they are respected.
     *
     * @param bool $streamEnabled
     */
    public function setStreamEnabled($streamEnabled)
    {
        $this->streamEnabled = $streamEnabled;
    }
    /**
     * @return bool
     */
    public function getStreamEnabled()
    {
        return $this->streamEnabled;
    }
    /**
     * Additional URL query parameters. Max length is 1024 characters.
     *
     * @param string $uriQueryParameter
     */
    public function setUriQueryParameter($uriQueryParameter)
    {
        $this->uriQueryParameter = $uriQueryParameter;
    }
    /**
     * @return string
     */
    public function getUriQueryParameter()
    {
        return $this->uriQueryParameter;
    }
    /**
     * If enabled, capture video play, progress, and complete events as visitors
     * view embedded videos on your site.
     *
     * @param bool $videoEngagementEnabled
     */
    public function setVideoEngagementEnabled($videoEngagementEnabled)
    {
        $this->videoEngagementEnabled = $videoEngagementEnabled;
    }
    /**
     * @return bool
     */
    public function getVideoEngagementEnabled()
    {
        return $this->videoEngagementEnabled;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEnhancedMeasurementSettings::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaEnhancedMeasurementSettings');
