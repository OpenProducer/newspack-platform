<?php

/**
 * FieldID
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
 * FieldID
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class FieldID
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['fieldType' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_OBJECT_IDENTIFIER], 'parameters' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_ANY, 'optional' => \true]]];
}
