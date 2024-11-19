<?php

/**
 * AttributeTypeAndValue
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
 * AttributeTypeAndValue
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class AttributeTypeAndValue
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\AttributeType::MAP, 'value' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\AttributeValue::MAP]];
}
