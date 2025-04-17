<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp;

use Google\Site_Kit_Dependencies\Psr\Http\Message\MessageInterface;
final class BodySummarizer implements \Google\Site_Kit_Dependencies\GuzzleHttp\BodySummarizerInterface
{
    /**
     * @var int|null
     */
    private $truncateAt;
    public function __construct(?int $truncateAt = null)
    {
        $this->truncateAt = $truncateAt;
    }
    /**
     * Returns a summarized message body.
     */
    public function summarize(\Google\Site_Kit_Dependencies\Psr\Http\Message\MessageInterface $message) : ?string
    {
        return $this->truncateAt === null ? \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Message::bodySummary($message) : \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Message::bodySummary($message, $this->truncateAt);
    }
}
