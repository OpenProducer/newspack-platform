<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Google\Site_Kit_Dependencies\Monolog\Handler;

use Google\Site_Kit_Dependencies\Monolog\Formatter\FormatterInterface;
use Google\Site_Kit_Dependencies\Monolog\Formatter\NormalizerFormatter;
use Google\Site_Kit_Dependencies\Monolog\Logger;
/**
 * Handler sending logs to Zend Monitor
 *
 * @author  Christian Bergau <cbergau86@gmail.com>
 * @author  Jason Davis <happydude@jasondavis.net>
 *
 * @phpstan-import-type FormattedRecord from AbstractProcessingHandler
 */
class ZendMonitorHandler extends \Google\Site_Kit_Dependencies\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * Monolog level / ZendMonitor Custom Event priority map
     *
     * @var array<int, int>
     */
    protected $levelMap = [];
    /**
     * @throws MissingExtensionException
     */
    public function __construct($level = \Google\Site_Kit_Dependencies\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (!\function_exists('Google\\Site_Kit_Dependencies\\zend_monitor_custom_event')) {
            throw new \Google\Site_Kit_Dependencies\Monolog\Handler\MissingExtensionException('You must have Zend Server installed with Zend Monitor enabled in order to use this handler');
        }
        //zend monitor constants are not defined if zend monitor is not enabled.
        $this->levelMap = [\Google\Site_Kit_Dependencies\Monolog\Logger::DEBUG => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_INFO, \Google\Site_Kit_Dependencies\Monolog\Logger::INFO => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_INFO, \Google\Site_Kit_Dependencies\Monolog\Logger::NOTICE => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_INFO, \Google\Site_Kit_Dependencies\Monolog\Logger::WARNING => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_WARNING, \Google\Site_Kit_Dependencies\Monolog\Logger::ERROR => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \Google\Site_Kit_Dependencies\Monolog\Logger::CRITICAL => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \Google\Site_Kit_Dependencies\Monolog\Logger::ALERT => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \Google\Site_Kit_Dependencies\Monolog\Logger::EMERGENCY => \Google\Site_Kit_Dependencies\ZEND_MONITOR_EVENT_SEVERITY_ERROR];
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->writeZendMonitorCustomEvent(\Google\Site_Kit_Dependencies\Monolog\Logger::getLevelName($record['level']), $record['message'], $record['formatted'], $this->levelMap[$record['level']]);
    }
    /**
     * Write to Zend Monitor Events
     * @param string $type      Text displayed in "Class Name (custom)" field
     * @param string $message   Text displayed in "Error String"
     * @param array  $formatted Displayed in Custom Variables tab
     * @param int    $severity  Set the event severity level (-1,0,1)
     *
     * @phpstan-param FormattedRecord $formatted
     */
    protected function writeZendMonitorCustomEvent(string $type, string $message, array $formatted, int $severity) : void
    {
        zend_monitor_custom_event($type, $message, $formatted, $severity);
    }
    /**
     * {@inheritDoc}
     */
    public function getDefaultFormatter() : \Google\Site_Kit_Dependencies\Monolog\Formatter\FormatterInterface
    {
        return new \Google\Site_Kit_Dependencies\Monolog\Formatter\NormalizerFormatter();
    }
    /**
     * @return array<int, int>
     */
    public function getLevelMap() : array
    {
        return $this->levelMap;
    }
}
