<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
