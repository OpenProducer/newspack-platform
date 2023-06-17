<?php
/**
 * @license MIT
 *
 * Modified by the-events-calendar on 15-June-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace TEC\Common\Psr\Log;

/**
 * Describes a logger-aware instance.
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger);
}
