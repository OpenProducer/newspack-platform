<?php

/**
 * Class QRDataAbstract
 *
 * @filesource   QRDataAbstract.php
 * @created      25.11.2015
 * @package      chillerlan\QRCode\Data
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */
namespace YoastSEO_Vendor\chillerlan\QRCode\Data;

use YoastSEO_Vendor\chillerlan\QRCode\QRCode;
use YoastSEO_Vendor\chillerlan\QRCode\QRCodeException;
use YoastSEO_Vendor\chillerlan\QRCode\QROptions;
use YoastSEO_Vendor\chillerlan\QRCode\Helpers\BitBuffer;
use YoastSEO_Vendor\chillerlan\QRCode\Helpers\Polynomial;
use YoastSEO_Vendor\chillerlan\QRCode\Traits\ClassLoader;
/**
 * Processes the binary data and maps it on a matrix which is then being returned
 */
abstract class QRDataAbstract implements \YoastSEO_Vendor\chillerlan\QRCode\Data\QRDataInterface
{
    use ClassLoader;
    /**
     * the string byte count
     *
     * @var int
     */
    protected $strlen;
    /**
     * the current data mode: Num, Alphanum, Kanji, Byte
     *
     * @var int
     */
    protected $datamode;
    /**
     * mode length bits for the version breakpoints 1-9, 10-26 and 27-40
     *
     * @var array
     */
    protected $lengthBits = [0, 0, 0];
    /**
     * current QR Code version
     *
     * @var int
     */
    protected $version;
    /**
     * the raw data that's being passed to QRMatrix::mapData()
     *
     * @var array
     */
    protected $matrixdata;
    /**
     * ECC temp data
     *
     * @var array
     */
    protected $ecdata;
    /**
     * ECC temp data
     *
     * @var array
     */
    protected $dcdata;
    /**
     * @var \chillerlan\QRCode\QROptions
     */
    protected $options;
    /**
     * @var \chillerlan\QRCode\Helpers\BitBuffer
     */
    protected $bitBuffer;
    /**
     * QRDataInterface constructor.
     *
     * @param \chillerlan\QRCode\QROptions $options
     * @param string|null                  $data
     */
    public function __construct(\YoastSEO_Vendor\chillerlan\QRCode\QROptions $options, $data = null)
    {
        $this->options = $options;
        if ($data !== null) {
            $this->setData($data);
        }
    }
    /**
     * Sets the data string (internally called by the constructor)
     *
     * @param string $data
     *
     * @return \chillerlan\QRCode\Data\QRDataInterface
     */
    public function setData($data)
    {
        if ($this->datamode === \YoastSEO_Vendor\chillerlan\QRCode\QRCode::DATA_KANJI) {
            $data = \mb_convert_encoding($data, 'SJIS', \mb_detect_encoding($data));
        }
        $this->strlen = $this->getLength($data);
        $this->version = $this->options->version === \YoastSEO_Vendor\chillerlan\QRCode\QRCode::VERSION_AUTO ? $this->getMinimumVersion() : $this->options->version;
        $this->matrixdata = $this->writeBitBuffer($data)->maskECC();
        return $this;
    }
    /**
     * returns a fresh matrix object with the data written for the given $maskPattern
     *
     * @param int       $maskPattern
     * @param bool|null $test
     *
     * @return \chillerlan\QRCode\Data\QRMatrix
     */
    public function initMatrix($maskPattern, $test = null)
    {
        /** @var \chillerlan\QRCode\Data\QRMatrix $matrix */
        $matrix = $this->loadClass(\YoastSEO_Vendor\chillerlan\QRCode\Data\QRMatrix::class, null, $this->version, $this->options->eccLevel);
        return $matrix->setFinderPattern()->setSeparators()->setAlignmentPattern()->setTimingPattern()->setVersionNumber($test)->setFormatInfo($maskPattern, $test)->setDarkModule()->mapData($this->matrixdata, $maskPattern);
    }
    /**
     * returns the length bits for the version breakpoints 1-9, 10-26 and 27-40
     *
     * @return int
     * @throws \chillerlan\QRCode\Data\QRCodeDataException
     * @codeCoverageIgnore
     */
    protected function getLengthBits()
    {
        foreach ([9, 26, 40] as $key => $breakpoint) {
            if ($this->version <= $breakpoint) {
                return $this->lengthBits[$key];
            }
        }
        throw new \YoastSEO_Vendor\chillerlan\QRCode\Data\QRCodeDataException('invalid version number: ' . $this->version);
    }
    /**
     * returns the byte count of the $data string
     *
     * @param string $data
     *
     * @return int
     */
    protected function getLength($data)
    {
        return \strlen($data);
    }
    /**
     * returns the minimum version number for the given string
     *
     * @return int
     * @throws \chillerlan\QRCode\Data\QRCodeDataException
     */
    protected function getMinimumVersion()
    {
        // guess the version number within the given range
        foreach (\range(\max(1, $this->options->versionMin), \min($this->options->versionMax, 40)) as $version) {
            $maxlength = self::MAX_LENGTH[$version][\YoastSEO_Vendor\chillerlan\QRCode\QRCode::DATA_MODES[$this->datamode]][\YoastSEO_Vendor\chillerlan\QRCode\QRCode::ECC_MODES[$this->options->eccLevel]];
            if ($this->strlen <= $maxlength) {
                return $version;
            }
        }
        throw new \YoastSEO_Vendor\chillerlan\QRCode\Data\QRCodeDataException('data exceeds ' . $maxlength . ' characters');
    }
    /**
     * @see \chillerlan\QRCode\Data\QRDataAbstract::writeBitBuffer()
     *
     * @param string $data
     *
     * @return void
     */
    protected abstract function write($data);
    /**
     * writes the string data to the BitBuffer
     *
     * @param string $data
     *
     * @return \chillerlan\QRCode\Data\QRDataAbstract
     * @throws \chillerlan\QRCode\QRCodeException
     */
    protected function writeBitBuffer($data)
    {
        $this->bitBuffer = new \YoastSEO_Vendor\chillerlan\QRCode\Helpers\BitBuffer();
        // @todo: fixme, get real length
        $MAX_BITS = self::MAX_BITS[$this->version][\YoastSEO_Vendor\chillerlan\QRCode\QRCode::ECC_MODES[$this->options->eccLevel]];
        $this->bitBuffer->clear()->put($this->datamode, 4)->put($this->strlen, $this->getLengthBits());
        $this->write($data);
        // there was an error writing the BitBuffer data, which is... unlikely.
        if ($this->bitBuffer->length > $MAX_BITS) {
            throw new \YoastSEO_Vendor\chillerlan\QRCode\QRCodeException('code length overflow. (' . $this->bitBuffer->length . ' > ' . $MAX_BITS . 'bit)');
            // @codeCoverageIgnore
        }
        // end code.
        if ($this->bitBuffer->length + 4 <= $MAX_BITS) {
            $this->bitBuffer->put(0, 4);
        }
        // padding
        while ($this->bitBuffer->length % 8 !== 0) {
            $this->bitBuffer->putBit(\false);
        }
        // padding
        while (\true) {
            if ($this->bitBuffer->length >= $MAX_BITS) {
                break;
            }
            $this->bitBuffer->put(0xec, 8);
            if ($this->bitBuffer->length >= $MAX_BITS) {
                break;
            }
            $this->bitBuffer->put(0x11, 8);
        }
        return $this;
    }
    /**
     * ECC masking
     *
     * @see \chillerlan\QRCode\Data\QRDataAbstract::writeBitBuffer()
     *
     * @link http://www.thonky.com/qr-code-tutorial/error-correction-coding
     *
     * @return array
     */
    protected function maskECC()
    {
        list($l1, $l2, $b1, $b2) = self::RSBLOCKS[$this->version][\YoastSEO_Vendor\chillerlan\QRCode\QRCode::ECC_MODES[$this->options->eccLevel]];
        $rsBlocks = \array_fill(0, $l1, [$b1, $b2]);
        $rsCount = $l1 + $l2;
        $this->ecdata = \array_fill(0, $rsCount, null);
        $this->dcdata = $this->ecdata;
        if ($l2 > 0) {
            $rsBlocks = \array_merge($rsBlocks, \array_fill(0, $l2, [$b1 + 1, $b2 + 1]));
        }
        $totalCodeCount = 0;
        $maxDcCount = 0;
        $maxEcCount = 0;
        $offset = 0;
        foreach ($rsBlocks as $key => $block) {
            list($rsBlockTotal, $dcCount) = $block;
            $ecCount = $rsBlockTotal - $dcCount;
            $maxDcCount = \max($maxDcCount, $dcCount);
            $maxEcCount = \max($maxEcCount, $ecCount);
            $this->dcdata[$key] = \array_fill(0, $dcCount, null);
            foreach ($this->dcdata[$key] as $a => $_z) {
                $this->dcdata[$key][$a] = 0xff & $this->bitBuffer->buffer[$a + $offset];
            }
            list($num, $add) = $this->poly($key, $ecCount);
            foreach ($this->ecdata[$key] as $c => $_z) {
                $modIndex = $c + $add;
                $this->ecdata[$key][$c] = $modIndex >= 0 ? $num[$modIndex] : 0;
            }
            $offset += $dcCount;
            $totalCodeCount += $rsBlockTotal;
        }
        $data = \array_fill(0, $totalCodeCount, null);
        $index = 0;
        $mask = function ($arr, $count) use(&$data, &$index, $rsCount) {
            for ($x = 0; $x < $count; $x++) {
                for ($y = 0; $y < $rsCount; $y++) {
                    if ($x < \count($arr[$y])) {
                        $data[$index] = $arr[$y][$x];
                        $index++;
                    }
                }
            }
        };
        $mask($this->dcdata, $maxDcCount);
        $mask($this->ecdata, $maxEcCount);
        return $data;
    }
    /**
     * @param int $key
     * @param int $count
     *
     * @return int[]
     */
    protected function poly($key, $count)
    {
        $rsPoly = new \YoastSEO_Vendor\chillerlan\QRCode\Helpers\Polynomial();
        $modPoly = new \YoastSEO_Vendor\chillerlan\QRCode\Helpers\Polynomial();
        for ($i = 0; $i < $count; $i++) {
            $modPoly->setNum([1, $modPoly->gexp($i)]);
            $rsPoly->multiply($modPoly->getNum());
        }
        $rsPolyCount = \count($rsPoly->getNum());
        $modPoly->setNum($this->dcdata[$key], $rsPolyCount - 1)->mod($rsPoly->getNum());
        $this->ecdata[$key] = \array_fill(0, $rsPolyCount - 1, null);
        $num = $modPoly->getNum();
        return [$num, \count($num) - \count($this->ecdata[$key])];
    }
}
