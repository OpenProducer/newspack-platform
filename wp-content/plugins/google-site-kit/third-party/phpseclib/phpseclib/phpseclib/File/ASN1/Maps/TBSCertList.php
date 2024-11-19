<?php

/**
 * TBSCertList
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
 * TBSCertList
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class TBSCertList
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['version' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_INTEGER, 'mapping' => ['v1', 'v2', 'v3'], 'optional' => \true, 'default' => 'v2'], 'signature' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\AlgorithmIdentifier::MAP, 'issuer' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\Name::MAP, 'thisUpdate' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\Time::MAP, 'nextUpdate' => ['optional' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\Time::MAP, 'revokedCertificates' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'optional' => \true, 'min' => 0, 'max' => -1, 'children' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\RevokedCertificate::MAP], 'crlExtensions' => ['constant' => 0, 'optional' => \true, 'explicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\Extensions::MAP]];
}
