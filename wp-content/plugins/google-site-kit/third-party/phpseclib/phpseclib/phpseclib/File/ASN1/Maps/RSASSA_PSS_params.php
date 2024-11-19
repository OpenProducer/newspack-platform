<?php

/**
 * RSASSA_PSS_params
 *
 * As defined in https://tools.ietf.org/html/rfc4055#section-3.1
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
 * RSASSA_PSS_params
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class RSASSA_PSS_params
{
    const MAP = ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['hashAlgorithm' => ['constant' => 0, 'optional' => \true, 'explicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\HashAlgorithm::MAP, 'maskGenAlgorithm' => ['constant' => 1, 'optional' => \true, 'explicit' => \true] + \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\MaskGenAlgorithm::MAP, 'saltLength' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_INTEGER, 'constant' => 2, 'optional' => \true, 'explicit' => \true, 'default' => 20], 'trailerField' => ['type' => \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::TYPE_INTEGER, 'constant' => 3, 'optional' => \true, 'explicit' => \true, 'default' => 1]]];
}
