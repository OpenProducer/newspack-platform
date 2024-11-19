<?php

/**
 * AuthorityKeyIdentifier
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
 * AuthorityKeyIdentifier
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class AuthorityKeyIdentifier
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['keyIdentifier' => ['constant' => 0, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\KeyIdentifier::MAP, 'authorityCertIssuer' => ['constant' => 1, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\GeneralNames::MAP, 'authorityCertSerialNumber' => ['constant' => 2, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\CertificateSerialNumber::MAP]];
}
