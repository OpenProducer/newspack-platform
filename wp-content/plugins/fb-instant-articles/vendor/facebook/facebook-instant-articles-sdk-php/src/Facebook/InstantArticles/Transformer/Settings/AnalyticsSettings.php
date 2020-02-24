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
use Facebook\InstantArticles\Elements\Analytics;

class AnalyticsSettings
{
    /**
     * @var string $fbPixelId Facebook pixel representational ID
     */
    private $fbPixelId;

    /**
     * @var string $rawHTML The raw HTML content for ADS to be inserted into Instant Articles
     */
    private $rawHTML;

    public function __construct($fbPixelId = "", $rawHTML = "")
    {
        if (Type::enforce($fbPixelId, Type::STRING) &&
            !Type::isTextEmpty($fbPixelId)) {
            $this->fbPixelId = $fbPixelId;
        }
        if (Type::enforce($rawHTML, Type::STRING) &&
            !Type::isTextEmpty($rawHTML)) {
            $this->rawHTML = $rawHTML;
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
    public function getFbPixelId()
    {
        return $this->fbPixelId;
    }

    public function getFbPixelScript()
    {
        $pixelCode = "";
        if ($this->fbPixelId && !Type::isTextEmpty($this->fbPixelId)) {
            $pixelCode = $pixelCode.
                "<!-- Facebook Pixel Code --> ".
                "<script> ".
                  "!function(f,b,e,v,n,t,s) ".
                  "{if(f.fbq)return;n=f.fbq=function(){n.callMethod? ".
                  "n.callMethod.apply(n,arguments):n.queue.push(arguments)}; ".
                  "if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0'; ".
                  "n.queue=[];t=b.createElement(e);t.async=!0; ".
                  "t.src=v;s=b.getElementsByTagName(e)[0]; ".
                  "s.parentNode.insertBefore(t,s)}(window, document,'script', ".
                  "'https://connect.facebook.net/en_US/fbevents.js'); ".
                  "fbq('init', '$this->fbPixelId'); ".
                  "fbq('track', 'PageView'); ".
                  "fbq('track', 'ViewContent', {".
                    "title: ia_document.title, ".
                    "platform: 'InstantArticles'".
                  "});".
                "</script>".
                "<!-- End Facebook Pixel Code -->";
        }

        if ($this->rawHTML && !Type::isTextEmpty($this->rawHTML)) {
            $pixelCode = $pixelCode." ".$this->rawHTML;
        }

        return $pixelCode;
    }

    public function getAnalyticsElement()
    {
        $analytics = null;
        if (!Type::isTextEmpty($this->getFbPixelScript())) {
            $analytics = Analytics::create();
            $analytics->withHTML($this->getFbPixelScript());
        }
        return $analytics;
    }
}
