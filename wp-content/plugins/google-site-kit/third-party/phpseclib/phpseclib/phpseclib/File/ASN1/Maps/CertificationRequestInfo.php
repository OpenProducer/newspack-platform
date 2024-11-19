<?php

/**
 * CertificationRequestInfo
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
 * CertificationRequestInfo
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class CertificationRequestInfo
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['version' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_INTEGER, 'mapping' => ['v1']], 'subject' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\Name::MAP, 'subjectPKInfo' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\SubjectPublicKeyInfo::MAP, 'attributes' => ['constant' => 0, 'optional' => \true, 'implicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\Attributes::MAP]];
}
