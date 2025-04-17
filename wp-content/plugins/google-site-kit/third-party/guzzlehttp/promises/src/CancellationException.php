<?php

declare (strict_types=1);
namespace Google\Site_Kit_Dependencies\GuzzleHttp\Promise;

/**
 * Exception that is set as the reason for a promise that has been cancelled.
 */
class CancellationException extends \Google\Site_Kit_Dependencies\GuzzleHttp\Promise\RejectionException
{
}
