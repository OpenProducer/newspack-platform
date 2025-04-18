<?php

namespace Google\Site_Kit_Dependencies\Psr\Http\Client;

use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface;
interface ClientInterface
{
    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws \Psr\Http\Client\ClientExceptionInterface If an error happens while processing the request.
     */
    public function sendRequest(\Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface $request) : \Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface;
}
