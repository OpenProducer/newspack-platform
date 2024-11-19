<?php

/**
 * DistributionPoint
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps;

use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1;
/**
 * DistributionPoint
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class DistributionPoint
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['distributionPoint' => ['constant' => 0, 'optional' => \true, 'explicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DistributionPointName::MAP, 'reasons' => ['constant' => 1, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\ReasonFlags::MAP, 'cRLIssuer' => ['constant' => 2, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\GeneralNames::MAP]];
}
