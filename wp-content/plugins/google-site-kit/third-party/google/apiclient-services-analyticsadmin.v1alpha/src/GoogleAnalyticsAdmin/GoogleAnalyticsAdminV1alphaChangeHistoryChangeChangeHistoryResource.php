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

class GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $accountType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccount::class;
    protected $accountDataType = '';
    protected $adsenseLinkType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAdSenseLink::class;
    protected $adsenseLinkDataType = '';
    protected $attributionSettingsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAttributionSettings::class;
    protected $attributionSettingsDataType = '';
    protected $audienceType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudience::class;
    protected $audienceDataType = '';
    protected $bigqueryLinkType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaBigQueryLink::class;
    protected $bigqueryLinkDataType = '';
    protected $calculatedMetricType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCalculatedMetric::class;
    protected $calculatedMetricDataType = '';
    protected $channelGroupType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroup::class;
    protected $channelGroupDataType = '';
    protected $conversionEventType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaConversionEvent::class;
    protected $conversionEventDataType = '';
    protected $customDimensionType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCustomDimension::class;
    protected $customDimensionDataType = '';
    protected $customMetricType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCustomMetric::class;
    protected $customMetricDataType = '';
    protected $dataRedactionSettingsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDataRedactionSettings::class;
    protected $dataRedactionSettingsDataType = '';
    protected $dataRetentionSettingsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDataRetentionSettings::class;
    protected $dataRetentionSettingsDataType = '';
    protected $dataStreamType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDataStream::class;
    protected $dataStreamDataType = '';
    protected $displayVideo360AdvertiserLinkType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLink::class;
    protected $displayVideo360AdvertiserLinkDataType = '';
    protected $displayVideo360AdvertiserLinkProposalType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLinkProposal::class;
    protected $displayVideo360AdvertiserLinkProposalDataType = '';
    protected $enhancedMeasurementSettingsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEnhancedMeasurementSettings::class;
    protected $enhancedMeasurementSettingsDataType = '';
    protected $eventCreateRuleType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventCreateRule::class;
    protected $eventCreateRuleDataType = '';
    protected $expandedDataSetType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSet::class;
    protected $expandedDataSetDataType = '';
    protected $firebaseLinkType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaFirebaseLink::class;
    protected $firebaseLinkDataType = '';
    protected $googleAdsLinkType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaGoogleAdsLink::class;
    protected $googleAdsLinkDataType = '';
    protected $googleSignalsSettingsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaGoogleSignalsSettings::class;
    protected $googleSignalsSettingsDataType = '';
    protected $keyEventType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaKeyEvent::class;
    protected $keyEventDataType = '';
    protected $measurementProtocolSecretType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaMeasurementProtocolSecret::class;
    protected $measurementProtocolSecretDataType = '';
    protected $propertyType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty::class;
    protected $propertyDataType = '';
    protected $reportingDataAnnotationType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReportingDataAnnotation::class;
    protected $reportingDataAnnotationDataType = '';
    protected $reportingIdentitySettingsType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReportingIdentitySettings::class;
    protected $reportingIdentitySettingsDataType = '';
    protected $searchAds360LinkType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSearchAds360Link::class;
    protected $searchAds360LinkDataType = '';
    protected $skadnetworkConversionValueSchemaType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSKAdNetworkConversionValueSchema::class;
    protected $skadnetworkConversionValueSchemaDataType = '';
    protected $subpropertySyncConfigType = \Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertySyncConfig::class;
    protected $subpropertySyncConfigDataType = '';
    /**
     * A snapshot of an Account resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaAccount $account
     */
    public function setAccount(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAccount $account)
    {
        $this->account = $account;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAccount
     */
    public function getAccount()
    {
        return $this->account;
    }
    /**
     * A snapshot of an AdSenseLink resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaAdSenseLink $adsenseLink
     */
    public function setAdsenseLink(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAdSenseLink $adsenseLink)
    {
        $this->adsenseLink = $adsenseLink;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAdSenseLink
     */
    public function getAdsenseLink()
    {
        return $this->adsenseLink;
    }
    /**
     * A snapshot of AttributionSettings resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaAttributionSettings $attributionSettings
     */
    public function setAttributionSettings(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAttributionSettings $attributionSettings)
    {
        $this->attributionSettings = $attributionSettings;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAttributionSettings
     */
    public function getAttributionSettings()
    {
        return $this->attributionSettings;
    }
    /**
     * A snapshot of an Audience resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaAudience $audience
     */
    public function setAudience(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAudience $audience)
    {
        $this->audience = $audience;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaAudience
     */
    public function getAudience()
    {
        return $this->audience;
    }
    /**
     * A snapshot of a BigQuery link resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaBigQueryLink $bigqueryLink
     */
    public function setBigqueryLink(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaBigQueryLink $bigqueryLink)
    {
        $this->bigqueryLink = $bigqueryLink;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaBigQueryLink
     */
    public function getBigqueryLink()
    {
        return $this->bigqueryLink;
    }
    /**
     * A snapshot of a CalculatedMetric resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaCalculatedMetric $calculatedMetric
     */
    public function setCalculatedMetric(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCalculatedMetric $calculatedMetric)
    {
        $this->calculatedMetric = $calculatedMetric;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaCalculatedMetric
     */
    public function getCalculatedMetric()
    {
        return $this->calculatedMetric;
    }
    /**
     * A snapshot of a ChannelGroup resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaChannelGroup $channelGroup
     */
    public function setChannelGroup(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChannelGroup $channelGroup)
    {
        $this->channelGroup = $channelGroup;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaChannelGroup
     */
    public function getChannelGroup()
    {
        return $this->channelGroup;
    }
    /**
     * A snapshot of a ConversionEvent resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaConversionEvent $conversionEvent
     */
    public function setConversionEvent(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaConversionEvent $conversionEvent)
    {
        $this->conversionEvent = $conversionEvent;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaConversionEvent
     */
    public function getConversionEvent()
    {
        return $this->conversionEvent;
    }
    /**
     * A snapshot of a CustomDimension resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaCustomDimension $customDimension
     */
    public function setCustomDimension(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCustomDimension $customDimension)
    {
        $this->customDimension = $customDimension;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaCustomDimension
     */
    public function getCustomDimension()
    {
        return $this->customDimension;
    }
    /**
     * A snapshot of a CustomMetric resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaCustomMetric $customMetric
     */
    public function setCustomMetric(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaCustomMetric $customMetric)
    {
        $this->customMetric = $customMetric;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaCustomMetric
     */
    public function getCustomMetric()
    {
        return $this->customMetric;
    }
    /**
     * A snapshot of DataRedactionSettings resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaDataRedactionSettings $dataRedactionSettings
     */
    public function setDataRedactionSettings(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDataRedactionSettings $dataRedactionSettings)
    {
        $this->dataRedactionSettings = $dataRedactionSettings;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaDataRedactionSettings
     */
    public function getDataRedactionSettings()
    {
        return $this->dataRedactionSettings;
    }
    /**
     * A snapshot of a data retention settings resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaDataRetentionSettings $dataRetentionSettings
     */
    public function setDataRetentionSettings(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDataRetentionSettings $dataRetentionSettings)
    {
        $this->dataRetentionSettings = $dataRetentionSettings;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaDataRetentionSettings
     */
    public function getDataRetentionSettings()
    {
        return $this->dataRetentionSettings;
    }
    /**
     * A snapshot of a DataStream resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaDataStream $dataStream
     */
    public function setDataStream(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDataStream $dataStream)
    {
        $this->dataStream = $dataStream;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaDataStream
     */
    public function getDataStream()
    {
        return $this->dataStream;
    }
    /**
     * A snapshot of a DisplayVideo360AdvertiserLink resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLink $displayVideo360AdvertiserLink
     */
    public function setDisplayVideo360AdvertiserLink(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLink $displayVideo360AdvertiserLink)
    {
        $this->displayVideo360AdvertiserLink = $displayVideo360AdvertiserLink;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLink
     */
    public function getDisplayVideo360AdvertiserLink()
    {
        return $this->displayVideo360AdvertiserLink;
    }
    /**
     * A snapshot of a DisplayVideo360AdvertiserLinkProposal resource in change
     * history.
     *
     * @param GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLinkProposal $displayVideo360AdvertiserLinkProposal
     */
    public function setDisplayVideo360AdvertiserLinkProposal(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLinkProposal $displayVideo360AdvertiserLinkProposal)
    {
        $this->displayVideo360AdvertiserLinkProposal = $displayVideo360AdvertiserLinkProposal;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLinkProposal
     */
    public function getDisplayVideo360AdvertiserLinkProposal()
    {
        return $this->displayVideo360AdvertiserLinkProposal;
    }
    /**
     * A snapshot of EnhancedMeasurementSettings resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaEnhancedMeasurementSettings $enhancedMeasurementSettings
     */
    public function setEnhancedMeasurementSettings(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEnhancedMeasurementSettings $enhancedMeasurementSettings)
    {
        $this->enhancedMeasurementSettings = $enhancedMeasurementSettings;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaEnhancedMeasurementSettings
     */
    public function getEnhancedMeasurementSettings()
    {
        return $this->enhancedMeasurementSettings;
    }
    /**
     * A snapshot of an EventCreateRule resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaEventCreateRule $eventCreateRule
     */
    public function setEventCreateRule(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaEventCreateRule $eventCreateRule)
    {
        $this->eventCreateRule = $eventCreateRule;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaEventCreateRule
     */
    public function getEventCreateRule()
    {
        return $this->eventCreateRule;
    }
    /**
     * A snapshot of an ExpandedDataSet resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaExpandedDataSet $expandedDataSet
     */
    public function setExpandedDataSet(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaExpandedDataSet $expandedDataSet)
    {
        $this->expandedDataSet = $expandedDataSet;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaExpandedDataSet
     */
    public function getExpandedDataSet()
    {
        return $this->expandedDataSet;
    }
    /**
     * A snapshot of a FirebaseLink resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaFirebaseLink $firebaseLink
     */
    public function setFirebaseLink(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaFirebaseLink $firebaseLink)
    {
        $this->firebaseLink = $firebaseLink;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaFirebaseLink
     */
    public function getFirebaseLink()
    {
        return $this->firebaseLink;
    }
    /**
     * A snapshot of a GoogleAdsLink resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaGoogleAdsLink $googleAdsLink
     */
    public function setGoogleAdsLink(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaGoogleAdsLink $googleAdsLink)
    {
        $this->googleAdsLink = $googleAdsLink;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaGoogleAdsLink
     */
    public function getGoogleAdsLink()
    {
        return $this->googleAdsLink;
    }
    /**
     * A snapshot of a GoogleSignalsSettings resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaGoogleSignalsSettings $googleSignalsSettings
     */
    public function setGoogleSignalsSettings(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaGoogleSignalsSettings $googleSignalsSettings)
    {
        $this->googleSignalsSettings = $googleSignalsSettings;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaGoogleSignalsSettings
     */
    public function getGoogleSignalsSettings()
    {
        return $this->googleSignalsSettings;
    }
    /**
     * A snapshot of a KeyEvent resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaKeyEvent $keyEvent
     */
    public function setKeyEvent(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaKeyEvent $keyEvent)
    {
        $this->keyEvent = $keyEvent;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaKeyEvent
     */
    public function getKeyEvent()
    {
        return $this->keyEvent;
    }
    /**
     * A snapshot of a MeasurementProtocolSecret resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaMeasurementProtocolSecret $measurementProtocolSecret
     */
    public function setMeasurementProtocolSecret(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaMeasurementProtocolSecret $measurementProtocolSecret)
    {
        $this->measurementProtocolSecret = $measurementProtocolSecret;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaMeasurementProtocolSecret
     */
    public function getMeasurementProtocolSecret()
    {
        return $this->measurementProtocolSecret;
    }
    /**
     * A snapshot of a Property resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaProperty $property
     */
    public function setProperty(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaProperty $property)
    {
        $this->property = $property;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaProperty
     */
    public function getProperty()
    {
        return $this->property;
    }
    /**
     * A snapshot of a ReportingDataAnnotation resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaReportingDataAnnotation $reportingDataAnnotation
     */
    public function setReportingDataAnnotation(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReportingDataAnnotation $reportingDataAnnotation)
    {
        $this->reportingDataAnnotation = $reportingDataAnnotation;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaReportingDataAnnotation
     */
    public function getReportingDataAnnotation()
    {
        return $this->reportingDataAnnotation;
    }
    /**
     * A snapshot of a ReportingIdentitySettings resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaReportingIdentitySettings $reportingIdentitySettings
     */
    public function setReportingIdentitySettings(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaReportingIdentitySettings $reportingIdentitySettings)
    {
        $this->reportingIdentitySettings = $reportingIdentitySettings;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaReportingIdentitySettings
     */
    public function getReportingIdentitySettings()
    {
        return $this->reportingIdentitySettings;
    }
    /**
     * A snapshot of a SearchAds360Link resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaSearchAds360Link $searchAds360Link
     */
    public function setSearchAds360Link(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSearchAds360Link $searchAds360Link)
    {
        $this->searchAds360Link = $searchAds360Link;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSearchAds360Link
     */
    public function getSearchAds360Link()
    {
        return $this->searchAds360Link;
    }
    /**
     * A snapshot of SKAdNetworkConversionValueSchema resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaSKAdNetworkConversionValueSchema $skadnetworkConversionValueSchema
     */
    public function setSkadnetworkConversionValueSchema(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSKAdNetworkConversionValueSchema $skadnetworkConversionValueSchema)
    {
        $this->skadnetworkConversionValueSchema = $skadnetworkConversionValueSchema;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSKAdNetworkConversionValueSchema
     */
    public function getSkadnetworkConversionValueSchema()
    {
        return $this->skadnetworkConversionValueSchema;
    }
    /**
     * A snapshot of a SubpropertySyncConfig resource in change history.
     *
     * @param GoogleAnalyticsAdminV1alphaSubpropertySyncConfig $subpropertySyncConfig
     */
    public function setSubpropertySyncConfig(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSubpropertySyncConfig $subpropertySyncConfig)
    {
        $this->subpropertySyncConfig = $subpropertySyncConfig;
    }
    /**
     * @return GoogleAnalyticsAdminV1alphaSubpropertySyncConfig
     */
    public function getSubpropertySyncConfig()
    {
        return $this->subpropertySyncConfig;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaChangeHistoryChangeChangeHistoryResource');
