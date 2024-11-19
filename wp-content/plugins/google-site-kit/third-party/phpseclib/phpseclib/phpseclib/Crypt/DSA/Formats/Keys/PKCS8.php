<?php

/**
 * PKCS#8 Formatted DSA Key Handler
 *
 * PHP version 5
 *
 * Processes keys with the following headers:
 *
 * -----BEGIN ENCRYPTED PRIVATE KEY-----
 * -----BEGIN PRIVATE KEY-----
 * -----BEGIN PUBLIC KEY-----
 *
 * Analogous to ssh-keygen's pkcs8 format (as specified by -m). Although PKCS8
 * is specific to private keys it's basically creating a DER-encoded wrapper
 * for keys. This just extends that same concept to public keys (much like ssh-keygen)
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Google\Site_Kit_Dependencies\phpseclib3\Crypt\DSA\Formats\Keys;

use Google\Site_Kit_Dependencies\phpseclib3\Crypt\Common\Formats\Keys\PKCS8 as Progenitor;
use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1;
use Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps;
use Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger;
/**
 * PKCS#8 Formatted DSA Key Handler
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class PKCS8 extends \Google\Site_Kit_Dependencies\phpseclib3\Crypt\Common\Formats\Keys\PKCS8
{
    /**
     * OID Name
     *
     * @var string
     */
    const OID_NAME = 'id-dsa';
    /**
     * OID Value
     *
     * @var string
     */
    const OID_VALUE = '1.2.840.10040.4.1';
    /**
     * Child OIDs loaded
     *
     * @var bool
     */
    protected static $childOIDsLoaded = \false;
    /**
     * Break a public or private key down into its constituent components
     *
     * @param string $key
     * @param string $password optional
     * @return array
     */
    public static function load($key, $password = '')
    {
        $key = parent::load($key, $password);
        $type = isset($key['privateKey']) ? 'privateKey' : 'publicKey';
        $decoded = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::decodeBER($key[$type . 'Algorithm']['parameters']->element);
        if (!$decoded) {
            throw new \RuntimeException('Unable to decode BER of parameters');
        }
        $components = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::asn1map($decoded[0], \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DSAParams::MAP);
        if (!\is_array($components)) {
            throw new \RuntimeException('Unable to perform ASN1 mapping on parameters');
        }
        $decoded = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::decodeBER($key[$type]);
        if (empty($decoded)) {
            throw new \RuntimeException('Unable to decode BER');
        }
        $var = $type == 'privateKey' ? 'x' : 'y';
        $components[$var] = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::asn1map($decoded[0], \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DSAPublicKey::MAP);
        if (!$components[$var] instanceof \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger) {
            throw new \RuntimeException('Unable to perform ASN1 mapping');
        }
        if (isset($key['meta'])) {
            $components['meta'] = $key['meta'];
        }
        return $components;
    }
    /**
     * Convert a private key to the appropriate format.
     *
     * @param BigInteger $p
     * @param BigInteger $q
     * @param BigInteger $g
     * @param BigInteger $y
     * @param BigInteger $x
     * @param string $password optional
     * @param array $options optional
     * @return string
     */
    public static function savePrivateKey(\Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $p, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $q, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $g, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $y, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $x, $password = '', array $options = [])
    {
        $params = ['p' => $p, 'q' => $q, 'g' => $g];
        $params = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($params, \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DSAParams::MAP);
        $params = new \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Element($params);
        $key = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($x, \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DSAPublicKey::MAP);
        return self::wrapPrivateKey($key, [], $params, $password, null, '', $options);
    }
    /**
     * Convert a public key to the appropriate format
     *
     * @param BigInteger $p
     * @param BigInteger $q
     * @param BigInteger $g
     * @param BigInteger $y
     * @param array $options optional
     * @return string
     */
    public static function savePublicKey(\Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $p, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $q, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $g, \Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $y, array $options = [])
    {
        $params = ['p' => $p, 'q' => $q, 'g' => $g];
        $params = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($params, \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DSAParams::MAP);
        $params = new \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Element($params);
        $key = \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1::encodeDER($y, \Google\Site_Kit_Dependencies\phpseclib3\File\ASN1\Maps\DSAPublicKey::MAP);
        return self::wrapPublicKey($key, $params);
    }
}
