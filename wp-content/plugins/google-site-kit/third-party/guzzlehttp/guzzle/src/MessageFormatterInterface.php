<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp;

use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface;
interface MessageFormatterInterface
{
    /**
     * Returns a formatted message string.
     *
     * @param RequestInterface       $request  Request that was sent
     * @param ResponseInterface|null $response Response that was received
     * @param \Throwable|null        $error    Exception that was received
     */
    public function format(\Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface $request, ?\Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface $response = null, ?\Throwable $error = null) : string;
}
