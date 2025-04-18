<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp\Exception;

use Google\Site_Kit_Dependencies\GuzzleHttp\BodySummarizer;
use Google\Site_Kit_Dependencies\GuzzleHttp\BodySummarizerInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Client\RequestExceptionInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface;
/**
 * HTTP Request exception
 */
class RequestException extends \Google\Site_Kit_Dependencies\GuzzleHttp\Exception\TransferException implements \Google\Site_Kit_Dependencies\Psr\Http\Client\RequestExceptionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface|null
     */
    private $response;
    /**
     * @var array
     */
    private $handlerContext;
    public function __construct(string $message, \Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface $request, ?\Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface $response = null, ?\Throwable $previous = null, array $handlerContext = [])
    {
        // Set the code of the exception if the response is set and not future.
        $code = $response ? $response->getStatusCode() : 0;
        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
        $this->handlerContext = $handlerContext;
    }
    /**
     * Wrap non-RequestExceptions with a RequestException
     */
    public static function wrapException(\Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface $request, \Throwable $e) : \Google\Site_Kit_Dependencies\GuzzleHttp\Exception\RequestException
    {
        return $e instanceof \Google\Site_Kit_Dependencies\GuzzleHttp\Exception\RequestException ? $e : new \Google\Site_Kit_Dependencies\GuzzleHttp\Exception\RequestException($e->getMessage(), $request, null, $e);
    }
    /**
     * Factory method to create a new exception with a normalized error message
     *
     * @param RequestInterface             $request        Request sent
     * @param ResponseInterface            $response       Response received
     * @param \Throwable|null              $previous       Previous exception
     * @param array                        $handlerContext Optional handler context
     * @param BodySummarizerInterface|null $bodySummarizer Optional body summarizer
     */
    public static function create(\Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface $request, ?\Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface $response = null, ?\Throwable $previous = null, array $handlerContext = [], ?\Google\Site_Kit_Dependencies\GuzzleHttp\BodySummarizerInterface $bodySummarizer = null) : self
    {
        if (!$response) {
            return new self('Error completing request', $request, null, $previous, $handlerContext);
        }
        $level = (int) \floor($response->getStatusCode() / 100);
        if ($level === 4) {
            $label = 'Client error';
            $className = \Google\Site_Kit_Dependencies\GuzzleHttp\Exception\ClientException::class;
        } elseif ($level === 5) {
            $label = 'Server error';
            $className = \Google\Site_Kit_Dependencies\GuzzleHttp\Exception\ServerException::class;
        } else {
            $label = 'Unsuccessful request';
            $className = __CLASS__;
        }
        $uri = \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Utils::redactUserInfo($request->getUri());
        // Client Error: `GET /` resulted in a `404 Not Found` response:
        // <html> ... (truncated)
        $message = \sprintf('%s: `%s %s` resulted in a `%s %s` response', $label, $request->getMethod(), $uri->__toString(), $response->getStatusCode(), $response->getReasonPhrase());
        $summary = ($bodySummarizer ?? new \Google\Site_Kit_Dependencies\GuzzleHttp\BodySummarizer())->summarize($response);
        if ($summary !== null) {
            $message .= ":\n{$summary}\n";
        }
        return new $className($message, $request, $response, $previous, $handlerContext);
    }
    /**
     * Get the request that caused the exception
     */
    public function getRequest() : \Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface
    {
        return $this->request;
    }
    /**
     * Get the associated response
     */
    public function getResponse() : ?\Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface
    {
        return $this->response;
    }
    /**
     * Check if a response was received
     */
    public function hasResponse() : bool
    {
        return $this->response !== null;
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
