<?php

/**
 * Class Number
 *
 * @filesource   Number.php
 * @created      26.11.2015
 * @package      QRCode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */
namespace YoastSEO_Vendor\chillerlan\QRCode\Data;

use YoastSEO_Vendor\chillerlan\QRCode\QRCode;
/**
 * Numeric mode: decimal digits 0 through 9
 */
class Number extends \YoastSEO_Vendor\chillerlan\QRCode\Data\QRDataAbstract
{
    /**
     * @inheritdoc
     */
    protected $datamode = \YoastSEO_Vendor\chillerlan\QRCode\QRCode::DATA_NUMBER;
    /**
     * @inheritdoc
     */
    protected $lengthBits = [10, 12, 14];
    /**
     * @inheritdoc
     */
    protected function write($data)
    {
        $i = 0;
        while ($i + 2 < $this->strlen) {
            $this->bitBuffer->put($this->parseInt(\substr($data, $i, 3)), 10);
            $i += 3;
        }
        if ($i < $this->strlen) {
            if ($this->strlen - $i === 1) {
                $this->bitBuffer->put($this->parseInt(\substr($data, $i, $i + 1)), 4);
            } elseif ($this->strlen - $i === 2) {
                $this->bitBuffer->put($this->parseInt(\substr($data, $i, $i + 2)), 7);
            }
            // @codeCoverageIgnoreEnd
        }
    }
    /**
     * @param string $string
     *
     * @return int
     * @throws \chillerlan\QRCode\Data\QRCodeDataException
     */
    protected function parseInt($string)
    {
        $num = 0;
        $map = \str_split('0123456789');
        $len = \strlen($string);
        for ($i = 0; $i < $len; $i++) {
            $c = \ord($string[$i]);
            if (!\in_array($string[$i], $map, \true)) {
                throw new \YoastSEO_Vendor\chillerlan\QRCode\Data\QRCodeDataException('illegal char: "' . $string[$i] . '" [' . $c . ']');
            }
            $c = $c - \ord('0');
            $num = $num * 10 + $c;
        }
        return $num;
    }
}
