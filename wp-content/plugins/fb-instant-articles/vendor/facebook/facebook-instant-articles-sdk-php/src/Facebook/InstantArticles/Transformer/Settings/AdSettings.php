<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Settings;

use Facebook\InstantArticles\Validators\Type;
use Facebook\InstantArticles\Elements\Ad;

class AdSettings
{
    /**
     * @var string $audienceNetworkPlacementId AN representational ID
     */
    private $audienceNetworkPlacementId;

    /**
     * @var string $rawHTML The raw HTML content for ADS to be inserted into Instant Articles
     */
    private $rawHTML;

    public function __construct($audienceNetworkPlacementId = "", $rawHTML = "")
    {
        if (Type::enforce($audienceNetworkPlacementId, Type::STRING) &&
            !Type::isTextEmpty($audienceNetworkPlacementId)) {
            $this->audienceNetworkPlacementId = $audienceNetworkPlacementId;
        }
        if (Type::enforce($rawHTML, Type::STRING) &&
            !Type::isTextEmpty($rawHTML)) {
            $this->rawHTML = $rawHTML;
        }

        if (!Type::isTextEmpty($audienceNetworkPlacementId) && !Type::isTextEmpty($rawHTML)) {
            throw new \Exception("You must specify only one Ads Setting, either audience_network_placement_id or raw_html", 1);
        }
    }

    /**
     * @return string Returns the previous set raw HTML content;
     */
    public function getRawHTML()
    {
        return $this->rawHTML;
    }

    /**
     * @return string Returns the previous set AN ID.
     */
    public function getAudienceNetworkPlacementId()
    {
        return $this->audienceNetworkPlacementId;
    }

    public function getAdElement()
    {
        $ad = null;

        if (!Type::isTextEmpty($this->getAudienceNetworkPlacementId()) ||
            (!Type::isTextEmpty($this->getRawHTML()))) {
            $ad = Ad::create();
            if (!Type::isTextEmpty($this->getAudienceNetworkPlacementId())) {
                $ad->withSource(
                    'https://www.facebook.com/adnw_request?placement='.
                    $this->getAudienceNetworkPlacementId()
                );
            }
            if (!Type::isTextEmpty($this->getRawHTML())) {
                $ad->withHTML(
                    $this->getRawHTML()
                );
            }
        }

        return $ad;
    }
}
