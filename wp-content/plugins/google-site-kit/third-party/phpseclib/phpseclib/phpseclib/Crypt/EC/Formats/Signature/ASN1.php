<?php

/**
 * ASN1 Signature Handler
 *
 * PHP version 5
 *
 * Handles signatures in the format described in
 * https://tools.ietf.org/html/rfc3279#section-2.2.3
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\Formats\Signature;

use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1 as Encoder;
use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\EcdsaSigValue;
use Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger;
/**
 * ASN1 Signature Handler
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class ASN1
{
    /**
     * Loads a signature
     *
     * @param string $sig
     * @return array
     */
    public static function load($sig)
    {
        if (!\is_string($sig)) {
            return \false;
        }
        $decoded = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::decodeBER($sig);
        if (empty($decoded)) {
            return \false;
        }
        $components = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::asn1map($decoded[0], \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\EcdsaSigValue::MAP);
        return $components;
    }
    /**
     * Returns a signature in the appropriate format
     *
     * @param BigInteger $r
     * @param BigInteger $s
     * @return string
     */
    public static function save(\Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $r, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $s)
    {
        return \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER(\compact('r', 's'), \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\EcdsaSigValue::MAP);
    }
}
