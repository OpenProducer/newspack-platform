<?php

/**
 * @file
 * Dispatch events when patches are applied.
 */
namespace Google\Site_Kit_Dependencies\cweagans\Composer;

use Google\Site_Kit_Dependencies\Composer\EventDispatcher\Event;
use Google\Site_Kit_Dependencies\Composer\Package\PackageInterface;
class PatchEvent extends \Google\Site_Kit_Dependencies\Composer\EventDispatcher\Event
{
    /**
     * @var PackageInterface $package
     */
    protected $package;
    /**
     * @var string $url
     */
    protected $url;
    /**
     * @var string $description
     */
    protected $description;
    /**
     * Constructs a PatchEvent object.
     *
     * @param string $eventName
     * @param PackageInterface $package
     * @param string $url
     * @param string $description
     */
    public function __construct($eventName, \Google\Site_Kit_Dependencies\Composer\Package\PackageInterface $package, $url, $description)
    {
        parent::__construct($eventName);
        $this->package = $package;
        $this->url = $url;
        $this->description = $description;
    }
    /**
     * Returns the package that is patched.
     *
     * @return PackageInterface
     */
    public function getPackage()
    {
        return $this->package;
    }
    /**
     * Returns the url of the patch.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * Returns the description of the patch.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
