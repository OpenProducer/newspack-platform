<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp\Handler;

use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
interface CurlFactoryInterface
{
    /**
     * Creates a cURL handle resource.
     *
     * @param RequestInterface $request Request
     * @param array            $options Transfer options
     *
     * @throws \RuntimeException when an option cannot be applied
     */
    public function create(\Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface $request, array $options) : \Google\Site_Kit_Dependencies\GuzzleHttp\Handler\EasyHandle;
    /**
     * Release an easy handle, allowing it to be reused or closed.
     *
     * This function must call unset on the easy handle's "handle" property.
     */
    public function release(\Google\Site_Kit_Dependencies\GuzzleHttp\Handler\EasyHandle $easy) : void;
}
