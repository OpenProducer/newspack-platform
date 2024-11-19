<?php

/**
 * ORAddress
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
 * ORAddress
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class ORAddress
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['built-in-standard-attributes' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\BuiltInStandardAttributes::MAP, 'built-in-domain-defined-attributes' => ['optional' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\BuiltInDomainDefinedAttributes::MAP, 'extension-attributes' => ['optional' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\ExtensionAttributes::MAP]];
}
