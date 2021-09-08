<?php

namespace Google\Site_Kit_Dependencies;

if (\class_exists('Google\\Site_Kit_Dependencies\\Google_Client', \false)) {
    // Prevent error with preloading in PHP 7.4
    // @see https://github.com/googleapis/google-api-php-client/issues/1976
    return;
}
$classMap = ['Google\\Site_Kit_Dependencies\\Google\\Client' => 'Google\\Site_Kit_Dependencies\Google_Client', 'Google\\Site_Kit_Dependencies\\Google\\Service' => 'Google\\Site_Kit_Dependencies\Google_Service', 'Google\\Site_Kit_Dependencies\\Google\\AccessToken\\Revoke' => 'Google\\Site_Kit_Dependencies\Google_AccessToken_Revoke', 'Google\\Site_Kit_Dependencies\\Google\\AccessToken\\Verify' => 'Google\\Site_Kit_Dependencies\Google_AccessToken_Verify', 'Google\\Site_Kit_Dependencies\\Google\\Model' => 'Google\\Site_Kit_Dependencies\Google_Model', 'Google\\Site_Kit_Dependencies\\Google\\Utils\\UriTemplate' => 'Google\\Site_Kit_Dependencies\Google_Utils_UriTemplate', 'Google\\Site_Kit_Dependencies\\Google\\AuthHandler\\Guzzle6AuthHandler' => 'Google\\Site_Kit_Dependencies\Google_AuthHandler_Guzzle6AuthHandler', 'Google\\Site_Kit_Dependencies\\Google\\AuthHandler\\Guzzle7AuthHandler' => 'Google\\Site_Kit_Dependencies\Google_AuthHandler_Guzzle7AuthHandler', 'Google\\Site_Kit_Dependencies\\Google\\AuthHandler\\Guzzle5AuthHandler' => 'Google\\Site_Kit_Dependencies\Google_AuthHandler_Guzzle5AuthHandler', 'Google\\Site_Kit_Dependencies\\Google\\AuthHandler\\AuthHandlerFactory' => 'Google\\Site_Kit_Dependencies\Google_AuthHandler_AuthHandlerFactory', 'Google\\Site_Kit_Dependencies\\Google\\Http\\Batch' => 'Google\\Site_Kit_Dependencies\Google_Http_Batch', 'Google\\Site_Kit_Dependencies\\Google\\Http\\MediaFileUpload' => 'Google\\Site_Kit_Dependencies\Google_Http_MediaFileUpload', 'Google\\Site_Kit_Dependencies\\Google\\Http\\REST' => 'Google\\Site_Kit_Dependencies\Google_Http_REST', 'Google\\Site_Kit_Dependencies\\Google\\Task\\Retryable' => 'Google\\Site_Kit_Dependencies\Google_Task_Retryable', 'Google\\Site_Kit_Dependencies\\Google\\Task\\Exception' => 'Google\\Site_Kit_Dependencies\Google_Task_Exception', 'Google\\Site_Kit_Dependencies\\Google\\Task\\Runner' => 'Google\\Site_Kit_Dependencies\Google_Task_Runner', 'Google\\Site_Kit_Dependencies\\Google\\Collection' => 'Google\\Site_Kit_Dependencies\Google_Collection', 'Google\\Site_Kit_Dependencies\\Google\\Service\\Exception' => 'Google\\Site_Kit_Dependencies\Google_Service_Exception', 'Google\\Site_Kit_Dependencies\\Google\\Service\\Resource' => 'Google\\Site_Kit_Dependencies\Google_Service_Resource', 'Google\\Site_Kit_Dependencies\\Google\\Exception' => 'Google\\Site_Kit_Dependencies\Google_Exception'];
foreach ($classMap as $class => $alias) {
    \class_alias($class, $alias);
}
/**
 * This class needs to be defined explicitly as scripts must be recognized by
 * the autoloader.
 */
class Google_Task_Composer extends \Google\Site_Kit_Dependencies\Google\Task\Composer
{
}
if (\false) {
    class Google_AccessToken_Revoke extends \Google\Site_Kit_Dependencies\Google\AccessToken\Revoke
    {
    }
    class Google_AccessToken_Verify extends \Google\Site_Kit_Dependencies\Google\AccessToken\Verify
    {
    }
    class Google_AuthHandler_AuthHandlerFactory extends \Google\Site_Kit_Dependencies\Google\AuthHandler\AuthHandlerFactory
    {
    }
    class Google_AuthHandler_Guzzle5AuthHandler extends \Google\Site_Kit_Dependencies\Google\AuthHandler\Guzzle5AuthHandler
    {
    }
    class Google_AuthHandler_Guzzle6AuthHandler extends \Google\Site_Kit_Dependencies\Google\AuthHandler\Guzzle6AuthHandler
    {
    }
    class Google_AuthHandler_Guzzle7AuthHandler extends \Google\Site_Kit_Dependencies\Google\AuthHandler\Guzzle7AuthHandler
    {
    }
    class Google_Client extends \Google\Site_Kit_Dependencies\Google\Client
    {
    }
    class Google_Collection extends \Google\Site_Kit_Dependencies\Google\Collection
    {
    }
    class Google_Exception extends \Google\Site_Kit_Dependencies\Google\Exception
    {
    }
    class Google_Http_Batch extends \Google\Site_Kit_Dependencies\Google\Http\Batch
    {
    }
    class Google_Http_MediaFileUpload extends \Google\Site_Kit_Dependencies\Google\Http\MediaFileUpload
    {
    }
    class Google_Http_REST extends \Google\Site_Kit_Dependencies\Google\Http\REST
    {
    }
    class Google_Model extends \Google\Site_Kit_Dependencies\Google\Model
    {
    }
    class Google_Service extends \Google\Site_Kit_Dependencies\Google\Service
    {
    }
    class Google_Service_Exception extends \Google\Site_Kit_Dependencies\Google\Service\Exception
    {
    }
    class Google_Service_Resource extends \Google\Site_Kit_Dependencies\Google\Service\Resource
    {
    }
    class Google_Task_Exception extends \Google\Site_Kit_Dependencies\Google\Task\Exception
    {
    }
    class Google_Task_Retryable extends \Google\Site_Kit_Dependencies\Google\Task\Retryable
    {
    }
    class Google_Task_Runner extends \Google\Site_Kit_Dependencies\Google\Task\Runner
    {
    }
    class Google_Utils_UriTemplate extends \Google\Site_Kit_Dependencies\Google\Utils\UriTemplate
    {
    }
}
