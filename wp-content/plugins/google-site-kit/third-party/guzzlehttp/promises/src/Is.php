<?php

declare (strict_types=1);
namespace Google\Site_Kit_Dependencies\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     */
    public static function pending(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     */
    public static function settled(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() !== \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     */
    public static function fulfilled(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     */
    public static function rejected(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
