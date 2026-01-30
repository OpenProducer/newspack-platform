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

class GoogleAnalyticsAdminV1alphaSearchAds360Link extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Enables personalized advertising features with this integration. If this
     * field is not set on create, it will be defaulted to true.
     *
     * @var bool
     */
    public $adsPersonalizationEnabled;
    /**
     * Output only. The display name of the Search Ads 360 Advertiser. Allows
     * users to easily identify the linked resource.
     *
     * @var string
     */
    public $advertiserDisplayName;
    /**
     * Immutable. This field represents the Advertiser ID of the Search Ads 360
     * Advertiser. that has been linked.
     *
     * @var string
     */
    public $advertiserId;
    /**
     * Immutable. Enables the import of campaign data from Search Ads 360 into the
     * Google Analytics property. After link creation, this can only be updated
     * from the Search Ads 360 product. If this field is not set on create, it
     * will be defaulted to true.
     *
     * @var bool
     */
    public $campaignDataSharingEnabled;
    /**
     * Immutable. Enables the import of cost data from Search Ads 360 to the
     * Google Analytics property. This can only be enabled if
     * campaign_data_sharing_enabled is enabled. After link creation, this can
     * only be updated from the Search Ads 360 product. If this field is not set
     * on create, it will be defaulted to true.
     *
     * @var bool
     */
    public $costDataSharingEnabled;
    /**
     * Output only. The resource name for this SearchAds360Link resource. Format:
     * properties/{propertyId}/searchAds360Links/{linkId} Note: linkId is not the
     * Search Ads 360 advertiser ID
     *
     * @var string
     */
    public $name;
    /**
     * Enables export of site stats with this integration. If this field is not
     * set on create, it will be defaulted to true.
     *
     * @var bool
     */
    public $siteStatsSharingEnabled;
    /**
     * Enables personalized advertising features with this integration. If this
     * field is not set on create, it will be defaulted to true.
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
     * Output only. The display name of the Search Ads 360 Advertiser. Allows
     * users to easily identify the linked resource.
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
     * Immutable. This field represents the Advertiser ID of the Search Ads 360
     * Advertiser. that has been linked.
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
     * Immutable. Enables the import of campaign data from Search Ads 360 into the
     * Google Analytics property. After link creation, this can only be updated
     * from the Search Ads 360 product. If this field is not set on create, it
     * will be defaulted to true.
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
     * Immutable. Enables the import of cost data from Search Ads 360 to the
     * Google Analytics property. This can only be enabled if
     * campaign_data_sharing_enabled is enabled. After link creation, this can
     * only be updated from the Search Ads 360 product. If this field is not set
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
     * Output only. The resource name for this SearchAds360Link resource. Format:
     * properties/{propertyId}/searchAds360Links/{linkId} Note: linkId is not the
     * Search Ads 360 advertiser ID
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
     * Enables export of site stats with this integration. If this field is not
     * set on create, it will be defaulted to true.
     *
     * @param bool $siteStatsSharingEnabled
     */
    public function setSiteStatsSharingEnabled($siteStatsSharingEnabled)
    {
        $this->siteStatsSharingEnabled = $siteStatsSharingEnabled;
    }
    /**
     * @return bool
     */
    public function getSiteStatsSharingEnabled()
    {
        return $this->siteStatsSharingEnabled;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaSearchAds360Link::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaSearchAds360Link');
