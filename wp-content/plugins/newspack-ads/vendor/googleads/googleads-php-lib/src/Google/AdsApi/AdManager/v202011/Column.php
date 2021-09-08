<?php

namespace Google\AdsApi\AdManager\v202011;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class Column
{
    const AD_SERVER_IMPRESSIONS = 'AD_SERVER_IMPRESSIONS';
    const AD_SERVER_TARGETED_IMPRESSIONS = 'AD_SERVER_TARGETED_IMPRESSIONS';
    const AD_SERVER_CLICKS = 'AD_SERVER_CLICKS';
    const AD_SERVER_TARGETED_CLICKS = 'AD_SERVER_TARGETED_CLICKS';
    const AD_SERVER_CTR = 'AD_SERVER_CTR';
    const AD_SERVER_CPM_AND_CPC_REVENUE = 'AD_SERVER_CPM_AND_CPC_REVENUE';
    const AD_SERVER_CPM_AND_CPC_REVENUE_GROSS = 'AD_SERVER_CPM_AND_CPC_REVENUE_GROSS';
    const AD_SERVER_CPD_REVENUE = 'AD_SERVER_CPD_REVENUE';
    const AD_SERVER_CPA_REVENUE = 'AD_SERVER_CPA_REVENUE';
    const AD_SERVER_ALL_REVENUE = 'AD_SERVER_ALL_REVENUE';
    const AD_SERVER_ALL_REVENUE_GROSS = 'AD_SERVER_ALL_REVENUE_GROSS';
    const AD_SERVER_WITHOUT_CPD_AVERAGE_ECPM = 'AD_SERVER_WITHOUT_CPD_AVERAGE_ECPM';
    const AD_SERVER_WITH_CPD_AVERAGE_ECPM = 'AD_SERVER_WITH_CPD_AVERAGE_ECPM';
    const AD_SERVER_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS = 'AD_SERVER_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS';
    const AD_SERVER_LINE_ITEM_LEVEL_PERCENT_CLICKS = 'AD_SERVER_LINE_ITEM_LEVEL_PERCENT_CLICKS';
    const AD_SERVER_INVENTORY_LEVEL_WITH_CPD_PERCENT_REVENUE = 'AD_SERVER_INVENTORY_LEVEL_WITH_CPD_PERCENT_REVENUE';
    const AD_SERVER_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE = 'AD_SERVER_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE';
    const AD_SERVER_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE = 'AD_SERVER_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE';
    const AD_SERVER_UNFILTERED_IMPRESSIONS = 'AD_SERVER_UNFILTERED_IMPRESSIONS';
    const AD_SERVER_UNFILTERED_CLICKS = 'AD_SERVER_UNFILTERED_CLICKS';
    const ADSENSE_LINE_ITEM_LEVEL_IMPRESSIONS = 'ADSENSE_LINE_ITEM_LEVEL_IMPRESSIONS';
    const ADSENSE_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS = 'ADSENSE_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS';
    const ADSENSE_LINE_ITEM_LEVEL_CLICKS = 'ADSENSE_LINE_ITEM_LEVEL_CLICKS';
    const ADSENSE_LINE_ITEM_LEVEL_TARGETED_CLICKS = 'ADSENSE_LINE_ITEM_LEVEL_TARGETED_CLICKS';
    const ADSENSE_LINE_ITEM_LEVEL_CTR = 'ADSENSE_LINE_ITEM_LEVEL_CTR';
    const ADSENSE_LINE_ITEM_LEVEL_REVENUE = 'ADSENSE_LINE_ITEM_LEVEL_REVENUE';
    const ADSENSE_LINE_ITEM_LEVEL_AVERAGE_ECPM = 'ADSENSE_LINE_ITEM_LEVEL_AVERAGE_ECPM';
    const ADSENSE_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS = 'ADSENSE_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS';
    const ADSENSE_LINE_ITEM_LEVEL_PERCENT_CLICKS = 'ADSENSE_LINE_ITEM_LEVEL_PERCENT_CLICKS';
    const ADSENSE_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE = 'ADSENSE_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE';
    const ADSENSE_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE = 'ADSENSE_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS = 'AD_EXCHANGE_LINE_ITEM_LEVEL_IMPRESSIONS';
    const AD_EXCHANGE_IMPRESSIONS = 'AD_EXCHANGE_IMPRESSIONS';
    const AD_EXCHANGE_MATCHED_REQUESTS = 'AD_EXCHANGE_MATCHED_REQUESTS';
    const AD_EXCHANGE_AD_ECPM = 'AD_EXCHANGE_AD_ECPM';
    const AD_EXCHANGE_CLICKS = 'AD_EXCHANGE_CLICKS';
    const AD_EXCHANGE_ESTIMATED_REVENUE = 'AD_EXCHANGE_ESTIMATED_REVENUE';
    const AD_EXCHANGE_COVERAGE = 'AD_EXCHANGE_COVERAGE';
    const AD_EXCHANGE_CTR = 'AD_EXCHANGE_CTR';
    const AD_EXCHANGE_LIFT = 'AD_EXCHANGE_LIFT';
    const AD_EXCHANGE_CPC = 'AD_EXCHANGE_CPC';
    const AD_EXCHANGE_AD_REQUESTS = 'AD_EXCHANGE_AD_REQUESTS';
    const AD_EXCHANGE_AD_REQUEST_ECPM = 'AD_EXCHANGE_AD_REQUEST_ECPM';
    const AD_EXCHANGE_AD_REQUEST_CTR = 'AD_EXCHANGE_AD_REQUEST_CTR';
    const AD_EXCHANGE_AD_CTR = 'AD_EXCHANGE_AD_CTR';
    const AD_EXCHANGE_VIDEO_DROPOFF_RATE = 'AD_EXCHANGE_VIDEO_DROPOFF_RATE';
    const AD_EXCHANGE_VIDEO_ABANDONMENT_RATE = 'AD_EXCHANGE_VIDEO_ABANDONMENT_RATE';
    const AD_EXCHANGE_MATCHED_ECPM = 'AD_EXCHANGE_MATCHED_ECPM';
    const AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE = 'AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE';
    const AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE = 'AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE';
    const AD_EXCHANGE_AVERAGE_VIEWABLE_TIME = 'AD_EXCHANGE_AVERAGE_VIEWABLE_TIME';
    const AD_EXCHANGE_ACTIVE_VIEW_ENABLED_IMPRESSIONS = 'AD_EXCHANGE_ACTIVE_VIEW_ENABLED_IMPRESSIONS';
    const AD_EXCHANGE_ACTIVE_VIEW_MEASURED_IMPRESSIONS = 'AD_EXCHANGE_ACTIVE_VIEW_MEASURED_IMPRESSIONS';
    const AD_EXCHANGE_ACTIVE_VIEW_VIEWED_IMPRESSIONS = 'AD_EXCHANGE_ACTIVE_VIEW_VIEWED_IMPRESSIONS';
    const AD_EXCHANGE_DEALS_BID_RESPONSES = 'AD_EXCHANGE_DEALS_BID_RESPONSES';
    const AD_EXCHANGE_DEALS_MATCHED_REQUESTS = 'AD_EXCHANGE_DEALS_MATCHED_REQUESTS';
    const AD_EXCHANGE_DEALS_AD_REQUESTS = 'AD_EXCHANGE_DEALS_AD_REQUESTS';
    const AD_EXCHANGE_DEALS_MATCH_RATE = 'AD_EXCHANGE_DEALS_MATCH_RATE';
    const AD_EXCHANGE_VIDEO_AUTO_PLAY = 'AD_EXCHANGE_VIDEO_AUTO_PLAY';
    const AD_EXCHANGE_VIDEO_CLICK_TO_PLAY = 'AD_EXCHANGE_VIDEO_CLICK_TO_PLAY';
    const AD_EXCHANGE_VIDEO_START = 'AD_EXCHANGE_VIDEO_START';
    const AD_EXCHANGE_VIDEO_QUARTILE_1 = 'AD_EXCHANGE_VIDEO_QUARTILE_1';
    const AD_EXCHANGE_VIDEO_MIDPOINT = 'AD_EXCHANGE_VIDEO_MIDPOINT';
    const AD_EXCHANGE_VIDEO_QUARTILE_3 = 'AD_EXCHANGE_VIDEO_QUARTILE_3';
    const AD_EXCHANGE_VIDEO_COMPLETE = 'AD_EXCHANGE_VIDEO_COMPLETE';
    const AD_EXCHANGE_VIDEO_COMPLETION_RATE = 'AD_EXCHANGE_VIDEO_COMPLETION_RATE';
    const AD_EXCHANGE_VIDEO_COLLAPSE = 'AD_EXCHANGE_VIDEO_COLLAPSE';
    const AD_EXCHANGE_VIDEO_EXPAND = 'AD_EXCHANGE_VIDEO_EXPAND';
    const AD_EXCHANGE_VIDEO_FULL_SCREEN = 'AD_EXCHANGE_VIDEO_FULL_SCREEN';
    const AD_EXCHANGE_VIDEO_MUTE = 'AD_EXCHANGE_VIDEO_MUTE';
    const AD_EXCHANGE_VIDEO_PAUSE = 'AD_EXCHANGE_VIDEO_PAUSE';
    const AD_EXCHANGE_VIDEO_RESUME = 'AD_EXCHANGE_VIDEO_RESUME';
    const AD_EXCHANGE_VIDEO_REWIND = 'AD_EXCHANGE_VIDEO_REWIND';
    const AD_EXCHANGE_VIDEO_SKIPPED = 'AD_EXCHANGE_VIDEO_SKIPPED';
    const AD_EXCHANGE_VIDEO_UNMUTE = 'AD_EXCHANGE_VIDEO_UNMUTE';
    const AD_EXCHANGE_VIDEO_VAST2_ERROR_COUNT = 'AD_EXCHANGE_VIDEO_VAST2_ERROR_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_100_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_100_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_101_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_101_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_102_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_102_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_200_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_200_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_201_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_201_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_202_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_202_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_203_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_203_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_300_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_300_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_301_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_301_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_302_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_302_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_303_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_303_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_400_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_400_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_401_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_401_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_402_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_402_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_403_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_403_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_405_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_405_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_500_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_500_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_501_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_501_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_502_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_502_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_503_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_503_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_600_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_600_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_601_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_601_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_602_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_602_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_603_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_603_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_604_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_604_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_900_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_900_COUNT';
    const AD_EXCHANGE_VIDEO_VAST_ERROR_901_COUNT = 'AD_EXCHANGE_VIDEO_VAST_ERROR_901_COUNT';
    const AD_EXCHANGE_VIDEO_TOTAL_VAST_ERROR_COUNT = 'AD_EXCHANGE_VIDEO_TOTAL_VAST_ERROR_COUNT';
    const AD_EXCHANGE_VIDEO_TOTAL_ERROR_RATE = 'AD_EXCHANGE_VIDEO_TOTAL_ERROR_RATE';
    const AD_EXCHANGE_VIDEO_TRUEVIEW_SKIP_RATE = 'AD_EXCHANGE_VIDEO_TRUEVIEW_SKIP_RATE';
    const AD_EXCHANGE_VIDEO_TRUEVIEW_VIEWS = 'AD_EXCHANGE_VIDEO_TRUEVIEW_VIEWS';
    const AD_EXCHANGE_VIDEO_TRUEVIEW_VTR = 'AD_EXCHANGE_VIDEO_TRUEVIEW_VTR';
    const MEDIATION_THIRD_PARTY_ECPM = 'MEDIATION_THIRD_PARTY_ECPM';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS = 'AD_EXCHANGE_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS = 'AD_EXCHANGE_LINE_ITEM_LEVEL_CLICKS';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_TARGETED_CLICKS = 'AD_EXCHANGE_LINE_ITEM_LEVEL_TARGETED_CLICKS';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_CTR = 'AD_EXCHANGE_LINE_ITEM_LEVEL_CTR';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS = 'AD_EXCHANGE_LINE_ITEM_LEVEL_PERCENT_IMPRESSIONS';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_PERCENT_CLICKS = 'AD_EXCHANGE_LINE_ITEM_LEVEL_PERCENT_CLICKS';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_REVENUE = 'AD_EXCHANGE_LINE_ITEM_LEVEL_REVENUE';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE = 'AD_EXCHANGE_LINE_ITEM_LEVEL_WITHOUT_CPD_PERCENT_REVENUE';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE = 'AD_EXCHANGE_LINE_ITEM_LEVEL_WITH_CPD_PERCENT_REVENUE';
    const AD_EXCHANGE_LINE_ITEM_LEVEL_AVERAGE_ECPM = 'AD_EXCHANGE_LINE_ITEM_LEVEL_AVERAGE_ECPM';
    const TOTAL_LINE_ITEM_LEVEL_IMPRESSIONS = 'TOTAL_LINE_ITEM_LEVEL_IMPRESSIONS';
    const TOTAL_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS = 'TOTAL_LINE_ITEM_LEVEL_TARGETED_IMPRESSIONS';
    const TOTAL_LINE_ITEM_LEVEL_CLICKS = 'TOTAL_LINE_ITEM_LEVEL_CLICKS';
    const TOTAL_LINE_ITEM_LEVEL_TARGETED_CLICKS = 'TOTAL_LINE_ITEM_LEVEL_TARGETED_CLICKS';
    const TOTAL_LINE_ITEM_LEVEL_CTR = 'TOTAL_LINE_ITEM_LEVEL_CTR';
    const TOTAL_INVENTORY_LEVEL_ALL_REVENUE = 'TOTAL_INVENTORY_LEVEL_ALL_REVENUE';
    const TOTAL_LINE_ITEM_LEVEL_CPM_AND_CPC_REVENUE = 'TOTAL_LINE_ITEM_LEVEL_CPM_AND_CPC_REVENUE';
    const TOTAL_LINE_ITEM_LEVEL_ALL_REVENUE = 'TOTAL_LINE_ITEM_LEVEL_ALL_REVENUE';
    const TOTAL_INVENTORY_LEVEL_WITH_CPD_AVERAGE_ECPM = 'TOTAL_INVENTORY_LEVEL_WITH_CPD_AVERAGE_ECPM';
    const TOTAL_LINE_ITEM_LEVEL_WITHOUT_CPD_AVERAGE_ECPM = 'TOTAL_LINE_ITEM_LEVEL_WITHOUT_CPD_AVERAGE_ECPM';
    const TOTAL_LINE_ITEM_LEVEL_WITH_CPD_AVERAGE_ECPM = 'TOTAL_LINE_ITEM_LEVEL_WITH_CPD_AVERAGE_ECPM';
    const TOTAL_CODE_SERVED_COUNT = 'TOTAL_CODE_SERVED_COUNT';
    const TOTAL_AD_REQUESTS = 'TOTAL_AD_REQUESTS';
    const TOTAL_RESPONSES_SERVED = 'TOTAL_RESPONSES_SERVED';
    const TOTAL_UNMATCHED_AD_REQUESTS = 'TOTAL_UNMATCHED_AD_REQUESTS';
    const TOTAL_FILL_RATE = 'TOTAL_FILL_RATE';
    const AD_SERVER_RESPONSES_SERVED = 'AD_SERVER_RESPONSES_SERVED';
    const ADSENSE_RESPONSES_SERVED = 'ADSENSE_RESPONSES_SERVED';
    const AD_EXCHANGE_RESPONSES_SERVED = 'AD_EXCHANGE_RESPONSES_SERVED';
    const TOTAL_VIDEO_OPPORTUNITIES = 'TOTAL_VIDEO_OPPORTUNITIES';
    const TOTAL_VIDEO_CAPPED_OPPORTUNITIES = 'TOTAL_VIDEO_CAPPED_OPPORTUNITIES';
    const TOTAL_INVENTORY_LEVEL_UNFILLED_IMPRESSIONS = 'TOTAL_INVENTORY_LEVEL_UNFILLED_IMPRESSIONS';
    const OPTIMIZATION_CONTROL_IMPRESSIONS = 'OPTIMIZATION_CONTROL_IMPRESSIONS';
    const OPTIMIZATION_CONTROL_CLICKS = 'OPTIMIZATION_CONTROL_CLICKS';
    const OPTIMIZATION_CONTROL_CTR = 'OPTIMIZATION_CONTROL_CTR';
    const OPTIMIZATION_OPTIMIZED_IMPRESSIONS = 'OPTIMIZATION_OPTIMIZED_IMPRESSIONS';
    const OPTIMIZATION_OPTIMIZED_CLICKS = 'OPTIMIZATION_OPTIMIZED_CLICKS';
    const OPTIMIZATION_NON_OPTIMIZED_IMPRESSIONS = 'OPTIMIZATION_NON_OPTIMIZED_IMPRESSIONS';
    const OPTIMIZATION_NON_OPTIMIZED_CLICKS = 'OPTIMIZATION_NON_OPTIMIZED_CLICKS';
    const OPTIMIZATION_EXTRA_CLICKS = 'OPTIMIZATION_EXTRA_CLICKS';
    const OPTIMIZATION_OPTIMIZED_CTR = 'OPTIMIZATION_OPTIMIZED_CTR';
    const OPTIMIZATION_LIFT = 'OPTIMIZATION_LIFT';
    const OPTIMIZATION_COVERAGE = 'OPTIMIZATION_COVERAGE';
    const OPTIMIZATION_BEHIND_SCHEDULE_IMPRESSIONS = 'OPTIMIZATION_BEHIND_SCHEDULE_IMPRESSIONS';
    const OPTIMIZATION_NO_CLICKS_RECORDED_IMPRESSIONS = 'OPTIMIZATION_NO_CLICKS_RECORDED_IMPRESSIONS';
    const OPTIMIZATION_SPONSORSHIP_IMPRESSIONS = 'OPTIMIZATION_SPONSORSHIP_IMPRESSIONS';
    const OPTIMIZATION_AS_FAST_AS_POSSIBLE_IMPRESSIONS = 'OPTIMIZATION_AS_FAST_AS_POSSIBLE_IMPRESSIONS';
    const OPTIMIZATION_NO_ABSOLUTE_LIFETIME_GOAL_IMPRESSIONS = 'OPTIMIZATION_NO_ABSOLUTE_LIFETIME_GOAL_IMPRESSIONS';
    const OPTIMIZATION_CONTROL_REVENUE = 'OPTIMIZATION_CONTROL_REVENUE';
    const OPTIMIZATION_OPTIMIZED_REVENUE = 'OPTIMIZATION_OPTIMIZED_REVENUE';
    const OPTIMIZATION_CONTROL_ECPM = 'OPTIMIZATION_CONTROL_ECPM';
    const OPTIMIZATION_OPTIMIZED_ECPM = 'OPTIMIZATION_OPTIMIZED_ECPM';
    const OPTIMIZATION_FREED_UP_IMPRESSIONS = 'OPTIMIZATION_FREED_UP_IMPRESSIONS';
    const OPTIMIZATION_ECPM_LIFT = 'OPTIMIZATION_ECPM_LIFT';
    const UNIQUE_REACH_FREQUENCY = 'UNIQUE_REACH_FREQUENCY';
    const UNIQUE_REACH_IMPRESSIONS = 'UNIQUE_REACH_IMPRESSIONS';
    const UNIQUE_REACH = 'UNIQUE_REACH';
    const SDK_MEDIATION_CREATIVE_IMPRESSIONS = 'SDK_MEDIATION_CREATIVE_IMPRESSIONS';
    const SDK_MEDIATION_CREATIVE_CLICKS = 'SDK_MEDIATION_CREATIVE_CLICKS';
    const SELL_THROUGH_FORECASTED_IMPRESSIONS = 'SELL_THROUGH_FORECASTED_IMPRESSIONS';
    const SELL_THROUGH_AVAILABLE_IMPRESSIONS = 'SELL_THROUGH_AVAILABLE_IMPRESSIONS';
    const SELL_THROUGH_RESERVED_IMPRESSIONS = 'SELL_THROUGH_RESERVED_IMPRESSIONS';
    const SELL_THROUGH_SELL_THROUGH_RATE = 'SELL_THROUGH_SELL_THROUGH_RATE';
    const RICH_MEDIA_BACKUP_IMAGES = 'RICH_MEDIA_BACKUP_IMAGES';
    const RICH_MEDIA_DISPLAY_TIME = 'RICH_MEDIA_DISPLAY_TIME';
    const RICH_MEDIA_AVERAGE_DISPLAY_TIME = 'RICH_MEDIA_AVERAGE_DISPLAY_TIME';
    const RICH_MEDIA_EXPANSIONS = 'RICH_MEDIA_EXPANSIONS';
    const RICH_MEDIA_EXPANDING_TIME = 'RICH_MEDIA_EXPANDING_TIME';
    const RICH_MEDIA_INTERACTION_TIME = 'RICH_MEDIA_INTERACTION_TIME';
    const RICH_MEDIA_INTERACTION_COUNT = 'RICH_MEDIA_INTERACTION_COUNT';
    const RICH_MEDIA_INTERACTION_RATE = 'RICH_MEDIA_INTERACTION_RATE';
    const RICH_MEDIA_AVERAGE_INTERACTION_TIME = 'RICH_MEDIA_AVERAGE_INTERACTION_TIME';
    const RICH_MEDIA_INTERACTION_IMPRESSIONS = 'RICH_MEDIA_INTERACTION_IMPRESSIONS';
    const RICH_MEDIA_MANUAL_CLOSES = 'RICH_MEDIA_MANUAL_CLOSES';
    const RICH_MEDIA_FULL_SCREEN_IMPRESSIONS = 'RICH_MEDIA_FULL_SCREEN_IMPRESSIONS';
    const RICH_MEDIA_VIDEO_INTERACTIONS = 'RICH_MEDIA_VIDEO_INTERACTIONS';
    const RICH_MEDIA_VIDEO_INTERACTION_RATE = 'RICH_MEDIA_VIDEO_INTERACTION_RATE';
    const RICH_MEDIA_VIDEO_MUTES = 'RICH_MEDIA_VIDEO_MUTES';
    const RICH_MEDIA_VIDEO_PAUSES = 'RICH_MEDIA_VIDEO_PAUSES';
    const RICH_MEDIA_VIDEO_PLAYES = 'RICH_MEDIA_VIDEO_PLAYES';
    const RICH_MEDIA_VIDEO_MIDPOINTS = 'RICH_MEDIA_VIDEO_MIDPOINTS';
    const RICH_MEDIA_VIDEO_COMPLETES = 'RICH_MEDIA_VIDEO_COMPLETES';
    const RICH_MEDIA_VIDEO_REPLAYS = 'RICH_MEDIA_VIDEO_REPLAYS';
    const RICH_MEDIA_VIDEO_STOPS = 'RICH_MEDIA_VIDEO_STOPS';
    const RICH_MEDIA_VIDEO_UNMUTES = 'RICH_MEDIA_VIDEO_UNMUTES';
    const RICH_MEDIA_VIDEO_VIEW_TIME = 'RICH_MEDIA_VIDEO_VIEW_TIME';
    const RICH_MEDIA_VIDEO_VIEW_RATE = 'RICH_MEDIA_VIDEO_VIEW_RATE';
    const RICH_MEDIA_CUSTOM_EVENT_TIME = 'RICH_MEDIA_CUSTOM_EVENT_TIME';
    const RICH_MEDIA_CUSTOM_EVENT_COUNT = 'RICH_MEDIA_CUSTOM_EVENT_COUNT';
    const VIDEO_VIEWERSHIP_START = 'VIDEO_VIEWERSHIP_START';
    const VIDEO_VIEWERSHIP_FIRST_QUARTILE = 'VIDEO_VIEWERSHIP_FIRST_QUARTILE';
    const VIDEO_VIEWERSHIP_MIDPOINT = 'VIDEO_VIEWERSHIP_MIDPOINT';
    const VIDEO_VIEWERSHIP_THIRD_QUARTILE = 'VIDEO_VIEWERSHIP_THIRD_QUARTILE';
    const VIDEO_VIEWERSHIP_COMPLETE = 'VIDEO_VIEWERSHIP_COMPLETE';
    const VIDEO_VIEWERSHIP_AVERAGE_VIEW_RATE = 'VIDEO_VIEWERSHIP_AVERAGE_VIEW_RATE';
    const VIDEO_VIEWERSHIP_AVERAGE_VIEW_TIME = 'VIDEO_VIEWERSHIP_AVERAGE_VIEW_TIME';
    const VIDEO_VIEWERSHIP_COMPLETION_RATE = 'VIDEO_VIEWERSHIP_COMPLETION_RATE';
    const VIDEO_VIEWERSHIP_TOTAL_ERROR_COUNT = 'VIDEO_VIEWERSHIP_TOTAL_ERROR_COUNT';
    const VIDEO_VIEWERSHIP_VIDEO_LENGTH = 'VIDEO_VIEWERSHIP_VIDEO_LENGTH';
    const VIDEO_VIEWERSHIP_SKIP_BUTTON_SHOWN = 'VIDEO_VIEWERSHIP_SKIP_BUTTON_SHOWN';
    const VIDEO_VIEWERSHIP_ENGAGED_VIEW = 'VIDEO_VIEWERSHIP_ENGAGED_VIEW';
    const VIDEO_VIEWERSHIP_VIEW_THROUGH_RATE = 'VIDEO_VIEWERSHIP_VIEW_THROUGH_RATE';
    const VIDEO_VIEWERSHIP_AUTO_PLAYS = 'VIDEO_VIEWERSHIP_AUTO_PLAYS';
    const VIDEO_VIEWERSHIP_CLICK_TO_PLAYS = 'VIDEO_VIEWERSHIP_CLICK_TO_PLAYS';
    const VIDEO_VIEWERSHIP_TOTAL_ERROR_RATE = 'VIDEO_VIEWERSHIP_TOTAL_ERROR_RATE';
    const VIDEO_ERRORS_VAST_ERROR_100_COUNT = 'VIDEO_ERRORS_VAST_ERROR_100_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_101_COUNT = 'VIDEO_ERRORS_VAST_ERROR_101_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_102_COUNT = 'VIDEO_ERRORS_VAST_ERROR_102_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_200_COUNT = 'VIDEO_ERRORS_VAST_ERROR_200_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_201_COUNT = 'VIDEO_ERRORS_VAST_ERROR_201_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_202_COUNT = 'VIDEO_ERRORS_VAST_ERROR_202_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_203_COUNT = 'VIDEO_ERRORS_VAST_ERROR_203_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_300_COUNT = 'VIDEO_ERRORS_VAST_ERROR_300_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_301_COUNT = 'VIDEO_ERRORS_VAST_ERROR_301_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_302_COUNT = 'VIDEO_ERRORS_VAST_ERROR_302_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_303_COUNT = 'VIDEO_ERRORS_VAST_ERROR_303_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_400_COUNT = 'VIDEO_ERRORS_VAST_ERROR_400_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_401_COUNT = 'VIDEO_ERRORS_VAST_ERROR_401_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_402_COUNT = 'VIDEO_ERRORS_VAST_ERROR_402_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_403_COUNT = 'VIDEO_ERRORS_VAST_ERROR_403_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_405_COUNT = 'VIDEO_ERRORS_VAST_ERROR_405_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_500_COUNT = 'VIDEO_ERRORS_VAST_ERROR_500_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_501_COUNT = 'VIDEO_ERRORS_VAST_ERROR_501_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_502_COUNT = 'VIDEO_ERRORS_VAST_ERROR_502_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_503_COUNT = 'VIDEO_ERRORS_VAST_ERROR_503_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_600_COUNT = 'VIDEO_ERRORS_VAST_ERROR_600_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_601_COUNT = 'VIDEO_ERRORS_VAST_ERROR_601_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_602_COUNT = 'VIDEO_ERRORS_VAST_ERROR_602_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_603_COUNT = 'VIDEO_ERRORS_VAST_ERROR_603_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_604_COUNT = 'VIDEO_ERRORS_VAST_ERROR_604_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_900_COUNT = 'VIDEO_ERRORS_VAST_ERROR_900_COUNT';
    const VIDEO_ERRORS_VAST_ERROR_901_COUNT = 'VIDEO_ERRORS_VAST_ERROR_901_COUNT';
    const VIDEO_INTERACTION_PAUSE = 'VIDEO_INTERACTION_PAUSE';
    const VIDEO_INTERACTION_RESUME = 'VIDEO_INTERACTION_RESUME';
    const VIDEO_INTERACTION_REWIND = 'VIDEO_INTERACTION_REWIND';
    const VIDEO_INTERACTION_MUTE = 'VIDEO_INTERACTION_MUTE';
    const VIDEO_INTERACTION_UNMUTE = 'VIDEO_INTERACTION_UNMUTE';
    const VIDEO_INTERACTION_COLLAPSE = 'VIDEO_INTERACTION_COLLAPSE';
    const VIDEO_INTERACTION_EXPAND = 'VIDEO_INTERACTION_EXPAND';
    const VIDEO_INTERACTION_FULL_SCREEN = 'VIDEO_INTERACTION_FULL_SCREEN';
    const VIDEO_INTERACTION_AVERAGE_INTERACTION_RATE = 'VIDEO_INTERACTION_AVERAGE_INTERACTION_RATE';
    const VIDEO_INTERACTION_VIDEO_SKIPS = 'VIDEO_INTERACTION_VIDEO_SKIPS';
    const VIDEO_OPTIMIZATION_CONTROL_STARTS = 'VIDEO_OPTIMIZATION_CONTROL_STARTS';
    const VIDEO_OPTIMIZATION_OPTIMIZED_STARTS = 'VIDEO_OPTIMIZATION_OPTIMIZED_STARTS';
    const VIDEO_OPTIMIZATION_CONTROL_COMPLETES = 'VIDEO_OPTIMIZATION_CONTROL_COMPLETES';
    const VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETES = 'VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETES';
    const VIDEO_OPTIMIZATION_CONTROL_COMPLETION_RATE = 'VIDEO_OPTIMIZATION_CONTROL_COMPLETION_RATE';
    const VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETION_RATE = 'VIDEO_OPTIMIZATION_OPTIMIZED_COMPLETION_RATE';
    const VIDEO_OPTIMIZATION_COMPLETION_RATE_LIFT = 'VIDEO_OPTIMIZATION_COMPLETION_RATE_LIFT';
    const VIDEO_OPTIMIZATION_CONTROL_SKIP_BUTTON_SHOWN = 'VIDEO_OPTIMIZATION_CONTROL_SKIP_BUTTON_SHOWN';
    const VIDEO_OPTIMIZATION_OPTIMIZED_SKIP_BUTTON_SHOWN = 'VIDEO_OPTIMIZATION_OPTIMIZED_SKIP_BUTTON_SHOWN';
    const VIDEO_OPTIMIZATION_CONTROL_ENGAGED_VIEW = 'VIDEO_OPTIMIZATION_CONTROL_ENGAGED_VIEW';
    const VIDEO_OPTIMIZATION_OPTIMIZED_ENGAGED_VIEW = 'VIDEO_OPTIMIZATION_OPTIMIZED_ENGAGED_VIEW';
    const VIDEO_OPTIMIZATION_CONTROL_VIEW_THROUGH_RATE = 'VIDEO_OPTIMIZATION_CONTROL_VIEW_THROUGH_RATE';
    const VIDEO_OPTIMIZATION_OPTIMIZED_VIEW_THROUGH_RATE = 'VIDEO_OPTIMIZATION_OPTIMIZED_VIEW_THROUGH_RATE';
    const VIDEO_OPTIMIZATION_VIEW_THROUGH_RATE_LIFT = 'VIDEO_OPTIMIZATION_VIEW_THROUGH_RATE_LIFT';
    const TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS = 'TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS';
    const TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS = 'TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS';
    const TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE = 'TOTAL_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE';
    const TOTAL_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS = 'TOTAL_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS';
    const TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE = 'TOTAL_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE';
    const TOTAL_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME = 'TOTAL_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME';
    const AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS = 'AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS';
    const AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS = 'AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS';
    const AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE = 'AD_SERVER_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE';
    const AD_SERVER_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS = 'AD_SERVER_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS';
    const AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE = 'AD_SERVER_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE';
    const AD_SERVER_ACTIVE_VIEW_REVENUE = 'AD_SERVER_ACTIVE_VIEW_REVENUE';
    const AD_SERVER_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME = 'AD_SERVER_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME';
    const ADSENSE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS = 'ADSENSE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS';
    const ADSENSE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS = 'ADSENSE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS';
    const ADSENSE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE = 'ADSENSE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE';
    const ADSENSE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS = 'ADSENSE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS';
    const ADSENSE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE = 'ADSENSE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE';
    const ADSENSE_ACTIVE_VIEW_REVENUE = 'ADSENSE_ACTIVE_VIEW_REVENUE';
    const ADSENSE_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME = 'ADSENSE_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME';
    const AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS = 'AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS';
    const AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS = 'AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS';
    const AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE = 'AD_EXCHANGE_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE';
    const AD_EXCHANGE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS = 'AD_EXCHANGE_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS';
    const AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE = 'AD_EXCHANGE_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE';
    const AD_EXCHANGE_ACTIVE_VIEW_REVENUE = 'AD_EXCHANGE_ACTIVE_VIEW_REVENUE';
    const AD_EXCHANGE_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME = 'AD_EXCHANGE_ACTIVE_VIEW_AVERAGE_VIEWABLE_TIME';
    const TOTAL_ACTIVE_VIEW_REVENUE = 'TOTAL_ACTIVE_VIEW_REVENUE';
    const VIEW_THROUGH_CONVERSIONS = 'VIEW_THROUGH_CONVERSIONS';
    const CONVERSIONS_PER_THOUSAND_IMPRESSIONS = 'CONVERSIONS_PER_THOUSAND_IMPRESSIONS';
    const CLICK_THROUGH_CONVERSIONS = 'CLICK_THROUGH_CONVERSIONS';
    const CONVERSIONS_PER_CLICK = 'CONVERSIONS_PER_CLICK';
    const VIEW_THROUGH_REVENUE = 'VIEW_THROUGH_REVENUE';
    const CLICK_THROUGH_REVENUE = 'CLICK_THROUGH_REVENUE';
    const TOTAL_CONVERSIONS = 'TOTAL_CONVERSIONS';
    const TOTAL_CONVERSION_REVENUE = 'TOTAL_CONVERSION_REVENUE';
    const DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_COMPETING_TOTAL = 'DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_COMPETING_TOTAL';
    const DYNAMIC_ALLOCATION_OPPORTUNITY_UNFILLED_IMPRESSIONS_COMPETING = 'DYNAMIC_ALLOCATION_OPPORTUNITY_UNFILLED_IMPRESSIONS_COMPETING';
    const DYNAMIC_ALLOCATION_OPPORTUNITY_ELIGIBLE_IMPRESSIONS_TOTAL = 'DYNAMIC_ALLOCATION_OPPORTUNITY_ELIGIBLE_IMPRESSIONS_TOTAL';
    const DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_NOT_COMPETING_TOTAL = 'DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_NOT_COMPETING_TOTAL';
    const DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_NOT_COMPETING_PERCENT_TOTAL = 'DYNAMIC_ALLOCATION_OPPORTUNITY_IMPRESSIONS_NOT_COMPETING_PERCENT_TOTAL';
    const DYNAMIC_ALLOCATION_OPPORTUNITY_SATURATION_RATE_TOTAL = 'DYNAMIC_ALLOCATION_OPPORTUNITY_SATURATION_RATE_TOTAL';
    const DYNAMIC_ALLOCATION_OPPORTUNITY_MATCH_RATE_TOTAL = 'DYNAMIC_ALLOCATION_OPPORTUNITY_MATCH_RATE_TOTAL';
    const INVOICED_IMPRESSIONS = 'INVOICED_IMPRESSIONS';
    const INVOICED_UNFILLED_IMPRESSIONS = 'INVOICED_UNFILLED_IMPRESSIONS';
    const NIELSEN_IMPRESSIONS = 'NIELSEN_IMPRESSIONS';
    const NIELSEN_IN_TARGET_IMPRESSIONS = 'NIELSEN_IN_TARGET_IMPRESSIONS';
    const NIELSEN_POPULATION_BASE = 'NIELSEN_POPULATION_BASE';
    const NIELSEN_IN_TARGET_POPULATION_BASE = 'NIELSEN_IN_TARGET_POPULATION_BASE';
    const NIELSEN_UNIQUE_AUDIENCE = 'NIELSEN_UNIQUE_AUDIENCE';
    const NIELSEN_IN_TARGET_UNIQUE_AUDIENCE = 'NIELSEN_IN_TARGET_UNIQUE_AUDIENCE';
    const NIELSEN_PERCENT_AUDIENCE_REACH = 'NIELSEN_PERCENT_AUDIENCE_REACH';
    const NIELSEN_IN_TARGET_PERCENT_AUDIENCE_REACH = 'NIELSEN_IN_TARGET_PERCENT_AUDIENCE_REACH';
    const NIELSEN_AVERAGE_FREQUENCY = 'NIELSEN_AVERAGE_FREQUENCY';
    const NIELSEN_IN_TARGET_AVERAGE_FREQUENCY = 'NIELSEN_IN_TARGET_AVERAGE_FREQUENCY';
    const NIELSEN_GROSS_RATING_POINTS = 'NIELSEN_GROSS_RATING_POINTS';
    const NIELSEN_IN_TARGET_GROSS_RATING_POINTS = 'NIELSEN_IN_TARGET_GROSS_RATING_POINTS';
    const NIELSEN_PERCENT_IMPRESSIONS_SHARE = 'NIELSEN_PERCENT_IMPRESSIONS_SHARE';
    const NIELSEN_IN_TARGET_PERCENT_IMPRESSIONS_SHARE = 'NIELSEN_IN_TARGET_PERCENT_IMPRESSIONS_SHARE';
    const NIELSEN_PERCENT_POPULATION_SHARE = 'NIELSEN_PERCENT_POPULATION_SHARE';
    const NIELSEN_IN_TARGET_PERCENT_POPULATION_SHARE = 'NIELSEN_IN_TARGET_PERCENT_POPULATION_SHARE';
    const NIELSEN_PERCENT_AUDIENCE_SHARE = 'NIELSEN_PERCENT_AUDIENCE_SHARE';
    const NIELSEN_IN_TARGET_PERCENT_AUDIENCE_SHARE = 'NIELSEN_IN_TARGET_PERCENT_AUDIENCE_SHARE';
    const NIELSEN_AUDIENCE_INDEX = 'NIELSEN_AUDIENCE_INDEX';
    const NIELSEN_IN_TARGET_AUDIENCE_INDEX = 'NIELSEN_IN_TARGET_AUDIENCE_INDEX';
    const NIELSEN_IMPRESSIONS_INDEX = 'NIELSEN_IMPRESSIONS_INDEX';
    const NIELSEN_IN_TARGET_IMPRESSIONS_INDEX = 'NIELSEN_IN_TARGET_IMPRESSIONS_INDEX';
    const DP_IMPRESSIONS = 'DP_IMPRESSIONS';
    const DP_CLICKS = 'DP_CLICKS';
    const DP_QUERIES = 'DP_QUERIES';
    const DP_MATCHED_QUERIES = 'DP_MATCHED_QUERIES';
    const DP_COST = 'DP_COST';
    const DP_ECPM = 'DP_ECPM';
    const DP_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS = 'DP_ACTIVE_VIEW_ELIGIBLE_IMPRESSIONS';
    const DP_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS = 'DP_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS';
    const DP_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS = 'DP_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS';
    const DP_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE = 'DP_ACTIVE_VIEW_MEASURABLE_IMPRESSIONS_RATE';
    const DP_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE = 'DP_ACTIVE_VIEW_VIEWABLE_IMPRESSIONS_RATE';
    const PARTNER_MANAGEMENT_HOST_IMPRESSIONS = 'PARTNER_MANAGEMENT_HOST_IMPRESSIONS';
    const PARTNER_MANAGEMENT_HOST_CLICKS = 'PARTNER_MANAGEMENT_HOST_CLICKS';
    const PARTNER_MANAGEMENT_HOST_CTR = 'PARTNER_MANAGEMENT_HOST_CTR';
    const PARTNER_MANAGEMENT_UNFILLED_IMPRESSIONS = 'PARTNER_MANAGEMENT_UNFILLED_IMPRESSIONS';
    const PARTNER_MANAGEMENT_PARTNER_IMPRESSIONS = 'PARTNER_MANAGEMENT_PARTNER_IMPRESSIONS';
    const PARTNER_MANAGEMENT_PARTNER_CLICKS = 'PARTNER_MANAGEMENT_PARTNER_CLICKS';
    const PARTNER_MANAGEMENT_PARTNER_CTR = 'PARTNER_MANAGEMENT_PARTNER_CTR';
    const PARTNER_MANAGEMENT_GROSS_REVENUE = 'PARTNER_MANAGEMENT_GROSS_REVENUE';
    const PARTNER_FINANCE_HOST_IMPRESSIONS = 'PARTNER_FINANCE_HOST_IMPRESSIONS';
    const PARTNER_FINANCE_HOST_REVENUE = 'PARTNER_FINANCE_HOST_REVENUE';
    const PARTNER_FINANCE_HOST_ECPM = 'PARTNER_FINANCE_HOST_ECPM';
    const PARTNER_FINANCE_PARTNER_REVENUE = 'PARTNER_FINANCE_PARTNER_REVENUE';
    const PARTNER_FINANCE_PARTNER_ECPM = 'PARTNER_FINANCE_PARTNER_ECPM';
    const PARTNER_FINANCE_GROSS_REVENUE = 'PARTNER_FINANCE_GROSS_REVENUE';
    const CREATIVE_LOAD_TIME_0_500_MS_PERCENT = 'CREATIVE_LOAD_TIME_0_500_MS_PERCENT';
    const CREATIVE_LOAD_TIME_500_1000_MS_PERCENT = 'CREATIVE_LOAD_TIME_500_1000_MS_PERCENT';
    const CREATIVE_LOAD_TIME_1_2_S_PERCENT = 'CREATIVE_LOAD_TIME_1_2_S_PERCENT';
    const CREATIVE_LOAD_TIME_2_4_S_PERCENT = 'CREATIVE_LOAD_TIME_2_4_S_PERCENT';
    const CREATIVE_LOAD_TIME_4_8_S_PERCENT = 'CREATIVE_LOAD_TIME_4_8_S_PERCENT';
    const CREATIVE_LOAD_TIME_GREATER_THAN_8_S_PERCENT = 'CREATIVE_LOAD_TIME_GREATER_THAN_8_S_PERCENT';
    const UNVIEWED_REASON_SLOT_NEVER_ENTERED_VIEWPORT_PERCENT = 'UNVIEWED_REASON_SLOT_NEVER_ENTERED_VIEWPORT_PERCENT';
    const UNVIEWED_REASON_USER_SCROLLED_BEFORE_AD_FILLED_PERCENT = 'UNVIEWED_REASON_USER_SCROLLED_BEFORE_AD_FILLED_PERCENT';
    const UNVIEWED_REASON_USER_SCROLLED_BEFORE_AD_LOADED_PERCENT = 'UNVIEWED_REASON_USER_SCROLLED_BEFORE_AD_LOADED_PERCENT';
    const UNVIEWED_REASON_USER_SCROLLED_BEFORE_1_S_PERCENT = 'UNVIEWED_REASON_USER_SCROLLED_BEFORE_1_S_PERCENT';
    const UNVIEWED_REASON_OTHER_PERCENT = 'UNVIEWED_REASON_OTHER_PERCENT';
    const PAGE_NAVIGATION_TO_TAG_LOADED_TIME_0_500_MS_PERCENT = 'PAGE_NAVIGATION_TO_TAG_LOADED_TIME_0_500_MS_PERCENT';
    const PAGE_NAVIGATION_TO_TAG_LOADED_TIME_500_1000_MS_PERCENT = 'PAGE_NAVIGATION_TO_TAG_LOADED_TIME_500_1000_MS_PERCENT';
    const PAGE_NAVIGATION_TO_TAG_LOADED_TIME_1_2_S_PERCENT = 'PAGE_NAVIGATION_TO_TAG_LOADED_TIME_1_2_S_PERCENT';
    const PAGE_NAVIGATION_TO_TAG_LOADED_TIME_2_4_S_PERCENT = 'PAGE_NAVIGATION_TO_TAG_LOADED_TIME_2_4_S_PERCENT';
    const PAGE_NAVIGATION_TO_TAG_LOADED_TIME_4_8_S_PERCENT = 'PAGE_NAVIGATION_TO_TAG_LOADED_TIME_4_8_S_PERCENT';
    const PAGE_NAVIGATION_TO_TAG_LOADED_TIME_GREATER_THAN_8_S_PERCENT = 'PAGE_NAVIGATION_TO_TAG_LOADED_TIME_GREATER_THAN_8_S_PERCENT';
    const PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_0_500_MS_PERCENT = 'PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_0_500_MS_PERCENT';
    const PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_500_1000_MS_PERCENT = 'PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_500_1000_MS_PERCENT';
    const PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_1_2_S_PERCENT = 'PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_1_2_S_PERCENT';
    const PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_2_4_S_PERCENT = 'PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_2_4_S_PERCENT';
    const PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_4_8_S_PERCENT = 'PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_4_8_S_PERCENT';
    const PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_GREATER_THAN_8_S_PERCENT = 'PAGE_NAVIGATION_TO_FIRST_AD_REQUEST_TIME_GREATER_THAN_8_S_PERCENT';
    const TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_0_500_MS_PERCENT = 'TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_0_500_MS_PERCENT';
    const TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_500_1000_MS_PERCENT = 'TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_500_1000_MS_PERCENT';
    const TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_1_2_S_PERCENT = 'TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_1_2_S_PERCENT';
    const TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_2_4_S_PERCENT = 'TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_2_4_S_PERCENT';
    const TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_4_8_S_PERCENT = 'TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_4_8_S_PERCENT';
    const TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_GREATER_THAN_8_S_PERCENT = 'TAG_LOAD_TO_FIRST_AD_REQUEST_TIME_GREATER_THAN_8_S_PERCENT';


}
