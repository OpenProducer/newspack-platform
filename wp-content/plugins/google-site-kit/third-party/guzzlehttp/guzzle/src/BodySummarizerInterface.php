<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp;

use Google\Site_Kit_Dependencies\Psr\Http\Message\MessageInterface;
interface BodySummarizerInterface
{
    /**
     * Returns a summarized message body.
     */
    public function summarize(\Google\Site_Kit_Dependencies\Psr\Http\Message\MessageInterface $message) : ?string;
}
