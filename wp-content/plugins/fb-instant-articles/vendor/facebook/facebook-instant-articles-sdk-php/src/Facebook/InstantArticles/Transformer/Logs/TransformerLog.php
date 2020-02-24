<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Logs;

use Facebook\InstantArticles\Elements\Element;
use Facebook\InstantArticles\Validators\Type;

class TransformerLog
{
    /**
     * OFF means no log will be generated during transformation.
     */
    const OFF = 'OFF';

    /**
     * INFO **default** Just the informative logs will be turned on.
     */
    const INFO = 'INFO';

    /**
     * DEBUG means all verbose information will be active for debugging purposes.
     */
    const DEBUG = 'DEBUG';

    /**
     * ERROR means only errors will be shown.
     */
    const ERROR = 'ERROR';

    private static $LOG_DEFINITION = array(
        self::OFF => 0,
        self::ERROR => 1,
        self::INFO => 2,
        self::DEBUG => 3
    );

    /**
     * @var string $logLevel The log level set into this transformer instance. Possible values: OFF, INFO or DEBUG
     */
    private static $logLevel = self::INFO;

    /**
     * @var The log level
     */
    private $level;

    /**
     * @var String the log message
     */
    private $message;

    /**
     * @param string $level. Can be any of these: @see TransformerLog::OFF, @see TransformerLog::INFO, @see TransformerLog::DEBUG, @see TransformerLog::ERROR
     * @param string $message The log message itself
     */
    public function __construct($level, $message)
    {
        $level = strtoupper($level);
        Type::enforceWithin(
            $level,
            [
                self::OFF,
                self::INFO,
                self::DEBUG,
                self::ERROR
            ]
        );
        $this->level = $level;
        $this->message = $message;
    }

    /**
     * Sets the log level from transformer. This should be set before @see Transformer::transform or @see Transformer::transformString are called.
     * The default level is TransformerLog::INFO
     * @param $level string The log level to be setted.
     * @see TransformerLog::OFF, TransformerLog::INFO, TransformerLog::DEBUG, TransformerLog::ERROR
     */
    public static function setLevel($level)
    {
        $level = strtoupper($level);
        Type::enforceWithin(
            $level,
            [
                self::OFF,
                self::INFO,
                self::DEBUG,
                self::ERROR
            ]
        );
        self::$logLevel = $level;
    }

    /**
     * @return bool if $level informed is compatible with the @see self::setLogLevel.
     * $level must be >= than @see self::setLogLevel.
     * Order of levels:  OFF < DEBUG < INFO < ERROR
     */
    public static function isLevelEnabled($level)
    {
        $logLevel = self::$LOG_DEFINITION[self::$logLevel];
        $levelChecked = self::$LOG_DEFINITION[$level];

        return $logLevel >= $levelChecked;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "[$this->level] $this->message";
    }

    /**
     * @return string Message level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @return string Message
     */
    public function getMessage()
    {
        return $this->message;
    }
}
