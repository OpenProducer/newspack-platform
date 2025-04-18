<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp\Exception;

use Google\Site_Kit_Dependencies\Psr\Http\Client\NetworkExceptionInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
/**
 * Exception thrown when a connection cannot be established.
 *
 * Note that no response is present for a ConnectException
 */
class ConnectException extends \Google\Site_Kit_Dependencies\GuzzleHttp\Exception\TransferException implements \Google\Site_Kit_Dependencies\Psr\Http\Client\NetworkExceptionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var array
     */
    private $handlerContext;
    public function __construct(string $message, \Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface $request, ?\Throwable $previous = null, array $handlerContext = [])
    {
        parent::__construct($message, 0, $previous);
        $this->request = $request;
        $this->handlerContext = $handlerContext;
    }
    /**
     * Get the request that caused the exception
     */
    public function getRequest() : \Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface
    {
        return $this->request;
    }
    /**
     * Get contextual information about the error from the underlying handler.
     *
     * The contents of this array will vary depending on which handler you are
     * using. It may also be just an empty array. Relying on this data will
     * couple you to a specific handler, but can give more debug information
     * when needed.
     */
    public function getHandlerContext() : array
    {
        return $this->handlerContext;
    }
}
