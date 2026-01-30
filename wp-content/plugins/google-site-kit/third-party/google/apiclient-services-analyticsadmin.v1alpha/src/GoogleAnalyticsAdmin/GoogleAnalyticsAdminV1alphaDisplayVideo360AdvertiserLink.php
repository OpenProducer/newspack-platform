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

class GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLink extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Enables personalized advertising features with this integration. If this
     * field is not set on create/update, it will be defaulted to true.
     *
     * @var bool
     */
    public $adsPersonalizationEnabled;
    /**
     * Output only. The display name of the Display & Video 360 Advertiser.
     *
     * @var string
     */
    public $advertiserDisplayName;
    /**
     * Immutable. The Display & Video 360 Advertiser's advertiser ID.
     *
     * @var string
     */
    public $advertiserId;
    /**
     * Immutable. Enables the import of campaign data from Display & Video 360
     * into the Google Analytics property. After link creation, this can only be
     * updated from the Display & Video 360 product. If this field is not set on
     * create, it will be defaulted to true.
     *
     * @var bool
     */
    public $campaignDataSharingEnabled;
    /**
     * Immutable. Enables the import of cost data from Display & Video 360 into
     * the Google Analytics property. This can only be enabled if
     * `campaign_data_sharing_enabled` is true. After link creation, this can only
     * be updated from the Display & Video 360 product. If this field is not set
     * on create, it will be defaulted to true.
     *
     * @var bool
     */
    public $costDataSharingEnabled;
    /**
     * Output only. The resource name for this DisplayVideo360AdvertiserLink
     * resource. Format:
     * properties/{propertyId}/displayVideo360AdvertiserLinks/{linkId} Note:
     * linkId is not the Display & Video 360 Advertiser ID
     *
     * @var string
     */
    public $name;
    /**
     * Enables personalized advertising features with this integration. If this
     * field is not set on create/update, it will be defaulted to true.
     *
     * @param bool $adsPersonalizationEnabled
     */
    public function setAdsPersonalizationEnabled($adsPersonalizationEnabled)
    {
        $this->adsPersonalizationEnabled = $adsPersonalizationEnabled;
    }
    /**
     * @return bool
     */
    public function getAdsPersonalizationEnabled()
    {
        return $this->adsPersonalizationEnabled;
    }
    /**
     * Output only. The display name of the Display & Video 360 Advertiser.
     *
     * @param string $advertiserDisplayName
     */
    public function setAdvertiserDisplayName($advertiserDisplayName)
    {
        $this->advertiserDisplayName = $advertiserDisplayName;
    }
    /**
     * @return string
     */
    public function getAdvertiserDisplayName()
    {
        return $this->advertiserDisplayName;
    }
    /**
     * Immutable. The Display & Video 360 Advertiser's advertiser ID.
     *
     * @param string $advertiserId
     */
    public function setAdvertiserId($advertiserId)
    {
        $this->advertiserId = $advertiserId;
    }
    /**
     * @return string
     */
    public function getAdvertiserId()
    {
        return $this->advertiserId;
    }
    /**
     * Immutable. Enables the import of campaign data from Display & Video 360
     * into the Google Analytics property. After link creation, this can only be
     * updated from the Display & Video 360 product. If this field is not set on
     * create, it will be defaulted to true.
     *
     * @param bool $campaignDataSharingEnabled
     */
    public function setCampaignDataSharingEnabled($campaignDataSharingEnabled)
    {
        $this->campaignDataSharingEnabled = $campaignDataSharingEnabled;
    }
    /**
     * @return bool
     */
    public function getCampaignDataSharingEnabled()
    {
        return $this->campaignDataSharingEnabled;
    }
    /**
     * Immutable. Enables the import of cost data from Display & Video 360 into
     * the Google Analytics property. This can only be enabled if
     * `campaign_data_sharing_enabled` is true. After link creation, this can only
     * be updated from the Display & Video 360 product. If this field is not set
     * on create, it will be defaulted to true.
     *
     * @param bool $costDataSharingEnabled
     */
    public function setCostDataSharingEnabled($costDataSharingEnabled)
    {
        $this->costDataSharingEnabled = $costDataSharingEnabled;
    }
    /**
     * @return bool
     */
    public function getCostDataSharingEnabled()
    {
        return $this->costDataSharingEnabled;
    }
    /**
     * Output only. The resource name for this DisplayVideo360AdvertiserLink
     * resource. Format:
     * properties/{propertyId}/displayVideo360AdvertiserLinks/{linkId} Note:
     * linkId is not the Display & Video 360 Advertiser ID
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
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLink::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaDisplayVideo360AdvertiserLink');
