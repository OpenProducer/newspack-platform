<?php

namespace Google\Site_Kit_Dependencies\GuzzleHttp\Ring\Future;

use Google\Site_Kit_Dependencies\React\Promise\PromiseInterface;
use Google\Site_Kit_Dependencies\React\Promise\PromisorInterface;
/**
 * Represents the result of a computation that may not have completed yet.
 *
 * You can use the future in a blocking manner using the wait() function, or
 * you can use a promise from the future to receive the result when the future
 * has been resolved.
 *
 * When the future is dereferenced using wait(), the result of the computation
 * is cached and returned for subsequent calls to wait(). If the result of the
 * computation has not yet completed when wait() is called, the call to wait()
 * will block until the future has completed.
 */
interface FutureInterface extends \Google\Site_Kit_Dependencies\React\Promise\PromiseInterface, \Google\Site_Kit_Dependencies\React\Promise\PromisorInterface
{
    /**
     * Returns the result of the future either from cache or by blocking until
     * it is complete.
     *
     * This method must block until the future has a result or is cancelled.
     * Throwing an exception in the wait() method will mark the future as
     * realized and will throw the exception each time wait() is called.
     * Throwing an instance of GuzzleHttp\Ring\CancelledException will mark
     * the future as realized, will not throw immediately, but will throw the
     * exception if the future's wait() method is called again.
     *
     * @return mixed
     */
    public function wait();
    /**
     * Cancels the future, if possible.
     */
    public function cancel();
}
