<?php

declare (strict_types=1);
namespace Google\Site_Kit_Dependencies\GuzzleHttp\Psr7;

use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestFactoryInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseFactoryInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\ServerRequestFactoryInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\ServerRequestInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\StreamFactoryInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\StreamInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\UploadedFileFactoryInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\UploadedFileInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\UriFactoryInterface;
use Google\Site_Kit_Dependencies\Psr\Http\Message\UriInterface;
/**
 * Implements all of the PSR-17 interfaces.
 *
 * Note: in consuming code it is recommended to require the implemented interfaces
 * and inject the instance of this class multiple times.
 */
final class HttpFactory implements \Google\Site_Kit_Dependencies\Psr\Http\Message\RequestFactoryInterface, \Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseFactoryInterface, \Google\Site_Kit_Dependencies\Psr\Http\Message\ServerRequestFactoryInterface, \Google\Site_Kit_Dependencies\Psr\Http\Message\StreamFactoryInterface, \Google\Site_Kit_Dependencies\Psr\Http\Message\UploadedFileFactoryInterface, \Google\Site_Kit_Dependencies\Psr\Http\Message\UriFactoryInterface
{
    public function createUploadedFile(\Google\Site_Kit_Dependencies\Psr\Http\Message\StreamInterface $stream, ?int $size = null, int $error = \UPLOAD_ERR_OK, ?string $clientFilename = null, ?string $clientMediaType = null) : \Google\Site_Kit_Dependencies\Psr\Http\Message\UploadedFileInterface
    {
        if ($size === null) {
            $size = $stream->getSize();
        }
        return new \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\UploadedFile($stream, $size, $error, $clientFilename, $clientMediaType);
    }
    public function createStream(string $content = '') : \Google\Site_Kit_Dependencies\Psr\Http\Message\StreamInterface
    {
        return \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Utils::streamFor($content);
    }
    public function createStreamFromFile(string $file, string $mode = 'r') : \Google\Site_Kit_Dependencies\Psr\Http\Message\StreamInterface
    {
        try {
            $resource = \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Utils::tryFopen($file, $mode);
        } catch (\RuntimeException $e) {
            if ('' === $mode || \false === \in_array($mode[0], ['r', 'w', 'a', 'x', 'c'], \true)) {
                throw new \InvalidArgumentException(\sprintf('Invalid file opening mode "%s"', $mode), 0, $e);
            }
            throw $e;
        }
        return \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Utils::streamFor($resource);
    }
    public function createStreamFromResource($resource) : \Google\Site_Kit_Dependencies\Psr\Http\Message\StreamInterface
    {
        return \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Utils::streamFor($resource);
    }
    public function createServerRequest(string $method, $uri, array $serverParams = []) : \Google\Site_Kit_Dependencies\Psr\Http\Message\ServerRequestInterface
    {
        if (empty($method)) {
            if (!empty($serverParams['REQUEST_METHOD'])) {
                $method = $serverParams['REQUEST_METHOD'];
            } else {
                throw new \InvalidArgumentException('Cannot determine HTTP method');
            }
        }
        return new \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\ServerRequest($method, $uri, [], null, '1.1', $serverParams);
    }
    public function createResponse(int $code = 200, string $reasonPhrase = '') : \Google\Site_Kit_Dependencies\Psr\Http\Message\ResponseInterface
    {
        return new \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Response($code, [], null, '1.1', $reasonPhrase);
    }
    public function createRequest(string $method, $uri) : \Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface
    {
        return new \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Request($method, $uri);
    }
    public function createUri(string $uri = '') : \Google\Site_Kit_Dependencies\Psr\Http\Message\UriInterface
    {
        return new \Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Uri($uri);
    }
}
