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

class GoogleAnalyticsAdminV1alphaAttributionSettings extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Lookback window size unspecified.
     */
    public const ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_UNSPECIFIED = 'ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_UNSPECIFIED';
    /**
     * 7-day lookback window.
     */
    public const ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_7_DAYS = 'ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_7_DAYS';
    /**
     * 30-day lookback window.
     */
    public const ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_30_DAYS = 'ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_30_DAYS';
    /**
     * Default value. This value is unused.
     */
    public const ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_UNSPECIFIED = 'ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_UNSPECIFIED';
    /**
     * No data export scope selected yet. Export scope can never be changed back
     * to this value.
     */
    public const ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_NOT_SELECTED_YET = 'NOT_SELECTED_YET';
    /**
     * Paid and organic channels are eligible to receive conversion credit, but
     * only credit assigned to Google Ads channels will appear in your Ads
     * accounts. To learn more, see [Paid and Organic
     * channels](https://support.google.com/analytics/answer/10632359).
     */
    public const ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_PAID_AND_ORGANIC_CHANNELS = 'PAID_AND_ORGANIC_CHANNELS';
    /**
     * Only Google Ads paid channels are eligible to receive conversion credit. To
     * learn more, see [Google Paid
     * channels](https://support.google.com/analytics/answer/10632359).
     */
    public const ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_GOOGLE_PAID_CHANNELS = 'GOOGLE_PAID_CHANNELS';
    /**
     * Lookback window size unspecified.
     */
    public const OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_UNSPECIFIED = 'OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_UNSPECIFIED';
    /**
     * 30-day lookback window.
     */
    public const OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_30_DAYS = 'OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_30_DAYS';
    /**
     * 60-day lookback window.
     */
    public const OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_60_DAYS = 'OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_60_DAYS';
    /**
     * 90-day lookback window.
     */
    public const OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_90_DAYS = 'OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_90_DAYS';
    /**
     * Reporting attribution model unspecified.
     */
    public const REPORTING_ATTRIBUTION_MODEL_REPORTING_ATTRIBUTION_MODEL_UNSPECIFIED = 'REPORTING_ATTRIBUTION_MODEL_UNSPECIFIED';
    /**
     * Data-driven attribution distributes credit for the conversion based on data
     * for each conversion event. Each Data-driven model is specific to each
     * advertiser and each conversion event. Previously CROSS_CHANNEL_DATA_DRIVEN
     */
    public const REPORTING_ATTRIBUTION_MODEL_PAID_AND_ORGANIC_CHANNELS_DATA_DRIVEN = 'PAID_AND_ORGANIC_CHANNELS_DATA_DRIVEN';
    /**
     * Ignores direct traffic and attributes 100% of the conversion value to the
     * last channel that the customer clicked through (or engaged view through for
     * YouTube) before converting. Previously CROSS_CHANNEL_LAST_CLICK
     */
    public const REPORTING_ATTRIBUTION_MODEL_PAID_AND_ORGANIC_CHANNELS_LAST_CLICK = 'PAID_AND_ORGANIC_CHANNELS_LAST_CLICK';
    /**
     * Attributes 100% of the conversion value to the last Google Paid channel
     * that the customer clicked through before converting. Previously
     * ADS_PREFERRED_LAST_CLICK
     */
    public const REPORTING_ATTRIBUTION_MODEL_GOOGLE_PAID_CHANNELS_LAST_CLICK = 'GOOGLE_PAID_CHANNELS_LAST_CLICK';
    /**
     * Required. The lookback window configuration for acquisition conversion
     * events. The default window size is 30 days.
     *
     * @var string
     */
    public $acquisitionConversionEventLookbackWindow;
    /**
     * Required. The Conversion Export Scope for data exported to linked Ads
     * Accounts.
     *
     * @var string
     */
    public $adsWebConversionDataExportScope;
    /**
     * Output only. Resource name of this attribution settings resource. Format:
     * properties/{property_id}/attributionSettings Example:
     * "properties/1000/attributionSettings"
     *
     * @var string
     */
    public $name;
    /**
     * Required. The lookback window for all other, non-acquisition conversion
     * events. The default window size is 90 days.
     *
     * @var string
     */
    public $otherConversionEventLookbackWindow;
    /**
     * Required. The reporting attribution model used to calculate conversion
     * credit in this property's reports. Changing the attribution model will
     * apply to both historical and future data. These changes will be reflected
     * in reports with conversion and revenue data. User and session data will be
     * unaffected.
     *
     * @var string
     */
    public $reportingAttributionModel;
    /**
     * Required. The lookback window configuration for acquisition conversion
     * events. The default window size is 30 days.
     *
     * Accepted values: ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_UNSPECIFIED,
     * ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_7_DAYS,
     * ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_30_DAYS
     *
     * @param self::ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_* $acquisitionConversionEventLookbackWindow
     */
    public function setAcquisitionConversionEventLookbackWindow($acquisitionConversionEventLookbackWindow)
    {
        $this->acquisitionConversionEventLookbackWindow = $acquisitionConversionEventLookbackWindow;
    }
    /**
     * @return self::ACQUISITION_CONVERSION_EVENT_LOOKBACK_WINDOW_*
     */
    public function getAcquisitionConversionEventLookbackWindow()
    {
        return $this->acquisitionConversionEventLookbackWindow;
    }
    /**
     * Required. The Conversion Export Scope for data exported to linked Ads
     * Accounts.
     *
     * Accepted values: ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_UNSPECIFIED,
     * NOT_SELECTED_YET, PAID_AND_ORGANIC_CHANNELS, GOOGLE_PAID_CHANNELS
     *
     * @param self::ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_* $adsWebConversionDataExportScope
     */
    public function setAdsWebConversionDataExportScope($adsWebConversionDataExportScope)
    {
        $this->adsWebConversionDataExportScope = $adsWebConversionDataExportScope;
    }
    /**
     * @return self::ADS_WEB_CONVERSION_DATA_EXPORT_SCOPE_*
     */
    public function getAdsWebConversionDataExportScope()
    {
        return $this->adsWebConversionDataExportScope;
    }
    /**
     * Output only. Resource name of this attribution settings resource. Format:
     * properties/{property_id}/attributionSettings Example:
     * "properties/1000/attributionSettings"
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
     * Required. The lookback window for all other, non-acquisition conversion
     * events. The default window size is 90 days.
     *
     * Accepted values: OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_UNSPECIFIED,
     * OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_30_DAYS,
     * OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_60_DAYS,
     * OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_90_DAYS
     *
     * @param self::OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_* $otherConversionEventLookbackWindow
     */
    public function setOtherConversionEventLookbackWindow($otherConversionEventLookbackWindow)
    {
        $this->otherConversionEventLookbackWindow = $otherConversionEventLookbackWindow;
    }
    /**
     * @return self::OTHER_CONVERSION_EVENT_LOOKBACK_WINDOW_*
     */
    public function getOtherConversionEventLookbackWindow()
    {
        return $this->otherConversionEventLookbackWindow;
    }
    /**
     * Required. The reporting attribution model used to calculate conversion
     * credit in this property's reports. Changing the attribution model will
     * apply to both historical and future data. These changes will be reflected
     * in reports with conversion and revenue data. User and session data will be
     * unaffected.
     *
     * Accepted values: REPORTING_ATTRIBUTION_MODEL_UNSPECIFIED,
     * PAID_AND_ORGANIC_CHANNELS_DATA_DRIVEN,
     * PAID_AND_ORGANIC_CHANNELS_LAST_CLICK, GOOGLE_PAID_CHANNELS_LAST_CLICK
     *
     * @param self::REPORTING_ATTRIBUTION_MODEL_* $reportingAttributionModel
     */
    public function setReportingAttributionModel($reportingAttributionModel)
    {
        $this->reportingAttributionModel = $reportingAttributionModel;
    }
    /**
     * @return self::REPORTING_ATTRIBUTION_MODEL_*
     */
    public function getReportingAttributionModel()
    {
        return $this->reportingAttributionModel;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaAttributionSettings::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaAttributionSettings');
