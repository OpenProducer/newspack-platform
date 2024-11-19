<?php

/**
 * PuTTY Formatted EC Key Handler
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\Formats\Keys;

use Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings;
use Google\Site_Kit_Dependencies\phpseclib3\Crypt\Common\Formats\Keys\PuTTY as Progenitor;
use Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\BaseCurves\Base as BaseCurve;
use Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\BaseCurves\TwistedEdwards as TwistedEdwardsCurve;
use Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger;
/**
 * PuTTY Formatted EC Key Handler
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class PuTTY extends \Google\Site_Kit_Dependencies\phpseclib3\Crypt\Common\Formats\Keys\PuTTY
{
    use Common;
    /**
     * Public Handler
     *
     * @var string
     */
    const PUBLIC_HANDLER = 'Google\\Site_Kit_Dependencies\\phpseclib3\\Crypt\\EC\\Formats\\Keys\\OpenSSH';
    /**
     * Supported Key Types
     *
     * @var array
     */
    protected static $types = ['ecdsa-sha2-nistp256', 'ecdsa-sha2-nistp384', 'ecdsa-sha2-nistp521', 'ssh-ed25519'];
    /**
     * Break a public or private key down into its constituent components
     *
     * @param string $key
     * @param string $password optional
     * @return array
     */
    public static function load($key, $password = '')
    {
        $components = parent::load($key, $password);
        if (!isset($components['private'])) {
            return $components;
        }
        $private = $components['private'];
        $temp = \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::base64_encode(\Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::packSSH2('s', $components['type']) . $components['public']);
        $components = \Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\Formats\Keys\OpenSSH::load($components['type'] . ' ' . $temp . ' ' . $components['comment']);
        if ($components['curve'] instanceof \Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\BaseCurves\TwistedEdwards) {
            if (\Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::shift($private, 4) != "\x00\x00\x00 ") {
                throw new \RuntimeException('Length of ssh-ed25519 key should be 32');
            }
            $arr = $components['curve']->extractSecret($private);
            $components['dA'] = $arr['dA'];
            $components['secret'] = $arr['secret'];
        } else {
            list($components['dA']) = \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::unpackSSH2('i', $private);
            $components['curve']->rangeCheck($components['dA']);
        }
        return $components;
    }
    /**
     * Convert a private key to the appropriate format.
     *
     * @param BigInteger $privateKey
     * @param BaseCurve $curve
     * @param \phpseclib3\Math\Common\FiniteField\Integer[] $publicKey
     * @param string $secret optional
     * @param string $password optional
     * @param array $options optional
     * @return string
     */
    public static function savePrivateKey(\Google\Site_Kit_Dependencies\phpseclib3\Math\BigInteger $privateKey, \Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\BaseCurves\Base $curve, array $publicKey, $secret = null, $password = \false, array $options = [])
    {
        self::initialize_static_variables();
        $public = \explode(' ', \Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\Formats\Keys\OpenSSH::savePublicKey($curve, $publicKey));
        $name = $public[0];
        $public = \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::base64_decode($public[1]);
        list(, $length) = \unpack('N', \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::shift($public, 4));
        \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::shift($public, $length);
        // PuTTY pads private keys with a null byte per the following:
        // https://github.com/github/putty/blob/a3d14d77f566a41fc61dfdc5c2e0e384c9e6ae8b/sshecc.c#L1926
        if (!$curve instanceof \Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\BaseCurves\TwistedEdwards) {
            $private = $privateKey->toBytes();
            if (!(\strlen($privateKey->toBits()) & 7)) {
                $private = "\x00{$private}";
            }
        }
        $private = $curve instanceof \Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\BaseCurves\TwistedEdwards ? \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::packSSH2('s', $secret) : \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::packSSH2('s', $private);
        return self::wrapPrivateKey($public, $private, $name, $password, $options);
    }
    /**
     * Convert an EC public key to the appropriate format
     *
     * @param BaseCurve $curve
     * @param \phpseclib3\Math\Common\FiniteField[] $publicKey
     * @return string
     */
    public static function savePublicKey(\Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\BaseCurves\Base $curve, array $publicKey)
    {
        $public = \explode(' ', \Google\Site_Kit_Dependencies\phpseclib3\Crypt\EC\Formats\Keys\OpenSSH::savePublicKey($curve, $publicKey));
        $type = $public[0];
        $public = \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::base64_decode($public[1]);
        list(, $length) = \unpack('N', \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::shift($public, 4));
        \Google\Site_Kit_Dependencies\phpseclib3\Common\Functions\Strings::shift($public, $length);
        return self::wrapPublicKey($public, $type);
    }
}
